<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
  *, *::before, *::after { box-sizing: border-box; }

  .pr {
    font-family: 'Inter', system-ui, sans-serif;
    background: #F8FAFC;
    padding: 14px 18px 18px;
    color: #0F172A;
  }

  /* header */
  .pr-hdr { margin-bottom: 12px; }
  .pr-hdr h1 { font-size: 18px; font-weight: 700; letter-spacing: -.3px; margin: 0 0 1px; }
  .pr-hdr p  { font-size: 12px; color: #64748B; margin: 0; }

  /* alert */
  .pr-alert { border-radius: 8px; padding: 7px 12px; font-size: 12px; margin-bottom: 10px; border: 1px solid transparent; }
  .pr-alert.ok  { background:#F0FDF4; border-color:#BBF7D0; color:#15803D; }
  .pr-alert.err { background:#FFF1F2; border-color:#FECDD3; color:#BE123C; }
  .pr-alert ul  { margin:0; padding-left:14px; }

  /* grid */
  .pr-grid { display:grid; grid-template-columns:220px 1fr; gap:14px; align-items:start; }
  @media(max-width:640px){ .pr-grid { grid-template-columns:1fr; } }

  /* card */
  .pr-card {
    background:#fff; border:1px solid #E5E7EB; border-radius:14px;
    box-shadow:0 2px 10px rgba(15,23,42,.05);
    transition:transform 200ms ease,box-shadow 200ms ease;
  }
  .pr-card:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(15,23,42,.08); }

  /* identity */
  .id-card { display:flex; flex-direction:column; align-items:center; text-align:center; gap:6px; padding:20px 14px 16px; }
  .id-avatar {
    width:60px; height:60px; border-radius:50%;
    background:linear-gradient(135deg,#3B82F6,#6366F1);
    display:flex; align-items:center; justify-content:center; color:#fff; margin-bottom:4px;
  }
  .id-name   { font-size:14px; font-weight:700; color:#0F172A; margin:0; }
  .id-badge  {
    display:inline-flex; align-items:center; gap:3px;
    background:#EFF6FF; color:#2563EB; border:1px solid #BFDBFE;
    border-radius:20px; font-size:10.5px; font-weight:600; padding:2px 9px;
  }
  .id-status { display:inline-flex; align-items:center; gap:4px; font-size:11.5px; color:#16A34A; font-weight:500; }
  .id-dot    { width:6px; height:6px; border-radius:50%; background:#22C55E; box-shadow:0 0 0 2px rgba(34,197,94,.2); }
  .id-hr     { width:100%; border:none; border-top:1px solid #E5E7EB; margin:2px 0; }
  .id-meta   { width:100%; font-size:11px; color:#94A3B8; line-height:1.5; word-break:break-all; }
  .id-meta strong { display:block; font-size:9.5px; font-weight:600; text-transform:uppercase; letter-spacing:.5px; color:#CBD5E1; margin-bottom:1px; }

  /* form card */
  .fc { padding:16px 18px 14px; }

  /* two-col field layout inside form */
  .fc-cols { display:grid; grid-template-columns:1fr 1fr; gap:10px 16px; }
  .fc-full  { grid-column: 1 / -1; }

  .sec-ttl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.7px; color:#94A3B8; margin:0 0 10px; }
  .fg   { display:flex; flex-direction:column; gap:8px; margin-bottom:14px; }
  .frow { display:flex; flex-direction:column; gap:3px; }
  .flbl { font-size:11.5px; font-weight:600; color:#374151; display:flex; align-items:center; gap:4px; }
  .flbl svg { width:12px; height:12px; color:#9CA3AF; flex-shrink:0; }

  .iw { position:relative; }
  .iw .ii { position:absolute; left:10px; top:50%; transform:translateY(-50%); width:13px; height:13px; color:#9CA3AF; pointer-events:none; }
  .iw .eye { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; padding:0; cursor:pointer; color:#9CA3AF; display:flex; align-items:center; }
  .iw .eye:hover { color:#6366F1; }

  .pi {
    width:100%; height:34px; border:1px solid #E5E7EB; border-radius:8px;
    padding:0 10px 0 30px; font-size:13px; font-family:'Inter',system-ui,sans-serif;
    color:#0F172A; background:#fff; outline:none;
    transition:border-color .2s,box-shadow .2s;
  }
  .pi:focus        { border-color:#6366F1; box-shadow:0 0 0 2px rgba(99,102,241,.1); }
  .pi[readonly]    { background:#F8FAFC; color:#64748B; cursor:default; }
  .pi[readonly]:focus { border-color:#E5E7EB; box-shadow:none; }
  .has-eye         { padding-right:30px; }
  .fhint           { font-size:10.5px; color:#94A3B8; margin:0; }

  .sec-sep { border:none; border-top:1px solid #E5E7EB; margin:0 0 12px; }

  /* password row side-by-side */
  .pw-row { display:grid; grid-template-columns:1fr 1fr; gap:10px 16px; margin-bottom:12px; }

  /* submit */
  .sub-row { display:flex; justify-content:flex-end; }
  .btn-sv {
    display:inline-flex; align-items:center; gap:6px;
    height:34px; padding:0 18px; min-width:148px; justify-content:center;
    background:#6366F1; color:#fff; border:none; border-radius:8px;
    font-size:12.5px; font-weight:600; font-family:'Inter',system-ui,sans-serif;
    cursor:pointer; transition:background .2s,transform .2s,box-shadow .2s;
    box-shadow:0 2px 6px rgba(99,102,241,.3);
  }
  .btn-sv:hover  { background:#4F46E5; transform:translateY(-1px); box-shadow:0 4px 12px rgba(99,102,241,.35); }
  .btn-sv:active { transform:translateY(0); }
  .btn-sv svg    { width:13px; height:13px; flex-shrink:0; }
</style>

<div class="pr">

  <div class="pr-hdr">
    <h1>Profil Pengguna</h1>
    <p>Kelola informasi akun dan keamanan Anda</p>
  </div>

  <?php if (session('success')) : ?>
    <div class="pr-alert ok"><?= session('success') ?></div>
  <?php endif ?>
  <?php if (session('errors')) : ?>
    <div class="pr-alert err"><ul><?php foreach (session('errors') as $e) : ?><li><?= esc($e) ?></li><?php endforeach ?></ul></div>
  <?php endif ?>

  <div class="pr-grid">

    <!-- LEFT: identity -->
    <div class="pr-card id-card">
      <div class="id-avatar">
        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      </div>
      <p class="id-name"><?= esc($user->username) ?></p>
      <span class="id-badge">
        <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        <?= esc(get_user_role()) ?>
      </span>
      <span class="id-status"><span class="id-dot"></span>Akun Aktif</span>
      <hr class="id-hr">
      <div class="id-meta"><strong>Email</strong><?= esc($user->email) ?></div>
      <div class="id-meta mt-2"><strong>Divisi / Department</strong><?= esc($user->divisi ?? '-') ?></div>
    </div>

    <!-- RIGHT: form -->
    <div class="pr-card fc">
      <form action="<?= site_url('profil/update') ?>" method="post" class="loading-form" data-overlay="true">
        <?= csrf_field() ?>

        <p class="sec-ttl">Informasi Akun</p>

        <!-- 3 fields in 2-col grid: email full-width, nama+role side by side -->
        <div class="fg">
          <div class="frow">
            <label class="flbl">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              Email
            </label>
            <div class="iw">
              <svg class="ii" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
              <input type="email" class="pi" value="<?= esc($user->email) ?>" readonly>
            </div>
            <p class="fhint">Email digunakan untuk login dan tidak dapat diubah.</p>
          </div>

          <!-- nama, divisi + role grid -->
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div class="frow">
              <label class="flbl" for="fu">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                Nama Lengkap
              </label>
              <div class="iw">
                <svg class="ii" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <input id="fu" type="text" name="username" class="pi" value="<?= old('username', $user->username) ?>" required>
              </div>
            </div>
            <div class="frow">
              <label class="flbl" for="fdiv">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Divisi / Department
              </label>
              <div class="iw">
                <svg class="ii" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                <input id="fdiv" type="text" name="divisi" class="pi" value="<?= old('divisi', $user->divisi ?? '') ?>" placeholder="Misal: Gudang / Logistik / Program">
              </div>
            </div>
            <div class="frow" style="grid-column: 1 / -1;">
              <label class="flbl">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                Role (Hak Akses)
              </label>
              <div class="iw">
                <svg class="ii" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <input type="text" class="pi" value="<?= esc(get_user_role()) ?>" readonly>
              </div>
            </div>
          </div>
        </div>

        <hr class="sec-sep">
        <p class="sec-ttl">Ubah Password</p>

        <!-- password side by side -->
        <div class="pw-row">
          <div class="frow">
            <label class="flbl" for="fp">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              Password Baru
            </label>
            <div class="iw">
              <svg class="ii" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input id="fp" type="password" name="password" class="pi has-eye" placeholder="Kosongkan jika tidak diubah">
              <button type="button" class="eye" onclick="tpwd('fp',this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
          <div class="frow">
            <label class="flbl" for="fc2">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              Konfirmasi Password
            </label>
            <div class="iw">
              <svg class="ii" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              <input id="fc2" type="password" name="pass_confirm" class="pi has-eye" placeholder="Ulangi password baru">
              <button type="button" class="eye" onclick="tpwd('fc2',this)">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
        </div>

        <div class="sub-row">
          <button type="submit" class="btn-sv">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Simpan Perubahan
          </button>
        </div>

      </form>
    </div>

  </div>
</div>

<script>
var EYE='<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
var EYEOFF='<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
function tpwd(id,btn){var i=document.getElementById(id);var h=i.type==='password';i.type=h?'text':'password';btn.innerHTML=h?EYEOFF:EYE;}
</script>

<?= $this->endSection() ?>z