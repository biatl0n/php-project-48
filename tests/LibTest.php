<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\Lib\gendiff;

class LibTest extends TestCase
{
    public function testGendiff(): void
    {
        $expectedString1 = file_get_contents(__DIR__ . "/fixtures/result1.txt");
        $expectedString2 = file_get_contents(__DIR__ . "/fixtures/result2.txt");
        $result1 = gendiff(__DIR__ . "/fixtures/file1_1.json", __DIR__ . "/fixtures/file1_2.json");
        $result2 = gendiff(__DIR__ . "/fixtures/file2_1.json", __DIR__ . "/fixtures/file2_2.json");
        

        $this->assertEquals($expectedString1, $result1);
        $this->assertEquals($expectedString2, $result2);
        $this->assertNotEquals($expectedString1, $result2);
    }
}