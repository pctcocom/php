<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown\Converter;

use pctco\php\files\text\HTMLToMarkdown\Configuration;
use pctco\php\files\text\HTMLToMarkdown\ConfigurationAwareInterface;
use pctco\php\files\text\HTMLToMarkdown\ElementInterface;

class DivConverter implements ConverterInterface, ConfigurationAwareInterface
{
    /** @var Configuration */
    protected $config;

    public function setConfig(Configuration $config): void
    {
        $this->config = $config;
    }

    public function convert(ElementInterface $element): string
    {
        if ($this->config->getOption('strip_tags', false)) {
            return $element->getValue() . "\n\n";
        }

        return \html_entity_decode($element->getChildrenAsString());
    }

    /**
     * @return string[]
     */
    public function getSupportedTags(): array
    {
        return ['div'];
    }
}
