<?php

namespace InlineSvg\Sources;

use SimpleXMLElement;

interface SourceInterface
{
    /**
     * Returns a svg.
     *
     * @param string $name The svg name
     *
     * @return SimpleXMLElement
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
