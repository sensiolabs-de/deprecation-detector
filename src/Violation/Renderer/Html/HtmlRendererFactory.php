<?php
namespace SensioLabs\DeprecationDetector\Violation\Renderer\Html;


use SensioLabs\DeprecationDetector\Violation\Renderer\MessageHelper\MessageHelper;
use Symfony\Component\Filesystem\Filesystem;

class HtmlRendererFactory
{
    private $messageHelper;
    private $filesystem;

    public function __construct(MessageHelper $messageHelper, Filesystem $filesystem)
    {
        $this->messageHelper = $messageHelper;
        $this->filesystem = $filesystem;
    }

    public function createHtmlOutputRenderer($outputFile)
    {
        return new HtmlRenderer($this->messageHelper, $this->filesystem, $outputFile);
    }
}