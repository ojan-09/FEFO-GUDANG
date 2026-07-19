<?php
require 'public/index.php';
$db = \Config\Database::connect();
$user = $db->table('users')->select('id')->first();
if ($user) {
    $db->table('penyesuaian_stok')->where('id_user IS NULL')->update(['id_user' => $user->id]);
    echo "Fixed!";
}
