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

    private array $middleware = ['web', 'auth:web', 'admin',];

    public function testIndexMethod(): void
    {
        // $this->withoutExceptionHandling();
        Post::factory()->count(100)->create();
        $this->actingAs(User::factory()->admin()->create())
            ->get(route('post.index'))
            ->assertOk()
            ->assertViewIs('admin.posts.index')
            ->assertViewHas('posts', Post::query()->latest()->paginate(15));
        $this->assertEquals($this->middleware, request()->route()->middleware());
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
        $this->assertEquals($this->middleware, request()->route()->middleware());
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
        $this->assertEquals($this->middleware, request()->route()->middleware());
    }

    public function testStoreMethod(): void
    {
        $user = User::factory()->admin()->create();
        $data = Post::factory()->state(['user_id' => $user->id])->make()->toArray();
        /** @noinspection PhpUnhandledExceptionInspection */
        $tags = Tag::factory()->count(random_int(1, 5))->create();
        $this->actingAs($user)
            ->post(route('post.store'),
                array_merge(
                    ['tags' => $tags->pluck('id')->toArray()],
                    $data,
                ))
            ->assertSessionHas('message', 'new post has been created')
            ->assertRedirect(route('post.index'));
        $this->assertDatabaseHas("posts", $data);
        $this->assertEquals(
            $tags->pluck('id')->toArray(),
            Post::query()->where($data)->first()->tags()->pluck('id')->toArray(),
        );
        $this->assertEquals($this->middleware, request()->route()->middleware());
    }

    public function testUpdateMethod(): void
    {
        $user = User::factory()->admin()->create();
        $data = Post::factory()->state(['user_id' => $user->id])->make()->toArray();
        /** @noinspection PhpUnhandledExceptionInspection */
        /** @noinspection PhpUndefinedMethodInspection */
        $post = Post::factory()->state(['user_id' => $user->id])->hasTags(random_int(1, 5))->create();
        /** @noinspection PhpUnhandledExceptionInspection */
        $tags = Tag::factory()->count(random_int(1, 5))->create();
        $this->actingAs($user)
            ->patch(route('post.update', $post->id),
                array_merge(
                    ['tags' => $tags->pluck('id')->toArray()],
                    $data,
                ))
            ->assertSessionHas('message', 'the post has been updated')
            ->assertRedirect(route('post.index'));
        $this->assertDatabaseHas("posts", array_merge(['id' => $post->id], $data));
        $this->assertEquals(
            $tags->pluck('id')->toArray(),
            Post::query()->where($data)->first()->tags()->pluck('id')->toArray(),
        );
        $this->assertEquals($this->middleware, request()->route()->middleware());
    }

}
