<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;
use function Gendiff\Lib\gendiff;
use function Gendiff\Parsers\readFile;
use function Gendiff\Parsers\getFileType;

class LibTest extends TestCase
{
    public function testGendiff(): void
    {
        $expectedString1 = file_get_contents(__DIR__ . "/fixtures/result1.txt");
        $expectedString2 = file_get_contents(__DIR__ . "/fixtures/result2.txt");
        $expectedString3 = file_get_contents(__DIR__ . "/fixtures/result3.txt");

        $result1 = gendiff(__DIR__ . "/fixtures/file1_1.json", __DIR__ . "/fixtures/file1_2.json");
        $result2 = gendiff(__DIR__ . "/fixtures/file2_1.json", __DIR__ . "/fixtures/file2_2.json");
        $result3 = gendiff(__DIR__ . "/fixtures/file1_1.yml", __DIR__ . "/fixtures/file1_2.yaml");
        
        $this->assertEquals($expectedString1, $result1);
        $this->assertEquals($expectedString2, $result2);
        $this->assertEquals($expectedString3, $result3);

        $this->assertNotEquals($expectedString1, $result2);
        $this->assertNotEquals($expectedString3, $result1);
    }

    public function testGetFileType(): void
    {
        $this->assertEquals('yml', getFileType("json.yaml"));
        $this->assertEquals('yml', getFileType("json.yml"));
        $this->assertEquals('json', getFileType("yaml.json"));
        $this->assertEquals('json', getFileType("yml.json"));
        $this->assertEquals('', getFileType("json.yaml.zip.exe"));
        $this->assertEquals('yml', getFileType("yaml.yml.json.yml"));
    }


    public function testReadFile(): void
    {
        $expectedArr1 = ['false'=>'false', 'grade' => 10, 'int' => 0, 'jobs' => 'no-jobs', 'name' => 'hexlet-check'];
        $arr1 = readFile(__DIR__ . "/fixtures/file1_1.yml", __DIR__ . "/fixtures/file1_2.yaml");
        $this->assertEquals($expectedArr1, (array)$arr1);
    }
}