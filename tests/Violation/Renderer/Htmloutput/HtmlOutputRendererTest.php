<?php

namespace SensioLabs\DeprecationDetector\Tests\Violation\Renderer\HtmlOutput;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit_Framework_TestCase;
use Prophecy\Argument;
use SensioLabs\DeprecationDetector\Violation\Renderer\Html\HtmlRenderer;

class HtmlOutputRendererTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var  vfsStreamDirectory
     */
    private $root;

    /**
     * set up test environment
     */
    public function setUp()
    {
        $this->root = vfsStream::setup('exampleDir');
    }

    public function testRenderViolations()
    {
        $fileSystem = $this->prophesize('Symfony\Component\Filesystem\Filesystem');
        $fileSystem->mkdir('exampleDir');

        $fileInfo = $this->prophesize('SensioLabs\DeprecationDetector\FileInfo\PhpFileInfo');
        $fileInfo->getPathname()->willReturn('just/a/path');

        $violation = $this->prophesize('SensioLabs\DeprecationDetector\Violation\Violation');
        $violation->getFile()->willReturn($fileInfo->reveal());
        $violation->getLine()->willReturn('12');
        $violation->getComment()->willReturn('Just a comment');

        $messageHelper = $this->prophesize('SensioLabs\DeprecationDetector\Violation\Renderer\MessageHelper\MessageHelper');
        $messageHelper->getViolationMessage($violation->reveal())->willReturn('testMethod');

        $renderer = new HtmlRenderer(
            $messageHelper->reveal(),
            $fileSystem->reveal(),
            vfsStream::url('exampleDir/output.html')
        );

        $renderer->renderViolations(array($violation->reveal()));

        $this->assertFileExists(vfsStream::url('exampleDir/output.html'));

        $fileOutPut = file_get_contents(vfsStream::url('exampleDir/output.html'));

        $this->assertContains('testMethod', $fileOutPut);
        $this->assertContains('12', $fileOutPut);
        $this->assertContains('Just a comment', $fileOutPut);
    }

}