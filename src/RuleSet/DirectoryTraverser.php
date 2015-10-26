<?php

namespace SensioLabs\DeprecationDetector\RuleSet;

use SensioLabs\DeprecationDetector\FileInfo\PhpFileInfo;
use SensioLabs\DeprecationDetector\Finder\ParsedPhpFileFinder;
use SensioLabs\DeprecationDetector\Parser\DeprecationParser;

/**
 * Class Traverser.
 *
 * @author Christopher Hertel <christopher.hertel@sensiolabs.de>
 */
class DirectoryTraverser
{
    /**
     * @var DeprecationParser
     */
    private $finder;

    /**
     * @param ParsedPhpFileFinder $finder
     */
    public function __construct(ParsedPhpFileFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @param string $path
     *
     * @return null|RuleSet
     */
    public function traverse($path)
    {
        /* @TODO remove $quiet from DirectoryTraverser */
        $files = $this->finder->in($path);

        $ruleSet = new RuleSet();
        $hasDeprecations = false;
        foreach ($files as $i => $file) {
            /** @var PhpFileInfo $file */
            if ($file->hasDeprecations()) {
                $ruleSet->merge($file);
                $hasDeprecations = true;
            }
        }

        return ($hasDeprecations ? $ruleSet : null);
    }
}