<?php
// Parsedown uses PSR-0 for autoloading
// use erusev\parsedown\Parsedown;

$test = "Welcome to the demo:

1. Write Markdown text on the left
2. Hit the __Parse__ button or `⌘ + Enter`
3. See the result to on the right
";
$Parsedown = new Parsedown();

echo $Parsedown->text($test);