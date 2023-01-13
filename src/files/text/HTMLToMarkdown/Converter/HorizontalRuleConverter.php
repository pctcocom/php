<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown\Converter;

use pctco\php\files\text\HTMLToMarkdown\ElementInterface;

class HorizontalRuleConverter implements ConverterInterface
{
    public function convert(ElementInterface $element): string
    {
        return "---\n\n";
    }

    /**
     * @return string[]
     */
    public function getSupportedTags(): array
    {
        return ['hr'];
    }
}
