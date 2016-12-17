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

$test4 = file_get_html('http://films.dominiek.be/');
if(!empty($test4)){
    $content = $test4->find(".titel", 0);
    if ($content) {
        $content = htmlentities(preg_replace('/\s+/', ' ', $content->plaintext));
        echo $content;
    }
    
    /*
    //to fetch all hyperlinks from a webpage
    $links = array();
    foreach($test4->find('#div2139 a') as $a) {
        $links[] = $a->href;
    }
    print_r($links);
    */
    
    /*$i = 0;
    foreach ($test4->find('#left > *') as $article) {
        $item['genre'] = $test4->find('div.meerfilm u', $i)->plaintext;
        $item['link'] = $test4->find('a .filmtitel .titel',$i)->plaintext;
        $articles[] = $item;
        $i++;
    }*/
    
    $i = 0;
    foreach ($test4->find('.navlist li') as $article) {
        //**/echo($i);
        $itemi = $test4->find('li a',$i)->plaintext;
        $yes[] = $itemi;
        /*$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
        $baba[] = $item;*/
        $i++;
    }
    
    //print_r($yes);
    
    $i = 0;
    foreach ($test4->find('#left .filmtitel') as $article) {
        echo($i);
        $itemtest = $test4->find('#left .filmtitel .titel',$i)->plaintext;
        //$item['link'] = $test4->find('.filmtitel .titel',$i)->plaintext;
        $baba[] = $itemtest;
        $i++;
    }
    
    print_r($baba);
    
    
    //echo($test4->find('.filmtitel .titel',5)->plaintext);
    
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

print_r($articles);


?>