<?php

namespace HkDevs\CodeForgeStudio\Tests\Unit;

use HkDevs\CodeForgeStudio\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class BasicTest extends TestCase
{
    #[Test]
    public function test_basic_assertion()
    {
        $this->assertTrue(true);
    }

    #[Test]
    public function test_php_version()
    {
        $this->assertGreaterThanOrEqual(8.1, (float) PHP_VERSION);
    }

    #[Test]
    public function test_string_operations()
    {
        $string = 'HkDevs CodeForgeStudio';
        $this->assertStringContainsString('HkDevs', $string);
        $this->assertStringContainsString('CodeForgeStudio', $string);
    }
}
