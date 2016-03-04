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
            $element->addAttribute('bar', 'foo');
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

        $this->assertEquals(1, $element->count());
        $this->assertEquals(1, $element->g->count());
        $this->assertEquals(4, $element->g->path->count());

        return $svg;
    }

    public function testAttributes()
    {
        $svg = self::$collection;

        $gaming = $svg->get('gaming');
        $element = $gaming->get();

        $attributes = $element->attributes();

        $this->assertEquals('foo', $attributes->bar);
        $this->assertEquals('1.1', $attributes->version);
        $this->assertEquals('512px', $attributes->width);
        $this->assertEquals('512px', $attributes->height);
        $this->assertEquals('0px', $attributes->x);
        $this->assertEquals('0px', $attributes->y);

        $gaming2 = $gaming->withAttributes([
            'x' => '10px',
            'y' => 23,
            'new-attribute' => 'value',
        ]);

        $attributes2 = $gaming2->get()->attributes();

        $this->assertEquals('512px', $attributes2->height);
        $this->assertEquals('10px', $attributes2->x);
        $this->assertEquals('23', $attributes2->y);
        $this->assertEquals('value', $attributes2['new-attribute']);

        $this->assertEquals('0px', $attributes->x);
    }

    public function testAccesibility()
    {
        $svg = self::$collection;

        $gaming = $svg->get('gaming');
        $element = $gaming->get();
        $attributes = $element->attributes();

        $this->assertNull($attributes->role);
        $this->assertEquals(0, $element->title->count());
        $this->assertEquals(0, $element->desc->count());

        $gaming = $gaming->withA11y('The title', 'the long description');

        $element = $gaming->get();
        $attributes = $element->attributes();

        $this->assertEquals('img', $attributes->role);
        $this->assertEquals(1, $element->title->count());
        $this->assertEquals(1, $element->desc->count());
        $this->assertEquals('The title', (string) $element->title);
        $this->assertEquals('the long description', (string) $element->desc);
    }

    public function testTemplate()
    {
        $svg = new Template(self::$source);

        $this->assertInstanceOf('InlineSvg\Svg', $svg->get('gaming'));

        $this->assertEquals($svg->getTemplate()->get()->g->count(), 1);

        $svg->get('days');

        $this->assertEquals($svg->getTemplate()->get()->g->count(), 2);
    }
}
