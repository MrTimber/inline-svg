<?php

namespace InlineSvg\Sources;

use DOMDocument;
use DOMElement;

class FileSystem implements SourceInterface
{
    protected $basePath;
    protected $map;

    /**
     * Constructor.
     *
     * @param string $basePath The directory where the icon files are placed
     * @param array  $map      Optional svg renamed
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
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->load($this->getPath($name), LIBXML_NOBLANKS | LIBXML_NOERROR);
        $svg = $dom->documentElement;

        if ($svg->tagName !== 'svg') {
            throw new \RuntimeException(sprintf('Only <svg> elements allowed. <%s> found', $svg->tagName));
        }

        return $svg;
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
