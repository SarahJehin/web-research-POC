<?php

include 'simple_html_dom.php';

// DOM opslaan in variabele
/*$html = file_get_html('https://www.buzzfeed.com/julesdarmanin/censored-volleyball-photo-hoax?utm_term=.qgVR4AobZJ#.obNyOZ5k8W');

if(!empty($html)){
    $titletag = $html->find("h1", 0);
        if ($titletag) {
            $titletag = htmlentities(preg_replace('/\s+/', ' ', $titletag->plaintext));
            echo $titletag;
        }
}
*/
/*
$test2 = file_get_html('http://www.bbc.com/news/world-middle-east-38350658');

if(!empty($test2)){
    $content = $test2->find(".story-body__introduction", 0);
    if ($content) {
            $content = htmlentities(preg_replace('/\s+/', ' ', $content->plaintext));
            echo $content;
        }
}

$test3 = file_get_html('http://www.bbc.co.uk/search?q=ryanair+free&sa_f=search-product&scope=');

if(!empty($test3)){
    $content = $test3->find("h1", 0);
    if ($content) {
        $content = htmlentities(preg_replace('/\s+/', ' ', $content->plaintext));
        echo $content;
    }
}
*/
/*
$test4 = file_get_html('http://films.dominiek.be/');
if(!empty($test4)){
    $content = $test4->find(".titel", 0);
    if ($content) {
        $content = htmlentities(preg_replace('/\s+/', ' ', $content->plaintext));
        //echo $content;
    }
    
    /*
    //to fetch all hyperlinks from a webpage
    $links = array();
    foreach($test4->find('#div2139 a') as $a) {
        $links[] = $a->href;
    }
    print_r($links);
    
    $i = 0;
    foreach ($test4->find('#left > *') as $article) {
        $item['genre'] = $test4->find('div.meerfilm u', $i)->plaintext;
        $item['link'] = $test4->find('a .filmtitel .titel',$i)->plaintext;
        $articles[] = $item;
        $i++;
    }*//*
    
    $i = 0;
    foreach ($test4->find('.navlist li') as $article) {
        $itemi = $test4->find('li a',$i)->plaintext;
        $yes[] = $itemi;
        /*$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
        $baba[] = $item;*//*
        $i++;
    }
    
    //print_r($yes);
    
    $i = 0;
    foreach ($test4->find('#left .filmtitel') as $article) {
        //echo($i);
        $itemtest = $test4->find('#left .filmtitel .titel',$i)->plaintext;
        //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
        $baba[] = $itemtest;
        $i++;
    }
    
    //print_r($baba);
    
    
    //echo($test4->find('.filmtitel .titel',5)->plaintext);
    
}


$test5 = file_get_html('http://www.bbc.co.uk/search?q=ryanair+free&sa_f=search-product&scope=');
if(!empty($test5)){
    
    $i = 0;
    foreach ($test5->find('ol.results li h1') as $article) {
        echo($i);
        $itemtest2 = $test5->find('ol.results li h1 a',$i)->plaintext;
        //echo($itemtest2);
        //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
        $bababa[] = $itemtest2;
        $i++;
    }
    
    print_r($bababa);
    
    echo($test5->find('ol.results li h1 a', 5)->plaintext);
}


$str = '<html>
<div class="allItems">
   <div class="Item" id="12345">
      <div class="ItemName">Tom</div>
      <div class="ItemAge">34</div>
      <div class="ItemGender">male</div>
   </div>
   <div class="Item" id="17892">
      <div class="ItemName">Dick</div>
      <div class="ItemAge">23</div>
      <div class="ItemGender">male</div>
   </div>
   <div class="Item" id="98776">
      <div class="ItemName">Harry</div>
      <div class="ItemAge">65</div>
      <div class="ItemGender">male</div>
   </div>
</div>
</html>';

$html22 = str_get_html($str); //this pulls the html code fine

//this is where my array constructions does not work
$i = 0;
foreach ($html22->find('div.allItems > *') as $article) {
    $item['name'] = $html22->find('div.ItemName', $i)->plaintext;
    $item['age'] = $html22->find('div.ItemAge',$i)->plaintext;
    $item['gender'] = $html22->find('div.ItemGender', $i)->plaintext;
    $articles[] = $item;
    $i++;
}

//print_r($articles);*/

/********************************************/

if(isset($_POST['submit'])) {
    var_dump($_POST['searchwords']);
    //var_dump(str_replace(" ", "+", $_POST['searchwords']));
    /*$search_string = str_replace(" ", "+", $_POST['searchwords']);
    var_dump('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    $search_test = file_get_html('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    
    if(!empty($search_test)){

        $i = 0;
        foreach ($search_test->find('ol.results li h1') as $article) {
            echo($i);
            $title = $search_test->find('ol.results li h1 a',$i)->plaintext;
            //echo($itemtest2);
            //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);

    }*/
    search_bbc($_POST['searchwords']);
    search_the_independent($_POST['searchwords']);
    search_snopes($_POST['searchwords']);
    //search_nytimes($_POST['searchwords']);
    //search_washington_post($_POST['searchwords']);
    
}


function search_bbc($searchwords) {
    $search_string = str_replace(" ", "+", $searchwords);
    //var_dump('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    $search_test = file_get_html('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    
    if(!empty($search_test)){

        $i = 0;
        foreach ($search_test->find('ol.results li h1') as $article) {
            $title = $search_test->find('ol.results li h1 a',$i)->plaintext;
            //echo($itemtest2);
            //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);

    }
}

function search_the_independent($searchwords) {
    $search_string = str_replace(" ", "%2520", $searchwords);
    //var_dump('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    $search_test = file_get_html('http://www.independent.co.uk/search/site/' . $search_string);
    
    if(!empty($search_test)){

        $i = 0;
        foreach ($search_test->find('ol.search-results li h2') as $article) {
            $title = $search_test->find('ol.search-results li h2 a',$i)->plaintext;
            //echo($itemtest2);
            //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);

    }
}

function search_snopes($searchwords) {
    $search_string = str_replace(" ", "+", $searchwords);
    //var_dump('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    $search_test = file_get_html('http://www.snopes.com/search/?q=' . $searchwords);
    
    if(!empty($search_test)){

        $i = 0;
        foreach ($search_test->find('.search-results .item') as $article) {
            $title = $search_test->find('.search-results .item h3 a',$i)->plaintext;
            //echo($itemtest2);
            //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);

    }
}

snopes_result('http://www.snopes.com/trump-sends-unpresidented-tweet/');

function snopes_result($url) {
    $page = file_get_html($url);
    
    if(!empty($page)){
        $true_false = $page->find('.claim-old span span', 0)->plaintext;
        echo($true_false);
    }
}



function search_nytimes($searchwords) {
    $search_string = str_replace(" ", "+", $searchwords);
    var_dump('http://query.nytimes.com/search/sitesearch/?action=click&contentCollection&region=TopBar&WT.nav=searchWidget&module=SearchSubmit&pgtype=Homepage#/' . $search_string .'/');
    $search_test = file_get_html('http://query.nytimes.com/search/sitesearch/?action=click&contentCollection&region=TopBar&WT.nav=searchWidget&module=SearchSubmit&pgtype=Homepage#/' . $search_string .'/');
    
    if(!empty($search_test)){

        $i = 0;
        foreach ($search_test->find('.searchResults ol li') as $article) {
            echo($i);
            $title = $search_test->find('ol.searchResultsList li h3 a',$i)->plaintext;
            //echo($itemtest2);
            //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);
        
        echo($search_test->find('.filterList li a', 0)->plaintext);

    }
}

function search_washington_post($searchwords) {
    $search_string = str_replace(" ", "%20", $searchwords);
    var_dump('https://www.washingtonpost.com/newssearch/?query=' . $search_string);
    $search_test = file_get_html('https://www.washingtonpost.com/newssearch/?query=' . $search_string);
    
    if(!empty($search_test)){
/*
        $i = 0;
        foreach ($search_test->find('.searchResults ol li') as $article) {
            echo($i);
            $title = $search_test->find('ol.searchResultsList li h3 a',$i)->plaintext;
            //echo($itemtest2);
            //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
            $results_search[] = $title;
            $i++;
        }

        print_r($results_search);*/
        
        echo('comon ' . $search_test->find('.pb-search-text.ng-binding', 0)->plaintext);

    }
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Find results</title>
</head>
<body>
    
    
    <main>
        <div>
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




