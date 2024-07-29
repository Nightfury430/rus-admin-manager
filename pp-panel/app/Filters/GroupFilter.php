<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class GroupFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (empty($arguments)) {
            return;
        }

        if (!auth()->loggedIn()) {
            $loginRoute = service("globalstate")->get("loginRoute");

            if (uri_string() !== route_to($loginRoute)) {
                $session = session();
                $session->setTempdata('beforeLoginUrl', current_url(), 300);
            }

            return response_error(403);
        }

        if (auth()->user()->inGroup(...$arguments)) {
            return;
        }

        return response_error(403);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
