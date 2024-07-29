<?php

namespace App\Libraries;

use CodeIgniter\CodeIgniter as BaseCodeIgniter;
// use Closure;
// use CodeIgniter\Cache\ResponseCache;
// use CodeIgniter\Debug\Timer;
// use CodeIgniter\Events\Events;
// use CodeIgniter\Exceptions\FrameworkException;
// use CodeIgniter\Exceptions\PageNotFoundException;
// use CodeIgniter\Filters\Filters;
// use CodeIgniter\HTTP\CLIRequest;
// use CodeIgniter\HTTP\DownloadResponse;
// use CodeIgniter\HTTP\Exceptions\RedirectException;
// use CodeIgniter\HTTP\IncomingRequest;
// use CodeIgniter\HTTP\Method;
// use CodeIgniter\HTTP\RedirectResponse;
// use CodeIgniter\HTTP\Request;
// use CodeIgniter\HTTP\ResponsableInterface;
use CodeIgniter\HTTP\ResponseInterface;
// use CodeIgniter\HTTP\URI;
// use CodeIgniter\Router\Exceptions\RedirectException as DeprecatedRedirectException;
// use CodeIgniter\Router\RouteCollectionInterface;
// use CodeIgniter\Router\Router;
// use Config\App;
use Config\Cache;
// use Config\Feature;
// use Config\Kint as KintConfig;
// use Config\Services;
// use Exception;
// use Kint;
// use Kint\Renderer\CliRenderer;
// use Kint\Renderer\RichRenderer;
// use Locale;
// use LogicException;
// use Throwable;

class CodeIgniter extends BaseCodeIgniter
{
    public function displayCache(Cache $config)
    {
        $cachedResponse = $this->pageCache->get($this->request, $this->response);
        if ($cachedResponse instanceof ResponseInterface) {
            $this->response = $cachedResponse;

            // $this->totalTime = $this->benchmark->getElapsedTime('total_execution');
            // $output          = $this->displayPerformanceMetrics($cachedResponse->getBody());
            // $this->response->setBody($output);

            return $this->response;
        }

        return false;
    }

    public function displayPerformanceMetrics(string $output): string
    {
        // return str_replace(
        //     ['{elapsed_time}', '{memory_usage}'],
        //     [(string) $this->totalTime, number_format(memory_get_peak_usage() / 1024 / 1024, 3)],
        //     $output
        // );
        return $output;
    }

    public function spoofRequestMethod()
    {
        // // Only works with POSTED forms
        // if ($this->request->getMethod() !== Method::POST) {
        //     return;
        // }

        // $method = $this->request->getPost('_method');

        // if ($method === null) {
        //     return;
        // }

        // // Only allows PUT, PATCH, DELETE
        // if (in_array($method, [Method::PUT, Method::PATCH, Method::DELETE], true)) {
        //     $this->request = $this->request->setMethod($method);
        // }
    }
}
