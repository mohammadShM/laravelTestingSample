<?php /** @noinspection PhpPossiblePolymorphicInvocationInspection */

namespace Tests\Feature\Controller\Admin;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostControllerTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testIndexMethod(): void
    {
        // $this->withoutExceptionHandling();
        Post::factory()->count(100)->create();
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('post.index'))
            ->assertOk()
            ->assertViewIs('admin.posts.index')
            ->assertViewHas('posts', Post::query()->latest()->paginate(15));
        $this->assertEquals(['web', 'auth:web', 'admin'], request()->route()->middleware());
    }

    public function testCreateMethod(): void
    {
        // $this->withoutExceptionHandling();
        Tag::factory()->count(20)->create();
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('post.create'))
            ->assertOk()
            ->assertViewIs('admin.posts.create')
            ->assertViewHas('tags', Tag::query()->latest()->get());
        $this->assertEquals(['web', 'auth:web', 'admin'], request()->route()->middleware());
    }

    public function testEditMethod(): void
    {
        // $this->withoutExceptionHandling();
        $post = Post::factory()->create();
        Tag::factory()->count(20)->create();
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('post.edit', $post))
            ->assertOk()
            ->assertViewIs('admin.posts.edit')
            ->assertViewHasAll([
                'tags' => Tag::query()->latest()->get(),
                'post' => $post,
            ]);
        $this->assertEquals(['web', 'auth:web', 'admin'], request()->route()->middleware());
    }

}
