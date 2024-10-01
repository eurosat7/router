<?php

declare(strict_types=1);

namespace app\Routing;

use HttpInvalidParamException;
use RuntimeException;

class RouteFinder
{
    public function __construct(
        public string $viewPath,
        public array $aliases,
        public string $view404 = '404.php'
    ) {}

    /**
     * @throws HttpInvalidParamException
     * @throws RuntimeException
     *
     * supported uri:
     * -  => index.php
     * - user => user/index.php
     * - user/view => user/view.php
     * - user/view/12 => user/view.php $args=[12]
     * - user/view/12/dance/to/the/beat => user/view.php $args=[12,dance,to,the,beat]
     * - user/edit => user/edit.php
     * - user/edit/12 => user/edit.php $args=[12]
     * - user/edit/dance/to/the/beat => user/edit.php $args=[dance,to,the,beat]
     * - other => 404.php
     * */
    public function getParsedRoute(
        ?array $server = null,
        ?array $get = null,
        ?array $post = null,
        ?array $files = null,
    ): ParsedRoute {
        $server ??= $_SERVER;
        $request = [
            'get' => $get ?? $_GET ?? [],
            'post' => $post ?? $_POST ?? [],
            'files' => $files ?? $_FILES ?? [],
        ];

        $requestUri = $server['REQUEST_URI'] ?? '';
        $requestUri = current(explode('?', $requestUri)); // strip QueryString, available as $_GET
        $requestUri = trim($requestUri, '/'); // remove any unused "/"
        $controller = $this->aliases[$requestUri] ?? $requestUri; // apply aliases
        if (str_contains($controller, '..')) {
            throw new HttpInvalidParamException('invalid uri, no traversing'); // protect against traversing up
        }

        if ($requestUri !== '') {
            $requestPath = explode('/', $requestUri);
        } else {
            $requestPath = [];
        }
        $requestPath[] = 'index'; // overview, list, home, dashboard, ...

        $args = [];
        $path = $requestPath;
        $controllerPath = 'undefined';
        while ($path !== []) { // try to find a file as deep in the file tree as possible
            $filePath = implode('/', $path);
            $controllerPath = $this->viewPath . $filePath . '.php';
            if (file_exists($controllerPath)) {
                // happy end
                return new ParsedRoute(
                    path: $controllerPath,
                    args: $args,
                    request: $request,
                );
            }
            $args[] = array_pop($path);
        }

        $controllerPath = $this->viewPath . $this->view404;
        if (file_exists($controllerPath)) {
            // controlled end
            return new ParsedRoute(
                path: $controllerPath,
                args: $args,
                request: $request,
            );
        }

        // uncontrolled end:
        echo "<!-- failed to find $requestUri -->\n";
        echo '<!-- _SERVER: ';
        var_dump($_SERVER);
        echo " -->\n";
        throw new RuntimeException('Bad config error');
    }
}
