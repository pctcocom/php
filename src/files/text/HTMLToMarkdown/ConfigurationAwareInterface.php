<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown;

interface ConfigurationAwareInterface
{
    public function setConfig(Configuration $config): void;
}
