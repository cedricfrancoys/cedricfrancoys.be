<?php

list($params, $providers) = eQual::announce([
    'description'   => "Displays the list of all articles within a given section",
    'params'        => [
        'section'   => [
            'type'      => 'string',
            'required'  => true
        ]
    ],
    'response'      => [
        'content-type'  => 'text/html',
        'charset'       => 'UTF-8'
    ],
    'providers'     => ['context']
]);

$data = json_decode(eQual::run('get', 'blog_articles', ['section' => $params['section']]), true);

if(isset($data['errors'])) {
    foreach($data['errors'] as $name => $message) {
        throw new Exception($message, qn_error_code($name));
    }
}

$result = "<ul style=\"list-style-type: none; padding-left: 0;\">".PHP_EOL;
foreach($data as $article) {
        $result .=  "<li><a href=\"/article/{$params['section']}/$article\">$article</a></li>".PHP_EOL;
}
$result .= "</ul>".PHP_EOL;

$providers['context']->httpResponse()->body($result)->send();