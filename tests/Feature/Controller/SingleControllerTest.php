<?php
/** @noinspection PhpUnhandledExceptionInspection */

/** @noinspection PhpUndefinedMethodInspection */

namespace Tests\Feature\Controller;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SingleControllerTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testIndexSingleMethod(): void
    {
        // $this->withoutExceptionHandling();
        $post = Post::factory()->hasComments(random_int(0, 3))->create();
        $response = $this->get(route('single', $post->id));
        $response->assertOk();
        $response->assertViewIs('single');
        $response->assertViewHasAll([
            'post' => $post,
            'comments' => $post->comments()->latest()->paginate(15),
        ]);
    }

    public function testCommentMethodWhenUserLoggedIn(): void
    {
        // $this->withoutExceptionHandling();
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Post $post */
        $post = Post::factory()->create();
        $data = Comment::factory()->state([
            'user_id' => $user->id,
            'commentable_id' => $post->id,
        ])->make()->toArray();
        $response = $this
            ->actingAs($user)
            // for set request ajax
            ->withHeaders([
                "HTTP_X-Requested-with" => 'XMLHttpRequest',
            ])
            ->postJson(route('single.comment', $post->id), ['text' => $data['text']]);
        //$response->assertRedirect(route('single', $post->id));
        $response->assertOk()->assertJson([
            'created' => true,
        ]);
        $this > $this->assertDatabaseHas('comments', $data);
    }

    public function testCommentMethodWhenUserNotLoggedIn(): void
    {
        // $this->withoutExceptionHandling();
        /** @var Post $post */
        $post = Post::factory()->create();
        $data = Comment::factory()->state([
            'commentable_id' => $post->id,
        ])->make()->toArray();
        unset($data['user_id']);
        $response = $this
            // for set request ajax
            ->withHeaders([
                "HTTP_X-Requested-with" => 'XMLHttpRequest',
            ])
            ->postJson(route('single.comment', $post->id), ['text' => $data['text']]);
        // $response->assertRedirect(route('login'));
        $response->assertUnauthorized();
        $this > $this->assertDatabaseMissing('comments', $data);
    }


    public function testCommentMethodValidRequiredData(): void
    {
        // $this->withoutExceptionHandling();
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Post $post */
        $post = Post::factory()->create();
        $response = $this->actingAs($user)
            // for set request ajax
            ->withHeaders([
                "HTTP_X-Requested-with" => 'XMLHttpRequest',
            ])
            ->postJson(route('single.comment', $post->id), ['text' => '']);
        $response->assertJsonValidationErrors([
            'text' => 'The text field is required.'
        ]);
    }

}
