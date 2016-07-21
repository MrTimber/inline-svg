<?php

include_once dirname(__DIR__).'/src/autoloader.php';

use InlineSvg\Collection;
use InlineSvg\Template;
use InlineSvg\Sources\FileSystem;

class InlineSvgTest extends PHPUnit_Framework_TestCase
{
    private static $collection;
    private static $source;

    public static function setUpBeforeClass()
    {
        self::$source = new FileSystem(dirname(__DIR__).'/demo/icons', ['gamepad' => 'gaming']);

        self::$collection = new Collection(self::$source);

        self::$collection->addTransformer(function ($element) {
            $element->setAttribute('bar', 'foo');
            return $element;
        });
    }

    public function testSource()
    {
        $svg = self::$collection;
        $source = self::$source;

        $this->assertInstanceOf('InlineSvg\Svg', $svg->get('gaming'));
        $this->assertInstanceOf('InlineSvg\Svg', $svg->get('gamepad'));

        $this->assertTrue($source->has('gaming'));
        $this->assertTrue($source->has('gamepad'));
        $this->assertFalse($source->has('notfound'));
    }

    /**
     * @expectedException InlineSvg\NotFoundException
     */
    public function testException()
    {
        self::$collection->get('notFound');
    }

    public function testGet()
    {
        $svg = self::$collection;

        $this->assertInstanceOf('InlineSvg\Svg', $svg->get('gaming'));
        $this->assertSame($svg->get('gaming'), $svg->get('gaming'));
    }

    public function testReadXml()
    {
        $svg = self::$collection;

        $element = $svg->get('gaming')->get();

        $this->assertEquals(1, $element->childNodes->length);
        $this->assertEquals(1, $element->getElementsByTagName('g')->length);
        $this->assertEquals(4, $element->getElementsByTagName('path')->length);

        return $svg;
    }

    public function testAttributes()
    {
        $svg = self::$collection;

        $gaming = $svg->get('gaming');
        $element = $gaming->get();

        $this->assertEquals('foo', $element->getAttribute('bar'));
        $this->assertEquals('1.1', $element->getAttribute('version'));
        $this->assertEquals('512px', $element->getAttribute('width'));
        $this->assertEquals('512px', $element->getAttribute('height'));
        $this->assertEquals('0px', $element->getAttribute('x'));
        $this->assertEquals('0px', $element->getAttribute('y'));

        $gaming2 = $gaming->withAttributes([
            'x' => '10px',
            'y' => 23,
            'new-attribute' => 'value',
        ]);

        $element2 = $gaming2->get();

        $this->assertEquals('512px', $element2->getAttribute('height'));
        $this->assertEquals('10px', $element2->getAttribute('x'));
        $this->assertEquals('23', $element2->getAttribute('y'));
        $this->assertEquals('value', $element2->getAttribute('new-attribute'));

        $this->assertEquals('0px', $element->getAttribute('x'));
    }

    public function testAccesibility()
    {
        $svg = self::$collection;

        $gaming = $svg->get('gaming');
        $element = $gaming->get();

        $this->assertFalse($element->hasAttribute('role'));
        $this->assertEquals(0, $element->getElementsByTagName('title')->length);
        $this->assertEquals(0, $element->getElementsByTagName('desc')->length);

        $gaming = $gaming->withA11y('The title', 'the long description');

        $element = $gaming->get();

        $this->assertEquals('img', $element->getAttribute('role'));
        $this->assertEquals(1, $element->getElementsByTagName('title')->length);
        $this->assertEquals(1, $element->getElementsByTagName('title')->length);
        $this->assertEquals('The title', $element->getElementsByTagName('title')[0]->nodeValue);
        $this->assertEquals('the long description', $element->getElementsByTagName('desc')[0]->nodeValue);
    }
}
