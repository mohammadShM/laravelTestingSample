<?php /** @noinspection PhpRedundantOptionalArgumentInspection */

/** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Tests\Feature\Middlewares;

use App\Http\Middleware\CheckUserIsAdmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;

class CheckUserIsAdminMiddleware extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testWhenUserIsNotAdmin(): void
    {
        $user = User::factory()->user()->create();
        $this->actingAs($user);
        $request = Request::create('/admin', 'GET');
        $middleware = new CheckUserIsAdmin();
        $response = $middleware->handle($request, static function () {
        });
        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testWhenUserIsAdmin(): void
    {
        $user = User::factory()->admin()->create();
        $this->actingAs($user);
        $request = Request::create('/admin', 'GET');
        $middleware = new CheckUserIsAdmin();
        $response = $middleware->handle($request, static function () {
        });
        $this->assertEquals(null, $response);
    }

    public function testWhenUserIsNotLoggedIn() : void
    {
        $request = Request::create('/admin', 'GET');
        $middleware = new CheckUserIsAdmin();
        $response = $middleware->handle($request, static function () {
        });
        $this->assertEquals(302, $response->getStatusCode());
    }

}
