<?php

namespace InlineSvg\Transformers;

use SimpleXMLElement;

class Cleaner
{
    /**
     * Execute the transformer.
     *
     * @param SimpleXMLElement
     */
    public function __invoke(SimpleXMLElement $svg)
    {
        $this->addSource($source);
    }
}
