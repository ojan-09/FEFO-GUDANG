<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RBACFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticate = service('authentication');
        $authorize    = service('authorization');

        if (! $authenticate->check()) {
            session()->set('redirect_url', current_url());
            return redirect('login');
        }

        if (empty($arguments)) {
            return;
        }

        foreach ($arguments as $group) {
            if ($authorize->inGroup($group, $authenticate->id())) {
                return; // User is in one of the allowed groups
            }
        }

        // If not in allowed groups, show 403 Forbidden
        $response = service('response');
        $response->setStatusCode(403);
        $response->setBody(view('errors/html/error_403'));
        return $response;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}

