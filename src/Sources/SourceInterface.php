<?php

namespace InlineSvg\Sources;

use DOMElement;

interface SourceInterface
{
    /**
     * Returns a svg.
     *
     * @param string $name The svg name
     *
     * @return DOMElement
     */
    public function get($name);

    /**
     * Check if the svg exists.
     *
     * @param string $name The svg name
     *
     * @return bool
     */
    public function has($name);
}
