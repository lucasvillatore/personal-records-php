<?php

namespace App\Application;

class Router
{

    public function __construct(private readonly Container $container) {}

    private array $routes = [];

    public function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [$method, $this->compilePath($path), $handler];
    }

    public function dispatch(string $method, string $uri): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        $uriPath = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';
        $queryParams = $_GET;

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if (strtoupper($method) !== strtoupper($routeMethod)) {
                continue;
            }

            if (preg_match($pattern['regex'], $uriPath, $matches)) {
                $routeParams = [];
                foreach ($pattern['params'] as $name => $index) {
                    $routeParams[$name] = $matches[$index] ?? null;
                }

                $input = file_get_contents('php://input') ?: '';
                $body = json_decode($input, true);
                if (!is_array($body)) {
                    $body = [];
                }

                $request = new Request(
                    params: $routeParams,
                    query: $queryParams,
                    body: $body
                );

                try {
                    $responseData = $this->resolveAndCall($handler, [$request]);
                    echo json_encode($responseData);
                } catch (\Throwable $e) {
                    http_response_code(500);
                    echo json_encode([
                        'error' => 'Erro interno',
                        'message' => $e->getMessage()
                    ], JSON_UNESCAPED_UNICODE);
                }

                return;
            }
        }

        http_response_code(404);
        echo json_encode([
            'error' => '404 - Página não encontrada',
            'message' => 'O recurso solicitado não foi encontrado.'
        ], JSON_UNESCAPED_UNICODE);
    }

    private function resolveAndCall(callable|array $handler, array $params): mixed
    {
        if (is_array($handler) && is_string($handler[0])) {
            if (!$this->container) {
                throw new \RuntimeException("Container não fornecido para resolver dependência do controller.");
            }

            $instance = $this->container->get($handler[0]);
            return call_user_func_array([$instance, $handler[1]], $params);
        }

        return call_user_func_array($handler, $params);
    }

    private function compilePath(string $path): array
    {
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, rtrim($path, '/'));

        $regex = '@^' . $pattern . '$@D';

        preg_match_all('/\{(\w+)\}/', $path, $paramNames);
        $params = array_flip($paramNames[1]);

        return [
            'regex' => $regex,
            'params' => $params,
        ];
    }
}
