<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown\Converter;

use pctco\php\files\text\HTMLToMarkdown\ElementInterface;

interface ConverterInterface
{
    public function convert(ElementInterface $element): string;

    /**
     * @return string[]
     */
    public function getSupportedTags(): array;
}
