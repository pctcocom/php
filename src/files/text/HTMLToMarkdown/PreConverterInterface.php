<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown;

interface PreConverterInterface
{
    public function preConvert(ElementInterface $element): void;
}
