<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use HkDevs\CodeForgeStudio\Tests\TestCase;

class BasicTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_basic_assertion()
    {
        $this->assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_php_version()
    {
        $this->assertGreaterThanOrEqual(8.1, (float)PHP_VERSION);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function test_string_operations()
    {
        $string = 'HkDevs CodeForgeStudio';
        $this->assertStringContainsString('HkDevs', $string);
        $this->assertStringContainsString('CodeForgeStudio', $string);
    }
}
