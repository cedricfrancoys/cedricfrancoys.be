<?php

list($params, $providers) = eQual::announce([
    'description'   => "Provides the list of available sections in the blog",
    'response'      => [
        'content-type'  => 'application/json',
        'charset'       => 'UTF-8'
    ],
    'providers'     => ['context']
]);

// retrieve all directories under the 'blog' repository (hidden directories will be ignored)
$dirs = array_map(function ($a) {return basename($a);}, array_filter(glob('./blog/*'), 'is_dir'));
// and send the resulting array as a HTTP response
$providers['context']->httpResponse()->body($dirs)->send();