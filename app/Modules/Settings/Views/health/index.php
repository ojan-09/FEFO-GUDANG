<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --h-bg: #F8FAFC; --h-card: #FFFFFF; --h-border: #E2E8F0;
        --h-primary: #0F172A; --h-primary-soft: #F1F5F9;
        --h-success: #16A34A; --h-danger: #DC2626;
        --h-text: #1E293B; --h-text-soft: #64748B;
    }
    .h-page { background: var(--h-bg); margin: -1.5rem -1.5rem 0 -1.5rem; padding: 24px; min-height: 100vh; }
    
    .h-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 24px; }
    
    .h-card { background: var(--h-card); border: 1px solid var(--h-border); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .h-card-header { padding: 16px 20px; border-bottom: 1px solid var(--h-border); background: var(--h-primary-soft); font-weight: 700; color: var(--h-primary); display: flex; align-items: center; gap: 8px; font-size: 1.05rem; }
    .h-card-body { padding: 0; }
    
    .h-list { margin: 0; padding: 0; list-style: none; }
    .h-list li { display: flex; justify-content: space-between; padding: 12px 20px; border-bottom: 1px solid var(--h-border); font-size: 0.9rem; }
    .h-list li:last-child { border-bottom: none; }
    .h-list-label { color: var(--h-text-soft); font-weight: 600; }
    .h-list-value { color: var(--h-text); font-weight: 700; text-align: right; }
    
    .h-status-ok { color: var(--h-success); display: inline-flex; align-items: center; gap: 4px; }
    .h-status-fail { color: var(--h-danger); display: inline-flex; align-items: center; gap: 4px; }
</style>

<div class="h-page">
    <div class="mb-4">
        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--h-primary); margin:0;">System Health Check</h1>
        <p style="color: var(--h-text-soft); margin:0;">Real-time telemetry & environment analytics.</p>
    </div>

    <div class="h-grid">
        
        <!-- Application Info -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-layer-group"></i> Application Information</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">Name</span> <span class="h-list-value">FEFO Gudang</span></li>
                    <li><span class="h-list-label">Version</span> <span class="h-list-value"><?= esc($serverInfo['app_version']) ?></span></li>
                    <li><span class="h-list-label">Build Date</span> <span class="h-list-value"><?= esc($serverInfo['build']) ?></span></li>
                    <li><span class="h-list-label">Developer</span> <span class="h-list-value"><?= esc($serverInfo['developer']) ?></span></li>
                    <li><span class="h-list-label">Environment</span> <span class="h-list-value"><?= strtoupper(esc($serverInfo['environment'])) ?></span></li>
                </ul>
            </div>
        </div>

        <!-- Server Info -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-server"></i> Server Information</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">OS</span> <span class="h-list-value"><?= esc($serverInfo['os']) ?></span></li>
                    <li><span class="h-list-label">Web Server</span> <span class="h-list-value"><?= esc($serverInfo['server']) ?></span></li>
                    <li><span class="h-list-label">PHP Version</span> <span class="h-list-value"><?= esc($serverInfo['php_version']) ?></span></li>
                    <li><span class="h-list-label">CodeIgniter</span> <span class="h-list-value"><?= esc($serverInfo['ci_version']) ?></span></li>
                    <li><span class="h-list-label">Timezone</span> <span class="h-list-value"><?= esc($serverInfo['timezone']) ?></span></li>
                </ul>
            </div>
        </div>

        <!-- Performance Info -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-gauge-high"></i> System Performance</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">Response Time</span> <span class="h-list-value"><?= esc($performanceInfo['response_time']) ?></span></li>
                    <li><span class="h-list-label">Memory Usage</span> <span class="h-list-value"><?= esc($performanceInfo['memory_usage']) ?></span></li>
                    <li><span class="h-list-label">Peak Memory</span> <span class="h-list-value"><?= esc($performanceInfo['peak_memory']) ?></span></li>
                    <li><span class="h-list-label">Max Execution</span> <span class="h-list-value"><?= esc($limitsInfo['execution_time']) ?></span></li>
                </ul>
            </div>
        </div>

        <!-- Limits & Storage -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-hard-drive"></i> Resource Limits & Storage</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">Memory Limit</span> <span class="h-list-value"><?= esc($limitsInfo['memory_limit']) ?></span></li>
                    <li><span class="h-list-label">Upload Max</span> <span class="h-list-value"><?= esc($limitsInfo['upload_max']) ?></span></li>
                    <li><span class="h-list-label">Post Max</span> <span class="h-list-value"><?= esc($limitsInfo['post_max']) ?></span></li>
                    <li><span class="h-list-label">Disk Storage</span> 
                        <span class="h-list-value">
                            Used: <?= esc($limitsInfo['disk_used']) ?> <br>
                            <span style="font-size:0.8rem; color:var(--h-text-soft)">Free: <?= esc($limitsInfo['disk_free']) ?> / <?= esc($limitsInfo['disk_total']) ?></span>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Folders Check -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-regular fa-folder-open"></i> Writable Folders Status</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <?php foreach($folderStatus as $folder => $status): ?>
                    <li>
                        <span class="h-list-label"><?= esc($folder) ?>/</span> 
                        <span class="h-list-value">
                            <?php if($status): ?>
                                <span class="h-status-ok"><i class="fa-solid fa-circle-check"></i> Writable</span>
                            <?php else: ?>
                                <span class="h-status-fail"><i class="fa-solid fa-circle-xmark"></i> Not Writable</span>
                            <?php endif; ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Database Health -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-database"></i> Database Health</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">Status</span> 
                        <span class="h-list-value">
                            <?php if($dbHealth['status'] === 'Connected'): ?>
                                <span class="h-status-ok"><i class="fa-solid fa-plug-circle-check"></i> Connected</span>
                            <?php else: ?>
                                <span class="h-status-fail"><i class="fa-solid fa-plug-circle-xmark"></i> <?= esc($dbHealth['status']) ?></span>
                            <?php endif; ?>
                        </span>
                    </li>
                    <li><span class="h-list-label">MySQL Version</span> <span class="h-list-value"><?= esc($dbHealth['version']) ?></span></li>
                    <li><span class="h-list-label">Tables Count</span> <span class="h-list-value"><?= esc($dbHealth['tables']) ?></span></li>
                    <li><span class="h-list-label">Records Count</span> <span class="h-list-value"><?= number_format($dbHealth['records']) ?></span></li>
                    <li><span class="h-list-label">Database Size</span> <span class="h-list-value"><?= esc($dbHealth['size_mb']) ?> MB</span></li>
                </ul>
            </div>
        </div>

        <!-- Cache Info -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-bolt"></i> Cache Information</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">Driver</span> <span class="h-list-value"><?= strtoupper(esc($cacheInfo['driver'])) ?></span></li>
                    <li><span class="h-list-label">Items Cached</span> <span class="h-list-value"><?= esc($cacheInfo['items_cached']) ?></span></li>
                    <li><span class="h-list-label">TTL Default</span> <span class="h-list-value"><?= esc($cacheInfo['ttl_default']) ?></span></li>
                </ul>
            </div>
        </div>

        <!-- Backup Info -->
        <div class="h-card">
            <div class="h-card-header"><i class="fa-solid fa-shield-halved"></i> Backup Information</div>
            <div class="h-card-body">
                <ul class="h-list">
                    <li><span class="h-list-label">Latest Backup</span> 
                        <span class="h-list-value">
                            <div style="font-size:0.85rem; word-break:break-all; max-width:200px;"><?= esc($backupInfo['latest']) ?></div>
                        </span>
                    </li>
                    <li><span class="h-list-label">Size</span> <span class="h-list-value"><?= esc($backupInfo['size']) ?></span></li>
                    <li><span class="h-list-label">Checksum</span> 
                        <span class="h-list-value">
                            <?php if($backupInfo['checksum'] === 'Valid'): ?>
                                <span class="h-status-ok"><i class="fa-solid fa-check"></i> Valid</span>
                            <?php elseif($backupInfo['checksum'] === 'No Checksum'): ?>
                                <span class="h-text-soft">No Checksum</span>
                            <?php else: ?>
                                <span class="h-status-fail"><i class="fa-solid fa-xmark"></i> Invalid</span>
                            <?php endif; ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>
