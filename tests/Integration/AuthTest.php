<?php

class AuthTest extends TestCase
{
    /** @test */
    public function test_is_empty()
    {
        $empty = [];

        $this->assertEmpty($empty);
    }
}
