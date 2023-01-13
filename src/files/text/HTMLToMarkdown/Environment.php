<?php

declare(strict_types=1);

namespace pctco\php\files\text\HTMLToMarkdown;

use pctco\php\files\text\HTMLToMarkdown\Converter\BlockquoteConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\CodeConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\CommentConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\ConverterInterface;
use pctco\php\files\text\HTMLToMarkdown\Converter\DefaultConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\DivConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\EmphasisConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\HardBreakConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\HeaderConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\HorizontalRuleConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\ImageConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\LinkConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\ListBlockConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\ListItemConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\ParagraphConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\PreformattedConverter;
use pctco\php\files\text\HTMLToMarkdown\Converter\TextConverter;

final class Environment
{
    /** @var Configuration */
    protected $config;

    /** @var ConverterInterface[] */
    protected $converters = [];

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = new Configuration($config);
        $this->addConverter(new DefaultConverter());
    }

    public function getConfig(): Configuration
    {
        return $this->config;
    }

    public function addConverter(ConverterInterface $converter): void
    {
        if ($converter instanceof ConfigurationAwareInterface) {
            $converter->setConfig($this->config);
        }

        foreach ($converter->getSupportedTags() as $tag) {
            $this->converters[$tag] = $converter;
        }
    }

    public function getConverterByTag(string $tag): ConverterInterface
    {
        if (isset($this->converters[$tag])) {
            return $this->converters[$tag];
        }

        return $this->converters[DefaultConverter::DEFAULT_CONVERTER];
    }

    /**
     * @param array<string, mixed> $config
     */
    public static function createDefaultEnvironment(array $config = []): Environment
    {
        $environment = new static($config);

        $environment->addConverter(new BlockquoteConverter());
        $environment->addConverter(new CodeConverter());
        $environment->addConverter(new CommentConverter());
        $environment->addConverter(new DivConverter());
        $environment->addConverter(new EmphasisConverter());
        $environment->addConverter(new HardBreakConverter());
        $environment->addConverter(new HeaderConverter());
        $environment->addConverter(new HorizontalRuleConverter());
        $environment->addConverter(new ImageConverter());
        $environment->addConverter(new LinkConverter());
        $environment->addConverter(new ListBlockConverter());
        $environment->addConverter(new ListItemConverter());
        $environment->addConverter(new ParagraphConverter());
        $environment->addConverter(new PreformattedConverter());
        $environment->addConverter(new TextConverter());

        return $environment;
    }
}
