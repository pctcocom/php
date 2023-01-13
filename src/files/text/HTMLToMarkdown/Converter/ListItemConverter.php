<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown\Converter;

use pctco\php\files\text\HTMLToMarkdown\Configuration;
use pctco\php\files\text\HTMLToMarkdown\ConfigurationAwareInterface;
use pctco\php\files\text\HTMLToMarkdown\ElementInterface;

class ListItemConverter implements ConverterInterface, ConfigurationAwareInterface
{
    /** @var Configuration */
    protected $config;

    /** @var string|null */
    protected $listItemStyle;

    public function setConfig(Configuration $config): void
    {
        $this->config = $config;
    }

    public function convert(ElementInterface $element): string
    {
        // If parent is an ol, use numbers, otherwise, use dashes
        $listType = ($parent = $element->getParent()) ? $parent->getTagName() : 'ul';

        // Add spaces to start for nested list items
        $level = $element->getListItemLevel();

        $value = \trim(\implode("\n" . '    ', \explode("\n", \trim($element->getValue()))));

        // If list item is the first in a nested list, add a newline before it
        $prefix = '';
        if ($level > 0 && $element->getSiblingPosition() === 1) {
            $prefix = "\n";
        }

        if ($listType === 'ul') {
            $listItemStyle          = \strval($this->config->getOption('list_item_style', '-'));
            $listItemStyleAlternate = \strval($this->config->getOption('list_item_style_alternate', ''));
            if (! isset($this->listItemStyle)) {
                $this->listItemStyle = $listItemStyleAlternate ?: $listItemStyle;
            }

            if ($listItemStyleAlternate && $level === 0 && $element->getSiblingPosition() === 1) {
                $this->listItemStyle = $this->listItemStyle === $listItemStyle ? $listItemStyleAlternate : $listItemStyle;
            }

            return $prefix . $this->listItemStyle . ' ' . $value . "\n";
        }

        if ($listType === 'ol' && ($parent = $element->getParent()) && ($start = \intval($parent->getAttribute('start')))) {
            $number = $start + $element->getSiblingPosition() - 1;
        } else {
            $number = $element->getSiblingPosition();
        }

        return $prefix . $number . '. ' . $value . "\n";
    }

    /**
     * @return string[]
     */
    public function getSupportedTags(): array
    {
        return ['li'];
    }
}
