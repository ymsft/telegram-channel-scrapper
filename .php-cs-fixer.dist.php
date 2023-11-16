<?php

$finder = (new PhpCsFixer\Finder())->in(['src', 'tests']);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PER-CS' => true,
        '@PHP82Migration' => true,
    ])
    ->setFinder($finder);