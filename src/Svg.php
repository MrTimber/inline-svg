<?php

namespace InlineSvg;

use SimpleXMLElement;

class Svg
{
    private $svg;

    public function __construct(SimpleXMLElement $svg)
    {
        $this->svg = $svg;
    }

    /**
     * Return the svg.
     * 
     * @return SimpleXMLElement
     */
    public function get()
    {
        return $this->svg;
    }

    /**
     * Magic method to clone.
     */
    public function __clone()
    {
        $this->svg = clone $this->svg;
    }

    /**
     * Set a new attribute of the svg.
     *
     * @param string $name
     * @param string $value
     *
     * @return self
     */
    public function withAttribute($name, $value)
    {
        $clone = clone $this;
        $attributes = $clone->svg->attributes();

        if (!empty($attributes->$name)) {
            $attributes->$name = $value;
        } else {
            $clone->svg->addAttribute($name, $value);
        }

        return $clone;
    }

    /**
     * Set new attributes.
     * 
     * @param array $attributes
     * 
     * @return self
     */
    public function withAttributes(array $attributes)
    {
        $clone = clone $this;

        foreach ($attributes as $name => $value) {
            $clone = $clone->withAttribute($name, $value);
        }

        return $clone;
    }

    /**
     * Set accessibility information to the svg.
     * 
     * @param string|null $title Short description
     * @param string|null $desc  Long description
     *
     * @return Svg
     */
    public function withA11y($title = null, $desc = null)
    {
        $clone = clone $this;

        $clone->svg->addAttribute('role', 'img');

        $ids = [];

        if ($title) {
            $clone->svg->addChild('title', $title)->addAttribute('id',  $ids[] = uniqid('svg_title_'));
        }

        if ($desc) {
            $clone->svg->addChild('desc', $desc)->addAttribute('id', $ids[] = uniqid('svg_desc_'));
        }

        if ($ids) {
            $clone->svg->addAttribute('aria-labelledby', implode(' ', $ids));
        }

        return $clone;
    }

    /**
     * Returns the xml code ready to embed in the html.
     *
     * @return string
     */
    public function __toString()
    {
        return preg_replace('|^.*(<svg.*</svg>).*$|Us', '$1', $this->svg->asXML());
    }
}
