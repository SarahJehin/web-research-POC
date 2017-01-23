<?php

$pathToPhantomJs = 'C:/Users/Sarah/Documents/Sarah/MCT3/Web Research/A-POC/phantomJS/phantomjs-2.1.1-windows/bin/phantomjs';

$pathToJsScript = 'C:/xampp/htdocs/MT3/web_research_POC/code/phantom_js_test/test_search.js';

//$blab = sprintf('"%s" %s > phantom.txt', $pathToPhantomJs,  $pathToJsScript);
//echo($blab);

$link_to_get_results_from = 'https://www.washingtonpost.com/newssearch/?query=brazil%20tribe';

exec(sprintf('"%s" %s %s > phantom.txt', $pathToPhantomJs,  $pathToJsScript, $link_to_get_results_from), $out);

//echo($stdOut);

$fileContents = file_get_contents('phantom.txt');

//echo $fileContents;


$article_links = explode(',' , $fileContents);
//var_dump($article_links);

/*
echo("<p>proceed</p>");

$first_split = preg_split("/http/", $fileContents,2);

$first_split_no_comma = rtrim($first_split[1], ',');
$second_split = preg_split("/\s/",  $first_split_no_comma, 2);
$hrefs = explode(",", $second_split[1]);
var_dump($hrefs);
*/

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scrape angular site</title>
</head>
<body>
    
    <h2>Links of articles to scrape:</h2>
    
    <p>The 5 first urls of articles when searching for <strong>"brazil tribe".</strong></p>
    
    <ul>
        <?php if(isset($article_links)) :
        foreach($article_links as $link) : ?>
        <li><?php echo($link) ?></li>
        <?php endforeach; endif; ?>
    </ul>
    
    
</body>
</html>