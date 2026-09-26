<?php

namespace Tests\Feature;

use Tests\TestCase;

class SiteHeaderTest extends TestCase
{
    public function test_home_page_renders_the_shared_site_header(): void
    {
        $response = $this->get('/');

        $response->assertSeeText('Mushroom Glades');
        $response->assertSee('aria-label="Primary navigation"', false);
        $response->assertSee('aria-label="Mobile navigation"', false);
        $response->assertSee('aria-current="page"', false);
    }
}
