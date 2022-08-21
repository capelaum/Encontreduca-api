<?php

namespace Tests;

use App\Models\Resource;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setup(): void
    {
        parent::setup();

        $this->withoutExceptionHandling();

        $this->seed('CategorySeeder');
        $this->seed('UserSeeder');
        $this->seed('MotiveSeeder');
    }

    public function createResource(array $args = [])
    {
        return Resource::factory()->create($args);
    }
}
