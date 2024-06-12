<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureAPIToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\APITestCase;

class APITokenMiddlewareTest extends APITestCase
{
    /**
     * A basic feature test example.
     */
    public function test_endpoint_without_correct_token(): void
    {
        $request = Request::create('/members/fake', 'GET');

        $middleware = new EnsureAPIToken;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_endpoint_with_correct_token(): void
    {
        $request = Request::create('/members/fake', 'GET');
        $request->headers->set('Authorization', 'Bearer ' . config('app.api_token'));

        $middleware = new EnsureAPIToken;

        $next = function () {
            return response()->json(['message' => 'Authorized'], 200);
        };

        $response = $middleware->handle($request, $next);

        $this->assertEquals(200, $response->getStatusCode());

    }
}
