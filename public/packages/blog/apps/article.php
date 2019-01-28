<?php
use sixlive\ParsedownHighlight as Parsedown;

list($params, $providers) = eQual::announce([
    'description'   => "Returns parsed result of an article embedded within the blog template",
    'params'        => [
        'section'   => [
            'type'          => 'string',
            'required'      => true
        ],
        'article'   => [
            'description'   => 'article slug',
            'type'          => 'string',
            'required'      => true
        ]        
    ],
    'response'      => [
        'content-type'  => 'text/html',
        'charset'       => 'UTF-8'
    ],
    'providers'     => ['context']
]);

// retrieve related article file
$article_path = './blog/'.$params['section'].'/'.$params['article'].'.md';
$template_path = './blog/'.$params['section'].'/template.html';

if(!is_file($article_path)) {
    throw new Exception("unknown article", QN_ERROR_INVALID_PARAM);
}

if(!is_file($template_path)) {
    throw new Exception("unknown article", QN_ERROR_INVALID_CONFIG);
}

$timestamp = filemtime($article_path);

$content = file_get_contents($article_path);
$template = file_get_contents($template_path);

$Parsedown = new Parsedown();
$html = str_replace(
    ['{{ content }}', '{{ date }}', '{{ section }}'], 
    [$Parsedown->text($content), date("F j, Y", $timestamp), $params['section']],
    $template);

// and send the resulting array as a HTTP response
$providers['context']->httpResponse()->body($html)->send();