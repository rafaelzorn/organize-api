<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class MovementShowTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_SHOW  = '/api/v1/movements';
}
