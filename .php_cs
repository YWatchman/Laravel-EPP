<?php
return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_empty_statement' => true,
        'phpdoc_summary' => true,
        'ordered_class_elements' => true,
    ]);

