<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use App\Constants\HttpStatusConstant;

class MovementUpdateTest extends TestCase
{
    use DatabaseMigrations;

    private const URL_UPDATE  = '/api/v1/movements/';
}
