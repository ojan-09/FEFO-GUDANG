<?php
namespace App\Controllers;
use App\Controllers\BaseController;
class Test extends BaseController {
    public function index() {
        $request = \Config\Services::request();
        $_POST["username"] = "Administrator";
        $_POST["role"] = "Pimpinan";

        $controller = new \App\Modules\Settings\Controllers\ManajemenUser();
        $controller->initController($request, \Config\Services::response(), \Config\Services::logger());

        // Call update for user 2
        $controller->update(2);

        $session = \Config\Services::session();
        var_dump($session->getFlashdata());
        
        $db = \Config\Database::connect();
        $res = $db->query("SELECT * FROM auth_groups_users WHERE user_id = 2")->getResultArray();
        print_r($res);
    }
}

