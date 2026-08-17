<?php

namespace App\Services;

use Config\Database;
use Config\Expired;

class ExpiredNotificationService
{
    protected $db;
    protected $config;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->config = new Expired();
    }

    /**
     * Determines the priority level based on days remaining.
     */
    public function getPriority($sisaHari)
    {
        if ($sisaHari < -30) {
            return 'CRITICAL';
        } elseif ($sisaHari < 0) {
            return 'HIGH';
        } elseif ($sisaHari <= 30) {
            return 'WARNING';
        } elseif ($sisaHari <= 90) {
            return 'INFO';
        }
        return 'AMAN';
    }

    /**
     * Retrieves unread notifications for a specific user.
     * Uses cache to avoid recalculating if nothing changed.
     */
    public function getNotificationsForUser($userId)
    {
        $cacheKey = 'expired_notifs_user_' . $userId . '_' . date('Ymd');
        if ($cached = cache($cacheKey)) {
            // Check if any stock changed globally, we can use a global cache version
            $globalVersion = cache('expired_global_version');
            if (isset($cached['version']) && $cached['version'] === $globalVersion) {
                return $cached;
            }
        }

        // We only care about internal warehouse items (kategori_gudang = Internal is for users, but here we just query batch)
        $builder = $this->db->table('batch b');
        $builder->select('b.*, br.nama_barang');
        $builder->join('barang br', 'br.id = b.id_barang', 'left');
        $builder->where('b.stok_saat_ini >', 0);
        
        $batches = $builder->get()->getResultArray();
        
        $today = new \DateTime(date('Y-m-d'));
        
        // Fetch read records for this user
        $reads = $this->db->table('notification_reads')
                          ->where('user_id', $userId)
                          ->get()->getResultArray();
                          
        $readMap = [];
        foreach ($reads as $r) {
            $readMap[$r['batch_id'] . '_' . $r['priority']] = true;
        }

        $notifications = [
            'CRITICAL' => [],
            'HIGH'     => [],
            'WARNING'  => [],
            'INFO'     => [],
        ];
        
        $unreadCount = 0;

        foreach ($batches as $batch) {
            $expDate = new \DateTime($batch['tanggal_kedaluwarsa']);
            $diff = (int) $today->diff($expDate)->format('%R%a');
            
            $priority = $this->getPriority($diff);
            
            if ($priority === 'AMAN') {
                continue;
            }

            // Check if this specific priority alert was already read by user
            $isRead = isset($readMap[$batch['id'] . '_' . $priority]);
            
            // Further check: Only show if it hits exact milestone, OR if it's unread
            // But actually, if it's unread, we show it.
            if (!$isRead) {
                $batch['sisa_hari'] = $diff;
                $batch['priority'] = $priority;
                $notifications[$priority][] = $batch;
                $unreadCount++;
            }
        }

        // Sort items by sisa_hari ASC inside each priority
        foreach (['CRITICAL', 'HIGH', 'WARNING', 'INFO'] as $p) {
            usort($notifications[$p], function($a, $b) {
                return $a['sisa_hari'] <=> $b['sisa_hari'];
            });
        }

        $result = [
            'count' => $unreadCount,
            'items' => array_merge(
                $notifications['CRITICAL'], 
                $notifications['HIGH'], 
                $notifications['WARNING'], 
                $notifications['INFO']
            )
        ];

        // Store in cache
        $version = cache('expired_global_version') ?: time();
        cache()->save('expired_global_version', $version, 86400); // 1 day
        $result['version'] = $version;
        
        cache()->save($cacheKey, $result, 300); // 5 minutes cache

        return $result;
    }

    /**
     * Mark a notification as read for a user
     */
    public function markAsRead($userId, $batchId, $priority)
    {
        $data = [
            'user_id'  => $userId,
            'batch_id' => $batchId,
            'priority' => $priority,
            'read_at'  => date('Y-m-d H:i:s')
        ];
        
        // Use IGNORE in case it's already there
        $builder = $this->db->table('notification_reads');
        $exists = $builder->where(['user_id' => $userId, 'batch_id' => $batchId, 'priority' => $priority])->countAllResults();
        
        if ($exists == 0) {
            $builder->insert($data);
            $this->invalidateCache();
        }
    }

    /**
     * Invalidate cache to force recalculation
     */
    public function invalidateCache()
    {
        cache()->save('expired_global_version', time(), 86400);
    }
    
    /**
     * Get Dashboard Statistics
     */
    public function getDashboardStats()
    {
        $cacheKey = 'expired_dashboard_stats_' . date('Ymd');
        if ($cached = cache($cacheKey)) {
            $globalVersion = cache('expired_global_version');
            if (isset($cached['version']) && $cached['version'] === $globalVersion) {
                return $cached['data'];
            }
        }

        $builder = $this->db->table('batch');
        $builder->select('tanggal_kedaluwarsa');
        $builder->where('stok_saat_ini >', 0);
        $batches = $builder->get()->getResultArray();
        
        $today = new \DateTime(date('Y-m-d'));
        
        $stats = [
            'CRITICAL' => 0,
            'HIGH'     => 0,
            'WARNING'  => 0,
            'INFO'     => 0,
            'AMAN'     => 0
        ];
        
        foreach ($batches as $batch) {
            $expDate = new \DateTime($batch['tanggal_kedaluwarsa']);
            $diff = (int) $today->diff($expDate)->format('%R%a');
            
            $priority = $this->getPriority($diff);
            $stats[$priority]++;
        }

        $result = [
            'data' => $stats,
            'version' => cache('expired_global_version') ?: time()
        ];
        
        cache()->save('expired_global_version', $result['version'], 86400);
        cache()->save($cacheKey, $result, 300);
        
        return $stats;
    }
}
