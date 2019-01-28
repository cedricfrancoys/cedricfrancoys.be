<?php

list($params, $providers) = eQual::announce([
    'description'   => "Returns a page listing the blog sections",
    'response'      => [
        'content-type'  => 'text/html',
        'charset'       => 'UTF-8'
    ],
    'providers'     => ['context']
]);

$sections = json_decode(eQual::run('get', 'blog_sections'), true);

$template_path = './blog/'.$params['section'].'/template.html';

if(!is_file($template_path)) {
    throw new Exception("unknown article", QN_ERROR_INVALID_CONFIG);
}

$template = file_get_contents($template_path);

$list = '';
foreach($sections as $section) {    
    $articles = json_decode(eQual::run('get', 'blog_articles', ['section' => $section]), true);
    $list .= "<h3><a href=\"/section/$section\">$section</h3>".PHP_EOL;
    $list .= "<ul>".PHP_EOL;    
    foreach($articles as $article) {
        $list .=  "<li><a href=\"/article/$section/$article\">$article</a></li>".PHP_EOL;        
    }
    $list .= "</ul>".PHP_EOL;    
}

$html = str_replace(
    ['{{ content }}', '{{ section }}'], 
    [$list, ''],
    $template);


// and send the resulting array as a HTTP response
$providers['context']->httpResponse()->body($html)->send();