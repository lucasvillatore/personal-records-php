<?php

namespace App\Presentation\Web\Router;

class Router
{
    private array $routes = [];

    /**
     * Adiciona uma nova rota ao roteador.
     * O handler (função de callback) deve retornar os dados que serão convertidos em JSON.
     *
     * @param string $method O método HTTP (GET, POST, PUT, DELETE, etc.).
     * @param string $path O caminho da URL com parâmetros opcionais como {id}.
     * @param callable $handler A função de callback que será executada para esta rota.
     * Esta função deve retornar um array ou objeto que será JSON-encodado.
     */
    public function add(string $method, string $path, callable $handler): void
    {
        $this->routes[] = [$method, $this->compilePath($path), $handler];
    }

    /**
     * Despacha a requisição para a rota correspondente.
     * Sempre define o cabeçalho Content-Type como application/json.
     *
     * @param string $method O método HTTP da requisição atual.
     * @param string $uri A URI completa da requisição atual.
     */
    public function dispatch(string $method, string $uri): void
    {
        header('Content-Type: application/json');

        $uri = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if (strtoupper($method) !== strtoupper($routeMethod)) {
                continue;
            }

            if (preg_match($pattern['regex'], $uri, $matches)) {
                $params = [];
                foreach ($pattern['params'] as $name => $index) {
                    $params[$name] = $matches[$index] ?? null;
                }

                $responseData = call_user_func_array($handler, $params);

                echo json_encode($responseData);
                return; 
            }
        }

        http_response_code(404);
        echo json_encode(['error' => '404 - Página não encontrada', 'message' => 'O recurso solicitado não foi encontrado.']);
    }

    /**
     * Compila o caminho da rota em uma expressão regular e extrai os nomes dos parâmetros.
     *
     * @param string $path O caminho da rota com placeholders como {nome}.
     * @return array Um array contendo a expressão regular e os nomes dos parâmetros.
     */
    private function compilePath(string $path): array
    {
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) {
            return '(?P<' . $matches[1] . '>[^/]+)';
        }, rtrim($path, '/')); // Remove barra final para consistência

        $regex = '@^' . $pattern . '$@D';

        preg_match_all('/\{(\w+)\}/', $path, $paramNames);
        $params = array_flip($paramNames[1]);

        return [
            'regex' => $regex,
            'params' => $params,
        ];
    }
}
