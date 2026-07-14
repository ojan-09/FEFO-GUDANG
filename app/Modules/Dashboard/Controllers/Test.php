<?php
namespace App\Modules\Dashboard\Controllers;
use App\Controllers\BaseController;
class Test extends BaseController {
    public function index() {
        $request = \Config\Services::request();
        $request = $request->withMethod("post");
        $request->setGlobal("post", [
            "username" => "Administrator",
            "role" => "Pimpinan"
        ]);

        $controller = new \App\Modules\Settings\Controllers\ManajemenUser();
        $controller->initController($request, \Config\Services::response(), \Config\Services::logger());

        // Call update for user 1
        $response = $controller->update(1);

        // print session flashes
        $session = \Config\Services::session();
        print_r($session->getFlashdata());
    }
}

