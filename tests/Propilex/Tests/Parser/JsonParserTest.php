<?php

namespace Propilex\Tests\Parser;

use Propilex\Parser\JsonParser;
use Propilex\Tests\TestCase;

class JsonParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new JsonParser();
    }

    public function testFromArrayRemovesRootElement()
    {
        $array = array('foo' => array('bar' => '1', 'baz' => '2'));
        $expected = '[{"bar":"1","baz":"2"}]';

        $this->assertEquals($expected, $this->parser->fromArray($array));
    }

    public function testFromArrayRemovesRootElementWithMultipleData()
    {
        $array = array('foo' => array('bar' => '1', 'baz' => '2'), 'bar' => array('titi' => '4', 'toto' => 5));
        $expected = '[{"bar":"1","baz":"2"},{"titi":"4","toto":5}]';

        $this->assertEquals($expected, $this->parser->fromArray($array));
    }

    public function testFromArrayHandlesSingleRecord()
    {
        $array = array('bar' => '1', 'baz' => '2');
        $expected = '{"bar":"1","baz":"2"}';

        $this->assertEquals($expected, $this->parser->fromArray($array));
    }
}
