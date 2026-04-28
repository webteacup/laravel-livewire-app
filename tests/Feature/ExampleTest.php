<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Guests are redirected to the sign-in page from root.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }
}
