<?php

namespace InlineSvg;

use InlineSvg\Sources\SourceInterface;
use SimpleXMLElement;

class Template extends Collection
{
    protected $template = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(SourceInterface $source)
    {
        parent::__construct($source);
    }

    /**
     * {@inheritdoc}
     */
    public function load($name)
    {
        $element = parent::load($name);

        $id = uniqid('svg_fragment_');
        $code = '';
        $g = $element->g ? $element->g : $element;

        foreach ($g->children() as $child) {
            $code .= $child->asXML();
        }

        $this->template .=  <<<SVG
<g id="{$id}">{$code}</g>
SVG;

        $attributes = $element->attributes();

        $code = <<<SVG
<svg viewBox="{$attributes->viewBox}" width="{$attributes->width}" height="{$attributes->height}">
    <use xlink:href="#{$id}"/>
</svg>
SVG;

        return new SimpleXMLElement($code, LIBXML_NOBLANKS | LIBXML_NOERROR);
    }

    /**
     * Returns the main template with all icons used.
     *
     * @return Svg
     */
    public function getTemplate()
    {
        $code = <<<SVG
<svg style="display: none">{$this->template}</svg>
SVG;

        return new Svg(new SimpleXMLElement($code, LIBXML_NOBLANKS | LIBXML_NOERROR));
    }
}
