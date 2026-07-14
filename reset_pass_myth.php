<?php

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/system/bootstrap.php';

$app = \Config\Services::codeigniter();
$app->initialize();

use Myth\Auth\Models\UserModel;
use Myth\Auth\Password;

$userModel = new UserModel();

$users = $userModel->findAll();
$newPassword = 'foi12345';

foreach ($users as $user) {
    // We can use Password::hash directly
    $hashedPassword = Password::hash($newPassword);
    
    // Update raw password_hash using DB builder to avoid triggering Entity setters if we just want to force it
    $userModel->builder()->where('id', $user->id)->update(['password_hash' => $hashedPassword]);
    echo "Updated user {$user->email}\n";
}

echo "All passwords updated correctly via Myth Auth!\n";
