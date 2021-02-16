<?php

namespace InlineSvg\Transformers;

use DOMDocument;

class Cleaner
{
    /**
     * Execute the transformer.
     *
     * @param DOMDocument $dom
     */
    public function __invoke(DOMDocument $dom)
    {
        $elements = $dom->getElementsByTagName('*');

        // Replace IDs by uniq IDs
        $id_array = [];
        foreach ($elements as $element) {
            $id = $element->getAttribute('id');
            if ($id) {
                $new_id = uniqid();
                $id_array["#$id"] = "#$new_id";
                $element->setAttribute('id', $new_id);
            }
        }
        foreach ($elements as $element) {
            if ($element->hasAttributes()) {
                foreach ($element->attributes as $attr) {
                    $name  = $attr->nodeName;
                    $value = $attr->nodeValue;
                    if (array_key_exists($value, $id_array)) {
                        $element->setAttribute($name, $id_array[$value]);
                    }
                }
            }
        }

        // Remove <desc>
        foreach ($dom->getElementsByTagName('desc') as $element) {
            $element->parentNode->removeChild($element);
        }

        // Remove empty <defs>
        foreach ($dom->getElementsByTagName('defs') as $element) {
            if (!$element->hasChildNodes()) {
                $element->parentNode->removeChild($element);
            }
        }

        // Remove <title>
        foreach ($dom->getElementsByTagName('title') as $element) {
            $element->parentNode->removeChild($element);
        }
    }
}
