<?php

namespace Tests;

use App\Core\Request;

class RouterTest extends TestCase
{
    public function test_dispatch_returns_known_route(): void
    {
        $this->router->get('/ping', fn () => 'pong');

        $response = $this->router->dispatch($this->makeRequest('GET', '/ping'));

        $this->assertSame('pong', $response->getBody());
        $this->assertSame(200, $response->getStatus());
    }

    public function test_dispatch_returns_404_when_path_missing(): void
    {
        $response = $this->router->dispatch($this->makeRequest('GET', '/unknown'));

        $this->assertSame(404, $response->getStatus());
        $this->assertStringContainsString('Page non trouvée', $response->getBody());
    }

    public function test_route_parameters_are_available(): void
    {
        $this->router->get('/users/{id}', function (Request $request) {
            return response($request->route('id'));
        });

        $response = $this->router->dispatch($this->makeRequest('GET', '/users/21'));

        $this->assertSame('21', $response->getBody());
    }
}
