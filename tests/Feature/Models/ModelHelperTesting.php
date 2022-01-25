<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

trait ModelHelperTesting
{

    /** @noinspection PhpUndefinedMethodInspection
     * @noinspection PhpPossiblePolymorphicInvocationInspection
     */
    public function testInsertData(): void
    {
        /** $data = Comment::factory()->make()->toArray();
         * Comment::create($data);
         * $this->assertDatabaseHas('comments', $data); */
        $model = $this->model(); // example === Comment
        $table = $model->getTable(); // example === 'comments'
        $data = $model->factory()->make()->toArray();
        // for password hidden in User Model and use bellow code when fillable set in model (not set guarded)
        if ($model instanceof User) {
            $data['password'] = 12345678;
        }
        $model->create($data);
        $this->assertDatabaseHas($table, $data);
    }

    abstract protected function model(): Model;

}
