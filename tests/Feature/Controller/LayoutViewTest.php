<?php

namespace Tests\Feature\Controller;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LayoutViewTest extends TestCase
{
    use WithFaker, RefreshDatabase , UrlHelper;

    public function testLayoutViewRenderWhenUserIsAdmin(): void
    {
        $user = User::factory()->state(['type'=>'admin'])->create();
        $this->actingAs($user);
        $view = $this->view('layouts.layout');
        // $view->assertSee('<a href="/admin/dashboard">admin panel</a>',false);
        // $view->assertSee('<a href="http://127.0.0.1:8000/admin/dashboard">admin panel</a>',false);
        $view->assertSee('<a href="'.$this->URL_NAME.'admin/dashboard">admin panel</a>',false);
    }

    public function testLayoutViewRenderWhenUserIsNotAdmin(): void
    {
        $user = User::factory()->state(['type'=>'user'])->create();
        $this->actingAs($user);
        $view = $this->view('layouts.layout');
        // $view->assertDontSee('<a href="/admin/dashboard">admin panel</a>',false);
        // $view->assertDontSee('<a href="http://127.0.0.1:8000/admin/dashboard">admin panel</a>',false);
        $view->assertDontSee('<a href="'.$this->URL_NAME.'admin/dashboard">admin panel</a>',false);
    }

}
