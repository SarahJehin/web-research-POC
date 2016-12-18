<?php

include 'simple_html_dom.php';


if(isset($_POST['submit'])) {
    $url = search_snopes($_POST['searchwords']);
    open_article_and_check($url);
}



function search_snopes($searchwords) {
    $search_string = str_replace(" ", "+", $searchwords);
    $search_test = file_get_html('http://www.snopes.com/search/?q=' . $searchwords);
    
    if(!empty($search_test)){

        /*
        $i = 0;
        foreach ($search_test->find('.search-results .item') as $article) {
            $result = $search_test->find('.search-results .item h3 a',$i);
            $title = $result->plaintext;
            $url_to_open = $result->href;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);
        */
        
        $url = $search_test->find('.search-results .item h3 a', 0)->href;
        
        //bovenstaande geeft /trump-sends-unpresidented-tweet/ , maar moet omgevormd worden naar url hieronder
        //http://www.snopes.com/trump-sends-unpresidented-tweet/
        $url = 'http://www.snopes.com' . $url;
        //print_r($url);
        return $url;

    }
}

function open_article_and_check($url) {
    
    $page = file_get_html($url);
    
    if(!empty($page)){
        $true_false = $page->find('.claim-old span span', 0)->plaintext;
        echo($true_false);
    }
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Open search result</title>
</head>
<body>
    
    <main>
        <div>
            <p>Zoek naar iets op snopes.com, open het eerste zoekresultaat en geef of het true of false is.</p>
            
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div>
                    <label for="searchwords">Zoekwoorden:</label>
                    <input type="text" name="searchwords" id="searchwords">
                </div>
                <input type="submit" name="submit" value="Zoek">
            </form>
            
        </div>
    </main>
    
    
</body>
</html>