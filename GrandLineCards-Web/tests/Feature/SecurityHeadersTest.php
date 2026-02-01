<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    /**
     * Test that the application responses contain security headers.
     */
    public function test_application_returns_security_headers(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Content-Security-Policy');
        
        // HSTS is only enabled in production usually, but we can check if it's NOT present in testing by default
        // or mock environment if needed. For now, let's just assert the mandatory ones are there.
    }

    public function test_csp_header_structure(): void
    {
        $response = $this->get('/');
        
        $csp = $response->headers->get('Content-Security-Policy');
        
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("img-src 'self'", $csp);
    }
}
