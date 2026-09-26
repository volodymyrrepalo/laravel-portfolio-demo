<?php

namespace Tests\Feature;

use Tests\TestCase;

class StyleguidePageTest extends TestCase
{
    public function test_styleguide_page_renders_the_design_system_showcase(): void
    {
        $response = $this->get('/styleguide');

        $response->assertSeeText('Mushroom Glades');
        $response->assertSeeText('Color system and contrast');
        $response->assertSeeText('Accessibility verification suite');
        $response->assertSee('aria-label="Primary navigation"', false);
        $response->assertSee('aria-label="Mobile navigation"', false);
        $response->assertSeeText('Home');
        $response->assertSeeText('Map');
        $response->assertSeeText('Favorites');
        $response->assertSeeText('Profile');
    }

    public function test_styleguide_page_renders_the_mushroom_carousel(): void
    {
        $response = $this->get('/styleguide');

        $response->assertSee('aria-roledescription="carousel"', false);
        $response->assertSee('aria-label="Mushroom highlights"', false);
        $response->assertSeeText('Golden Chanterelle');
        $response->assertSeeText('Fly Agaric');
        $response->assertSeeText('Common Morel');
        $response->assertSeeText('Previous mushroom');
        $response->assertSeeText('Next mushroom');
        $response->assertSeeText('Explore on Wikipedia');
        $response->assertSeeText('CC0 via Wikimedia Commons');
        $response->assertSeeText('Public domain via Wikimedia Commons');
        $response->assertSee('https://en.wikipedia.org/wiki/Cantharellus_cibarius', false);
    }
}
