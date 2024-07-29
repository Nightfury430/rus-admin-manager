<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Shield\Authentication\Authenticators\Session;

class SessionAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!$request instanceof IncomingRequest) {
            return;
        }

        /** @var Session $authenticator */
        $authenticator = auth('session')->getAuthenticator();

        $loginRoute = service("globalstate")->get("loginRoute");

        if ($authenticator->loggedIn()) {
            if (setting('Auth.recordActiveDate')) {
                $authenticator->recordActiveDate();
            }

            $user = $authenticator->getUser();

            if ($user === null || !$user->active || !$user->client_active || !empty($user->deleted_at) || $user->isBanned()) {
                $authenticator->logout();
                return redirect()->route($loginRoute);
            }

            return;
        }

        if ($authenticator->isPending()) {
            $authenticator->logout();
            return redirect()->route($loginRoute);
        }

        if (uri_string() !== route_to($loginRoute)) {
            $session = session();
            $session->setTempdata('beforeLoginUrl', current_url(), 300);
        }

        return redirect()->route($loginRoute);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): void
    {
    }
}
