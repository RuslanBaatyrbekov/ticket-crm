<?php

namespace Tests\Feature;

use Tests\TestCase;

class WidgetTest extends TestCase
{
    public function test_the_widget_can_be_embedded_in_an_iframe(): void
    {
        $response = $this->get('/widget');

        $response->assertOk();
        $this->assertFalse($response->headers->has('X-Frame-Options'));
        $this->assertSame('frame-ancestors *', $response->headers->get('Content-Security-Policy'));
    }
}
