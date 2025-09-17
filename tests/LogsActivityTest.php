<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kazitoha\ActivityLogger\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

it('logs create update delete', function () {
    $tc = test_case();
    $tc->setUp();

    // create test table
    Schema::create('posts', function (Blueprint $t) {
        $t->id();
        $t->string('title');
        $t->timestamps();
    });

    // dummy model
    $postModel = new class extends Model {
        use LogsActivity;
        protected $table = 'posts';
        protected $guarded = [];
    };

    // act
    $post = $postModel->create(['title' => 'hello']);
    $post->update(['title' => 'world']);
    $post->delete();

    // assert
    expect(\Kazitoha\ActivityLogger\Models\ActivityLog::query()->count())->toBe(3);

    $tc->tearDown();
});
