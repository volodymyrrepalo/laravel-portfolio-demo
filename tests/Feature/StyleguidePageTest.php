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
    }
}
