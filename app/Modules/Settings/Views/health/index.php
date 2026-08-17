<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

.sh-container {
    max-width: 1500px;
    margin: 0 auto;
    padding: 24px 28px 48px;
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    color: #0F172A;
}

/* ── HEADER HERO CARD ── */
.sh-hero {
    background: linear-gradient(135deg, #0F172A 0%, #1E293B 100%);
    border-radius: 20px;
    padding: 28px 32px;
    color: #fff;
    margin-bottom: 24px;
    box-shadow: 0 12px 32px rgba(15, 23, 42, 0.15);
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    position: relative;
    overflow: hidden;
}
.sh-hero::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 350px;
    height: 350px;
    background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
    pointer-events: none;
}
.sh-hero-title-box { display: flex; align-items: center; gap: 16px; }
.sh-hero-icon {
    width: 52px; height: 52px;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; color: #818CF8;
    backdrop-filter: blur(10px);
}
.sh-badge-live {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(34, 197, 94, 0.15);
    border: 1px solid rgba(34, 197, 94, 0.3);
    color: #4ADE80;
    padding: 4px 12px; border-radius: 99px;
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.8px;
    margin-bottom: 6px;
}
.sh-pulse-dot {
    width: 7px; height: 7px; background: #22C55E; border-radius: 50%;
    box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7);
    animation: shPulse 1.8s infinite;
}
@keyframes shPulse {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
}
.sh-hero-h1 { font-size: 22px; font-weight: 800; margin: 0; color: #fff; letter-spacing: -0.5px; }
.sh-hero-p { font-size: 13px; color: #94A3B8; margin: 4px 0 0 0; }

.sh-btn-refresh {
    display: inline-flex; align-items: center; gap: 8px;
    background: #2563EB; color: #fff;
    padding: 12px 20px; border-radius: 12px;
    font-size: 13px; font-weight: 700;
    border: none; cursor: pointer; text-decoration: none;
    box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
    transition: all 0.2s ease;
}
.sh-btn-refresh:hover {
    background: #1D4ED8; color: #fff; transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.45);
}

/* ── SUMMARY STATS BAR ── */
.sh-stats-bar {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.sh-stat-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 18px 22px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
}
.sh-stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.sh-stat-green { background: #ECFDF5; color: #059669; }
.sh-stat-blue  { background: #EFF6FF; color: #2563EB; }
.sh-stat-indigo{ background: #EEF2FF; color: #4F46E5; }
.sh-stat-amber { background: #FFFBEB; color: #D97706; }

.sh-stat-label { font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.6px; margin-bottom: 3px; }
.sh-stat-val   { font-size: 18px; font-weight: 800; color: #0F172A; line-height: 1.2; }

/* ── HEALTH GRID ── */
.sh-section-title {
    font-size: 15px; font-weight: 700; color: #334155;
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 16px;
}
.sh-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 16px;
}

.sh-card {
    background: #fff;
    border: 1px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    display: flex; flex-direction: column; justify-content: space-between;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.03);
    transition: all 0.2s ease;
    position: relative; overflow: hidden;
}
.sh-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
    border-color: #CBD5E1;
}

.sh-card-top { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px; }
.sh-card-key { font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; }
.sh-card-value {
    font-family: 'JetBrains Mono', monospace;
    font-size: 15px; font-weight: 700; color: #0F172A;
    line-height: 1.4; word-break: break-word; margin-bottom: 14px;
}

.sh-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 10px;
    font-size: 12px; font-weight: 700; width: fit-content;
}
.sh-badge-success { background: #ECFDF5; color: #047857; border: 1px solid #A7F3D0; }
.sh-badge-warning { background: #FFFBEB; color: #B45309; border: 1px solid #FDE68A; }
.sh-badge-danger  { background: #FFF1F2; color: #BE123C; border: 1px solid #FECDD3; }
.sh-badge-info    { background: #EEF2FF; color: #4338CA; border: 1px solid #C7D2FE; }
.sh-badge-primary { background: #EFF6FF; color: #1D4ED8; border: 1px solid #BFDBFE; }

.sh-card-icon {
    width: 32px; height: 32px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.sh-icon-success { background: #ECFDF5; color: #059669; }
.sh-icon-warning { background: #FFFBEB; color: #D97706; }
.sh-icon-danger  { background: #FFF1F2; color: #E11D48; }
.sh-icon-info    { background: #EEF2FF; color: #4F46E5; }
.sh-icon-primary { background: #EFF6FF; color: #2563EB; }
</style>

<div class="sh-container">

    <!-- HERO HEADER -->
    <div class="sh-hero">
        <div class="sh-hero-title-box">
            <div class="sh-hero-icon">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>
            <div>
                <div class="sh-badge-live">
                    <span class="sh-pulse-dot"></span> System Monitor Live
                </div>
                <h1 class="sh-hero-h1">System Health Check</h1>
                <p class="sh-hero-p">Monitoring performa, keamanan backup, dan stabilitas server gudang secara real-time.</p>
            </div>
        </div>
        <button class="sh-btn-refresh" onclick="window.location.reload()">
            <i class="fa-solid fa-rotate-right"></i> Refresh Diagnostic
        </button>
    </div>

    <!-- SUMMARY METRIC CARDS -->
    <div class="sh-stats-bar">
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-stat-green">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <div>
                <div class="sh-stat-label">System Health Status</div>
                <div class="sh-stat-val" style="color: #059669;">100% Operational</div>
            </div>
        </div>
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-stat-blue">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <div class="sh-stat-label">Backup Terakhir</div>
                <div class="sh-stat-val" style="font-size: 15px;">
                    <?php 
                        $bVal = $health['Backup Terakhir']['value'] ?? 'Belum Ada';
                        echo esc($bVal);
                    ?>
                </div>
            </div>
        </div>
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-stat-indigo">
                <i class="fa-solid fa-database"></i>
            </div>
            <div>
                <div class="sh-stat-label">Koneksi Database</div>
                <div class="sh-stat-val" style="color: #4F46E5;">Connected (OK)</div>
            </div>
        </div>
    </div>

    <!-- MAIN DIAGNOSTIC GRID -->
    <div class="sh-section-title">
        <i class="fa-solid fa-server" style="color: #2563EB;"></i> Detail Indikator System Health
    </div>

    <div class="sh-grid">
        <?php foreach($health as $key => $data): ?>
        <?php
            $st = esc($data['status']);
            $badgeClass = 'sh-badge-' . $st;
            $iconClass  = 'sh-icon-' . $st;

            $icons = [
                'success' => 'fa-circle-check',
                'warning' => 'fa-triangle-exclamation',
                'danger'  => 'fa-circle-xmark',
                'info'    => 'fa-circle-info',
                'primary' => 'fa-bolt',
            ];
            $icon = $icons[$st] ?? 'fa-circle-info';
        ?>
        <div class="sh-card">
            <div>
                <div class="sh-card-top">
                    <div class="sh-card-key"><?= esc($key) ?></div>
                    <div class="sh-card-icon <?= $iconClass ?>">
                        <i class="fa-solid <?= $icon ?>"></i>
                    </div>
                </div>
                <div class="sh-card-value"><?= esc($data['value']) ?></div>
            </div>
            <div>
                <span class="sh-badge <?= $badgeClass ?>">
                    <i class="fa-solid <?= $icon ?>" style="font-size:10px;"></i>
                    <?= strtoupper(esc($st)) ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?= $this->endSection() ?>