<?php

namespace Tests\Feature\Views;

use App\Models\Post;
use App\Models\User;
use DOMDocument;
use DOMXPath;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleViewTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testSingleViewRenderedWhenUserLoggedIn(): void
    {
        $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        $comments = [];
        $view = (string)$this->actingAs(User::factory()->create())
            ->view('single', compact('post', 'comments'));
        $dom = new  DOMDocument('1.0', 'UTF-8');
        // set error level
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($view);
        // Restore error level
        libxml_use_internal_errors($internalErrors);
        $dom = new DOMXPath($dom);
        $action = route('single.comment', $post);
        $this->assertCount(1,
            $dom->query("//form[@method='post'][@action='$action']//textarea[@name='text']"));
    }

    public function testSingleViewRenderedWhenNotUserLoggedIn(): void
    {
        // $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        $comments = [];
        $view = (string)$this->view('single', compact('post', 'comments'));
        $dom = new  DOMDocument('1.0', 'UTF-8');
        // set error level
        $internalErrors = libxml_use_internal_errors(true);
        $dom->loadHTML($view);
        // Restore error level
        libxml_use_internal_errors($internalErrors);
        $dom = new DOMXPath($dom);
        $action = route('single.comment', $post);
        $this->assertCount(0,
            $dom->query("//form[@method='post'][@action='$action']//textarea[@name='text']"));
    }

}
