<?php

use PHPUnit\Framework\TestCase;

class FieldsTest extends TestCase
{
    /** @var EER_Fields */
    private $fields;

    protected function setUp(): void
    {
        $this->fields = new EER_Fields();
    }

    public function test_int_sanitize()
    {
        $this->assertSame(123, $this->fields->sanitize('int', '123'));
    }

    public function test_boolean_sanitize()
    {
        $this->assertTrue($this->fields->sanitize('boolean', 'true'));
        $this->assertFalse($this->fields->sanitize('boolean', 'false'));
    }

    public function test_timestamp_sanitize()
    {
        $this->assertSame('2020-12-31 23:45', $this->fields->sanitize('timestamp', '2020-12-31 23:45:00'));
    }

    public function test_html_sanitize()
    {
        $this->assertSame("O\\'Reilly", $this->fields->sanitize('html', "O'Reilly"));
    }

    public function test_json_sanitize()
    {
        $json = '{"key":1}';
        $this->assertSame($json, $this->fields->sanitize('json', $json));
    }
}
