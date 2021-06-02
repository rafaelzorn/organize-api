<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class MovementTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_STORE  = '/api/v1/movements';

    /**
     * @test
     *
     * @return void
     */
    public function should_create_a_new_movement(): void
    {
        // TODO
        $this->assertEquals(true, true);
    }
}
