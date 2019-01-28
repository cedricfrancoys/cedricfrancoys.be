<?php

list($params, $providers) = eQual::announce([
    'description'   => "Provides the list of available articles within a given section",
    'params'        => [
        'section'   => [
            'type'      => 'string',
            'required'  => true
        ]
    ],
    'response'      => [
        'content-type'  => 'application/json',
        'charset'       => 'UTF-8'
    ],
    'providers'     => ['context']
]);

// retrieve all articles under the given section
$files = array_map(function ($a) {return explode('.', basename($a))[0];}, array_filter(glob('./blog/'.$params['section'].'/*.md'), 'is_file'));

if(!is_dir('./blog/'.$params['section'])) {
    throw new Exception("unknown section", QN_ERROR_INVALID_PARAM);
}

// and send the resulting array as a HTTP response
$providers['context']->httpResponse()->body($files)->send();