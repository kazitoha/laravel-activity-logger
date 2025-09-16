<?php

use Orchestra\Testbench\TestCase;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Kazitoha\ActivityLogger\ActivityLoggerServiceProvider;
use Kazitoha\ActivityLogger\Traits\LogsActivity;
use Kazitoha\ActivityLogger\Models\ActivityLog;

uses(TestCase::class)->in('.');

beforeEach(function () {
    // Load package provider
    $this->app->register(ActivityLoggerServiceProvider::class);

    // Create a dummy table for a test model
    Schema::create('posts', function (Blueprint $t) {
        $t->id();
        $t->string('title')->nullable();
        $t->timestamps();
    });
});

it('logs create, update, and delete', function () {
    $postModel = new class extends \Illuminate\Database\Eloquent\Model {
        use LogsActivity;
        protected $table = 'posts';
        protected $guarded = [];
    };

    $p = $postModel->create(['title' => 'hello']);
    $p->update(['title' => 'world']);
    $p->delete();

    expect(ActivityLog::query()->count())->toBe(3);
});
