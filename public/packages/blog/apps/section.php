<?php
use sixlive\ParsedownHighlight as Parsedown;

list($params, $providers) = eQual::announce([
    'description'   => "Returns parsed result of the content of a given section embedded within the blog template",
    'params'        => [
        'section'   => [
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

list($section) = [ $params['section'] ];

// retrieve related template file
$template_path = './blog/'.$params['section'].'/template.html';


if(!is_file($template_path)) {
    throw new Exception("unknown section", QN_ERROR_INVALID_CONFIG);
}


$articles = json_decode(eQual::run('get', 'blog_articles', ['section' => $section]), true);
$html = "<ul>".PHP_EOL;    
foreach($articles as $article) {
    $html .=  "<li><a href=\"/article/$section/$article\">$article</a></li>".PHP_EOL;        
}
$html .= "</ul>".PHP_EOL;    


$template = file_get_contents($template_path);

$Parsedown = new Parsedown();
$html = str_replace(
    ['{{ content }}', '{{ section }}'], 
    [$Parsedown->text($html), $section],
    $template);

// and send the resulting array as a HTTP response
$providers['context']->httpResponse()->body($html)->send();