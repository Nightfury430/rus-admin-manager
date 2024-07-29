<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\URI;

class AuthSetupFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (!$request instanceof IncomingRequest) {
            return;
        }

        $authDBGroup = session("db_group");

        /** @var \Config\Auth $authConfig  */
        $authConfig = config("Auth");

        $isAdminRoute = $this->isAdminRoute($request->getUri());

        $globalState = service("globalstate");
        $globalState->set("isAdminRoute", $isAdminRoute);

        $this->setupAuthRoutes($isAdminRoute, $globalState);

        if (empty($authDBGroup) && $isAdminRoute) {
            /** @var \Config\Database */
            $dbConfig = config("Database");
            $authDBGroup = $dbConfig->adminPanelGroup;

            if (empty($authDBGroup)) {
                return redirect()->route($globalState->get("loginRoute"));
            }
        }

        $globalState->set("DBGroup", $authDBGroup);
        $authConfig->DBGroup = $authDBGroup;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function setupAuthRoutes(bool $isAdminRoute, $globalState)
    {
        $loginRoute = $isAdminRoute ? 'admin-panel/login' : 'client-panel/login';
        $globalState->set("loginRoute", $loginRoute);
    }

    private function isAdminRoute(URI $uri): bool
    {
        $apSegment = "admin-panel";

        try {
            if ($uri->getSegment(1) === $apSegment) {
                return true;
            } else if ($uri->getSegment(2) === $apSegment) {
                return true;
            }
        } catch (\Throwable) {
        }

        return false;
    }
}
