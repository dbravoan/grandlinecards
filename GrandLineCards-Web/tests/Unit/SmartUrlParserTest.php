<?php

namespace Tests\Unit;

use Tests\TestCase; // Using Feature TestCase to access Application binding/cache if needed
use App\Services\Catalog\SmartUrlParser;
use App\Models\Expansion;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmartUrlParserTest extends TestCase
{
    use RefreshDatabase;

    private SmartUrlParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new SmartUrlParser();
        
        // Seed some expansions for the test
        Expansion::create(['code' => 'OP01', 'name' => 'Romance Dawn', 'release_date' => now()]);
        Expansion::create(['code' => 'OP02', 'name' => 'Paramount War', 'release_date' => now()]);
    }

    public function test_parses_simple_expansion_slug(): void
    {
        $result = $this->parser->parse('op01');
        
        $this->assertContains('OP01', $result['expansion']);
        $this->assertEmpty($result['color']);
    }

    public function test_parses_combined_filters(): void
    {
        $result = $this->parser->parse('op01/rojo/coste-4');
        
        $this->assertContains('OP01', $result['expansion']);
        $this->assertContains('Red', $result['color']);
        $this->assertEquals(4, $result['cost']);
    }

    public function test_parses_complex_slug_with_multiple_values(): void
    {
        $result = $this->parser->parse('rojo/verde/lider');
        
        $this->assertContains('Red', $result['color']);
        $this->assertContains('Green', $result['color']);
        $this->assertContains('Leader', $result['type']);
        $this->assertContains('L', $result['rarity']); // Leader is usually L rarity too logic
    }

    public function test_generates_correct_metadata(): void
    {
        $slug = 'op01/rojo';
        $filters = $this->parser->parse($slug);
        $metadata = $this->parser->generateMetadata($filters);

        $this->assertStringContainsString('Romance Dawn', $metadata['meta_title']);
        $this->assertStringContainsString('Rojos', $metadata['meta_title']);
        $this->assertStringContainsString('Grand Line Cards', $metadata['meta_title']);
    }
}
