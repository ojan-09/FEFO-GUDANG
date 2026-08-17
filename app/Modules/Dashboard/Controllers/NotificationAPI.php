<?php

namespace App\Modules\Dashboard\Controllers;

use App\Controllers\BaseController;
use App\Services\ExpiredNotificationService;

class NotificationAPI extends BaseController
{
    public function getBellData()
    {
        // Only allow Administrator or Petugas Gudang (Internal)
        if (!in_groups(['Administrator', 'Petugas Gudang'])) {
            return $this->response->setJSON(['count' => 0, 'items' => []]);
        }
        
        $userId = user()->id;
        $service = new ExpiredNotificationService();
        $data = $service->getNotificationsForUser($userId);
        
        return $this->response->setJSON($data);
    }

    public function markAsRead()
    {
        $batchId = $this->request->getPost('batch_id');
        $priority = $this->request->getPost('priority');
        
        if (!$batchId || !$priority) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid data']);
        }

        $userId = user()->id;
        $service = new ExpiredNotificationService();
        $service->markAsRead($userId, $batchId, $priority);

        // Activity log
        log_activity('Notification Dismiss', "User mematikan notifikasi $priority untuk batch ID $batchId");

        return $this->response->setJSON(['status' => 'success']);
    }
}
