<?php

namespace InlineSvg\Sources;

use SimpleXMLElement;

class FileSystem implements SourceInterface
{
    protected $basePath;
    protected $map;

    /**
     * Constructor.
     *
     * @param string $basePath The directory where the icon files are placed
     */
    public function __construct($basePath, array $map = [])
    {
        $this->basePath = $basePath;
        $this->map = $map;
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        return is_file($this->getPath($name));
    }

    /**
     * {@inheritdoc}
     */
    public function get($name)
    {
        return new SimpleXMLElement($this->getPath($name), LIBXML_NOBLANKS | LIBXML_NOERROR, true);
    }

    /**
     * Returns the path of a svg.
     * 
     * @param string $name
     * 
     * @return string
     */
    protected function getPath($name)
    {
        $name = isset($this->map[$name]) ? $this->map[$name] : $name;

        return "{$this->basePath}/{$name}.svg";
    }
}
