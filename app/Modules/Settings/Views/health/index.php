<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

:root {
    --bg:          #F8FAFC;
    --surface:     #FFFFFF;
    --border:      #E5E7EB;
    --text:        #111827;
    --text-soft:   #6B7280;
    --text-subtle: #9CA3AF;
    --primary:     #2563EB;
    --primary-bg:  #EFF6FF;
    --indigo:      #6366F1;
    --indigo-bg:   #EEF2FF;
    --success:     #16A34A;
    --success-bg:  #ECFDF5;
    --warning:     #D97706;
    --warning-bg:  #FFFBEB;
    --danger:      #DC2626;
    --danger-bg:   #FEF2F2;
    --muted-bg:    #F3F4F6;
    --shadow:      0 1px 3px rgba(0,0,0,.05), 0 4px 12px rgba(0,0,0,.04);
    --radius:      16px;
    --radius-lg:   20px;
    --radius-sm:   10px;
}

.sh {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: var(--bg);
    margin: -1.5rem -1.5rem 0;
    padding: 20px 24px 48px;
    -webkit-font-smoothing: antialiased;
    min-height: 100vh;
}

/* ── Header ──────────────────────────────────────────────── */
.sh-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 20px;
}
.sh-header-left h1 {
    font-size: 22px;
    font-weight: 700;
    color: var(--text);
    margin: 0 0 4px;
    letter-spacing: -0.3px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.sh-header-left h1 i { color: var(--indigo); font-size: 19px; }
.sh-header-left p    { font-size: 13px; color: var(--text-soft); margin: 0; }

.sh-header-right { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }

.sh-live-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 999px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 500;
    color: var(--text-soft);
    box-shadow: var(--shadow);
}
.sh-live-dot {
    width: 7px; height: 7px;
    background: #22C55E;
    border-radius: 50%;
    animation: livepulse 1.8s infinite;
}
@keyframes livepulse { 0%,100%{opacity:1} 50%{opacity:.3} }

.sh-btn-refresh {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    border-radius: var(--radius-sm);
    font-size: 13px;
    font-weight: 600;
    font-family: 'Inter', sans-serif;
    background: var(--primary);
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: background .13s, transform .12s, box-shadow .13s;
}
.sh-btn-refresh:hover {
    background: #1D4ED8;
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37,99,235,.25);
}
.sh-btn-refresh:active { transform: scale(.98); }

/* ── KPI Strip ───────────────────────────────────────────── */
.sh-kpi-strip {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
.sh-kpi {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow);
}
.sh-kpi-icon {
    width: 38px; height: 38px;
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; flex-shrink: 0;
}
.sh-kpi-icon.green  { background: var(--success-bg); color: var(--success); }
.sh-kpi-icon.blue   { background: var(--primary-bg); color: var(--primary); }
.sh-kpi-icon.indigo { background: var(--indigo-bg);  color: var(--indigo); }

.sh-kpi-lbl { font-size: 11px; font-weight: 600; color: var(--text-subtle); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 3px; }
.sh-kpi-val { font-size: 14px; font-weight: 700; color: var(--text); letter-spacing: -.2px; line-height: 1.2; }
.sh-kpi-val.green  { color: var(--success); }
.sh-kpi-val.indigo { color: var(--indigo); }

/* ── Section title ───────────────────────────────────────── */
.sh-section-title {
    font-size: 13px;
    font-weight: 600;
    color: var(--text);
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 12px;
}
.sh-section-title i { color: var(--indigo); font-size: 14px; }

/* ── Health Cards Grid ───────────────────────────────────── */
.sh-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 12px;
}

.sh-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 18px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    gap: 14px;
    box-shadow: var(--shadow);
    transition: box-shadow .18s, transform .18s, border-color .18s;
}
.sh-card:hover {
    box-shadow: 0 4px 18px rgba(0,0,0,.08);
    transform: translateY(-2px);
    border-color: #D1D5DB;
}

.sh-card-top {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 10px;
}
.sh-card-key {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-subtle);
    text-transform: uppercase;
    letter-spacing: .5px;
    flex: 1;
}
.sh-card-icon {
    width: 30px; height: 30px;
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; flex-shrink: 0;
}
.sh-card-icon.success { background: var(--success-bg); color: var(--success); }
.sh-card-icon.warning { background: var(--warning-bg); color: var(--warning); }
.sh-card-icon.danger  { background: var(--danger-bg);  color: var(--danger); }
.sh-card-icon.info    { background: var(--indigo-bg);  color: var(--indigo); }
.sh-card-icon.primary { background: var(--primary-bg); color: var(--primary); }

.sh-card-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--text);
    line-height: 1.45;
    word-break: break-word;
    font-variant-numeric: tabular-nums;
}

/* ── Status badge ────────────────────────────────────────── */
.sh-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    width: fit-content;
}
.sh-badge i { font-size: 10px; }
.sh-badge.success { background: var(--success-bg); color: #15803D; border: 1px solid #A7F3D0; }
.sh-badge.warning { background: var(--warning-bg); color: var(--warning); border: 1px solid #FDE68A; }
.sh-badge.danger  { background: var(--danger-bg);  color: var(--danger);  border: 1px solid #FECACA; }
.sh-badge.info    { background: var(--indigo-bg);  color: var(--indigo);  border: 1px solid #C7D2FE; }
.sh-badge.primary { background: var(--primary-bg); color: var(--primary); border: 1px solid #BFDBFE; }

/* ── Responsive ──────────────────────────────────────────── */
@media (max-width: 900px) {
    .sh-kpi-strip { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
    .sh { padding: 12px 14px 40px; }
    .sh-header-left h1 { font-size: 17px; }
    .sh-kpi-strip { grid-template-columns: 1fr; }
    .sh-grid { grid-template-columns: 1fr; }
}
</style>

<div class="sh">

    <!-- ── Header ──────────────────────────────────────────── -->
    <div class="sh-header">
        <div class="sh-header-left">
            <h1><i class="fa-solid fa-heart-pulse"></i> System Health Check</h1>
            <p>Monitoring performa, keamanan backup, dan stabilitas server gudang secara real-time</p>
        </div>
        <div class="sh-header-right">
            <div class="sh-live-pill">
                <span class="sh-live-dot"></span> Live Monitor
            </div>
            <button class="sh-btn-refresh" onclick="window.location.reload()">
                <i class="fa-solid fa-rotate-right"></i> Refresh
            </button>
        </div>
    </div>

    <!-- ── KPI Strip ───────────────────────────────────────── -->
    <div class="sh-kpi-strip">
        <div class="sh-kpi">
            <div class="sh-kpi-icon green"><i class="fa-solid fa-shield-halved"></i></div>
            <div>
                <div class="sh-kpi-lbl">System Status</div>
                <div class="sh-kpi-val green">100% Operational</div>
            </div>
        </div>
        <div class="sh-kpi">
            <div class="sh-kpi-icon blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
            <div>
                <div class="sh-kpi-lbl">Backup Terakhir</div>
                <div class="sh-kpi-val">
                    <?= esc($health['Backup Terakhir']['value'] ?? 'Belum Ada') ?>
                </div>
            </div>
        </div>
        <div class="sh-kpi">
            <div class="sh-kpi-icon indigo"><i class="fa-solid fa-database"></i></div>
            <div>
                <div class="sh-kpi-lbl">Koneksi Database</div>
                <div class="sh-kpi-val indigo">Connected (OK)</div>
            </div>
        </div>
    </div>

    <!-- ── Diagnostic Grid ─────────────────────────────────── -->
    <div class="sh-section-title">
        <i class="fa-solid fa-server"></i> Detail Indikator Health
    </div>

    <div class="sh-grid">
        <?php
        $icons = [
            'success' => 'fa-circle-check',
            'warning' => 'fa-triangle-exclamation',
            'danger'  => 'fa-circle-xmark',
            'info'    => 'fa-circle-info',
            'primary' => 'fa-bolt',
        ];
        foreach ($health as $key => $data):
            $st   = esc($data['status']);
            $icon = $icons[$st] ?? 'fa-circle-info';
        ?>
        <div class="sh-card">
            <div>
                <div class="sh-card-top">
                    <div class="sh-card-key"><?= esc($key) ?></div>
                    <div class="sh-card-icon <?= $st ?>">
                        <i class="fa-solid <?= $icon ?>"></i>
                    </div>
                </div>
                <div class="sh-card-value"><?= esc($data['value']) ?></div>
            </div>
            <span class="sh-badge <?= $st ?>">
                <i class="fa-solid <?= $icon ?>"></i>
                <?= strtoupper($st) ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<?= $this->endSection() ?>