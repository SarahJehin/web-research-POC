<?php

include 'simple_html_dom.php';

//The most common English words should be filtered from the article (source: https://en.wikipedia.org/wiki/Most_common_words_in_English)
$most_common_english_words = array("have", "with", "this", "that", "were", "from", "they", "will", "would", "there", "their", "what", "about", "which", "when", "make", "like", "time", "just", "know", "tak", "into", "your", "good", "some", "could", "them", "than", "then", "look", "only", "come", "over", "think", "also", "back", "after", "these", "here", "made", "like", "almost", "later", "told", "said", "been", "didn", "most", "thing", "word", "things");

$critical_words = array("false", "hoax", "fake");

//trustworthiness level starts at 50, it decreases when [I don't know yet...] and increases when an article was found on trustworthty media
$trustworthiness = 50;

//get important words from article
//check search results on snopes
//open first result
//check similarities in first result --> do the important words come back in the result article (if yes, this means the result is relevant)
    //yes --> check whether it's true or false
    //no --> open next search result


if(isset($_POST['submit'])) {
    $top_six = get_key_words($_POST['article'], 6);
    $top_ten = get_key_words($_POST['article'], 10);
    $top_twenty = get_key_words($_POST['article'], 20);
    
    $searchwords = $_POST['searchwords'];
    
    if($_POST['do_what'] == 'searchwords' || $_POST['do_what'] == 'both') {
        $computer_generated_searchwords = find_search_words($_POST['title'], $top_ten);
        $searchwords = implode(" ", $computer_generated_searchwords);
        $searchwords = preg_replace('/[^A-Za-z0-9\-\s]/', '', $searchwords);
        echo("<div>Zoekwoorden zijn: " . $searchwords . "</div>");
    }
    if($_POST['do_what'] == 'article_reliability' || $_POST['do_what'] == 'both') {


        //search_google($_POST['searchwords']);


        //first search snopes
        $url = search_snopes($searchwords, 0);
        echo($url);
        if($url) {
            $true_false = open_article_and_check($url, $top_six, 0);
            if(strtolower($true_false) && strtolower($true_false) != "not in 5 first results") {
                $trustworthiness = $trustworthiness + 30;
            }
            else if(!strtolower($true_false)) {
                $trustworthiness = $trustworthiness - 30;
            }
        }
        else {
            echo("no results found");
        }


        $times_found = 0;
        //if snopes doesn't have a result, search other newssites
        $extra_points = search_bbc($searchwords);
        $trustworthiness = $trustworthiness + $extra_points;
        if($extra_points > 0) {
            $times_found++;
        }
        else {
            $trustworthiness = $trustworthiness - 5;
        }
        //echo("snopes: " . $true_false . " en betrouwbaarheid: " . $trustworthiness);
        $extra_points2 = search_the_independent($searchwords);
        $trustworthiness = $trustworthiness + $extra_points2;
        if($extra_points2 > 0) {
            $times_found++;
        }
        else {
            $trustworthiness = $trustworthiness - 5;
        }
        echo("snopes: " . $true_false . " en betrouwbaarheid: " . $trustworthiness);
        echo("<div>BBC points: " . $extra_points . " and Independent points: ". $extra_points2 . "</div>");
        echo("<div>articles found: " . $times_found . "</div>");


        /*
        $limited_text_for_check = substr($_POST["article"], 0, 5000);

        $limited_text_for_check = preg_replace('/\s+/', '+', $limited_text_for_check);
        $limited_text_for_check = str_replace('?', '%3F', $limited_text_for_check);
        $limited_text_for_check = str_replace(',', '%2C', $limited_text_for_check);

        $data = json_decode(file_get_contents('https://api.textgears.com/check.php?text=' . $limited_text_for_check . '&key=DEMO_KEY'), true);

        //convert data to array
        $data = (array)$data;
        var_dump($data);

        //get errors count
        $errors_count = count((array)$data['errors']);

        $api_score = $data['score'];
        if($api_score >= 0 && $api_score <= 40) {
            $down_score = 30;
        }
        else if($api_score > 40 && $api_score <= 60) {
            $down_score = 20;
        }
        else if($api_score > 60 && $api_score <= 75) {
            $down_score = 5;
        }
        else {
            $down_score = 0;
        }

        $trustworthiness = $trustworthiness-$down_score;

        print_r("<div>Api score: " . $api_score . "</div>");
        print_r("<div>end score: " . $trustworthiness . "</div>");


        //search google for hoax articles open the first 3
        */
    
    }
    
    
}



function search_snopes($searchwords, $result_number) {
    $search_string = str_replace(" ", "+", $searchwords);
    $search_test = file_get_html('http://www.snopes.com/search/?q=' . $searchwords);
    
    if(!empty($search_test)){
        
        if(isset($search_test->find('.search-results .item h3 a', $result_number)->href)) {
            $url = $search_test->find('.search-results .item h3 a', $result_number)->href;
            //bovenstaande geeft /trump-sends-unpresidented-tweet/ , maar moet omgevormd worden naar url hieronder
            //http://www.snopes.com/trump-sends-unpresidented-tweet/
            $url = 'http://www.snopes.com' . $url;
            //print_r($url);
            return $url;
        }
        else {
            return false;
        }

    }
}

function open_article_and_check($url, $top_words, $result_number) {
    
    $page = file_get_html($url);
    
    if(!empty($page)){
        
        $string_to_search_in = "";
        
        //put the whole article in one long string to search in
        $i = 0;
        foreach ($page->find('.content p') as $paragraph) {
            $result = $page->find('.content p', $i)->plaintext;
            //echo($result);
            $string_to_search_in = $string_to_search_in . $result;
            $i++;
        }
        
        $j = 0;
        foreach ($page->find('div[itemprop="reviewBody"] p') as $paragraph2) {
            $result2 = $page->find('div[itemprop="reviewBody"] p', $j)->plaintext;
            //echo($result2);
            $string_to_search_in = $string_to_search_in . $result2;
            $j++;
        }
        
        $k = 0;
        foreach ($page->find('div[itemprop="reviewBody"] blockquote p') as $paragraph3) {
            $result3 = $page->find('div[itemprop="reviewBody"] blockquote p', $k)->plaintext;
            //echo($result3);
            $string_to_search_in = $string_to_search_in . $result3;
            $k++;
        }
        
        //echo($string_to_search_in);
        $article_relevant = 0;
        
        $string_to_search_in = str_replace('"', "", $string_to_search_in);
        
        
        foreach($top_words as $top_word) {
            $position = strpos($string_to_search_in , $top_word);
            if($position) {
                $article_relevant++;
            }
        }
        
        echo("<div>article relevance : " . $article_relevant . "</div>");
        if($article_relevant >= 3) {
            echo("article seems to be relevant and the result on snopes was ");
            $true_false = $page->find('.claim-old span span', 0)->plaintext;
            echo($true_false);
            return $true_false;
        }
        else {
            //echo("article was not relevant, let's try the next result");
            $result_number++;
            if($result_number < 5) {
                //$next_result = $search_test->find('.search-results .item h3 a', $result_number)->href;
                //$next_url = 'http://www.snopes.com' . $next_result;
                
                $next_url = search_snopes($_POST['searchwords'], $result_number);
                //echo("next url: " .$next_url);
                if($next_url) {
                    return open_article_and_check($next_url, $top_words, $result_number);
                }
                else {
                    return "not in 5 first results";
                }
            }
            else {
                return "not in 5 first results";
            }
        }
        
        
    }
}


//bbc
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
            
            $url_to_open = $search_test->find('ol.results li h1 a',$i)->href;
            
            $article_found = check_similarity_bbc($url_to_open);
            
            if($article_found) {
                //betrouwbaarheid mag ++ gedaan worden
                return $article_found;
                //break;
            }
            
            $i++;
        }
        
        //if no match was found, return 0
        return 0;
    }
}

//checks how many of the most important words of the original article match the article from the search result.
//returns the amount of matches
function check_similarity_bbc($url) {
    global $critical_words;
    
    //open the article and check whether it's similar enough
    $page = file_get_html($url);
    
    if(!empty($page)){
        
        $string_to_search_in = "";
        
        //bbc nog probleem dat de opmaak van artikels niet altijd hetzelfde is...
        //put the whole article in one long string to search in
        $i = 0;
        foreach ($page->find('.story-body__inner p') as $paragraph) {
            $result = $page->find('.story-body__inner p', $i)->plaintext;
            //echo($result);
            $string_to_search_in = $string_to_search_in . $result;
            $i++;
        }
        //echo($string_to_search_in);
        $article_relevant = true;
        
        $string_to_search_in = str_replace('"', "", $string_to_search_in);
        
        //count is the amount of top words occur in the article found
        $count = 0;
        
        $top_words = get_key_words($_POST['article'], 20);
        
        foreach($top_words as $top_word) {
            //print_r($top_word);
            $position = strpos($string_to_search_in , $top_word);
            if($position) {
                $count++;
            }
        }
        
        
        echo($count);
        if($count > 10) {
            echo("success !!");
            //var_dump($critical_words);
            foreach($critical_words as $critical_w) {
                if(strpos($string_to_search_in , $critical_w)) {
                    //if the word hoax, false or fake exists, score minus
                    $count = (-20);
                }
            }
            
            return $count;
        }
        else {
            echo("no match, try the next article...");
            return false;
        }
        
    }
}


//search the independent
function search_the_independent($searchwords) {
    $search_string = str_replace(" ", "%2520", $searchwords);
    //var_dump('http://www.bbc.co.uk/search?q=' . $search_string . '&sa_f=search-product&scope=');
    $search_test = file_get_html('http://www.independent.co.uk/search/site/' . $search_string);
    
    if(!empty($search_test)){

        $i = 0;
        foreach ($search_test->find('ol.search-results li h2') as $article) {
            
            $url_to_open = $search_test->find('ol.search-results li h2 a',$i)->href;
            
            $article_found = check_similarity_independent($url_to_open);
            
            if($article_found) {
                //betrouwbaarheid mag ++ gedaan worden
                return $article_found;
                //break;
            }
            $i++;
        }
        
        //if no match was found, return 0
        return 0;


    }
}

function check_similarity_independent($url) {
    $page = file_get_html($url);
    
    if(!empty($page)){
        
        $string_to_search_in = "";
        
        //independent ook nog probleem dat de opmaak van artikels niet altijd hetzelfde is... grr... (soms .field-item.even en soms div[itemprop="articleBody"])
        //put the whole article in one long string to search in
        $i = 0;
        foreach ($page->find('div[itemprop="articleBody"] p') as $paragraph) {
            $result = $page->find('div[itemprop="articleBody"] p', $i)->plaintext;
            $string_to_search_in = $string_to_search_in . $result;
            $i++;
        }
        //echo($string_to_search_in);
        $article_relevant = true;
        
        $string_to_search_in = str_replace('"', "", $string_to_search_in);
        
        //count is the amount of top words occur in the article found
        $count = 0;
        
        $top_words = get_key_words($_POST['article'], 20);
        
        foreach($top_words as $top_word) {
            $position = strpos($string_to_search_in , $top_word);
            if($position) {
                $count++;
            }
        }
        echo($count);
        if($count > 10) {
            echo("success !!");
            echo($url);
            return $count;
        }
        else {
            echo("no match, try the next article...");
            return false;
        }
        
    }
}


function search_google($searchwords) {
    echo("testje");
    $search_string = str_replace(" ", "%20", $searchwords);
    $search_string = $search_string . '%20hoax';
    echo($search_string);
    $search_test = file_get_html('https://www.google.be/webhp?sourceid=chrome-instant&ion=1&espv=2&ie=UTF-8#q=' . $search_string);
    //echo($search_test);
    if(!empty($search_test)){
        
        $i = 0;
        
        $testtt = $search_test->find('.hdtb-mitem hdtb-imb a', 2)->plaintext;
        echo("dit is testttt: " . $testtt);
        
        foreach ($search_test->find('._NId .g .rc h3') as $article) {
            
            $title = $search_test->find('._NId .g .rc h3 a',$i)->plaintext;
            $desc = $search_test->find('._NId .g .rc .st',$i)->plaintext;
            
            print_r("<div><h4>" . $title . "</h4>");
            print_r("<p>" . $desc . "</p></div>");
            
            $i++;
            
            if($i > 5) {
                break;
            }
        }


    }
}




// get most important words from article

function get_key_words($text, $amount) {
    
    global $most_common_english_words;
    
    //step 1 - get uppercase words from article (ignore words shorter than 4 characters)
    $most_important_words_arr = get_uppercase_words($text);
    //get uppercase words with count
    $most_important_words_with_count = get_arr_with_count($most_important_words_arr);
    
    //step 2 - create array from the text (remove spaces, commas and points)
    $article_arr  = explode(" ", str_replace(array("’", PHP_EOL), " ", str_replace(array('.', ',', '?'), "", $text)));

    //step 3 - remove array with most important uppercases from article_arr
    $article_arr = remove_matches($article_arr, $most_important_words_arr);

    //step 4 - remove all most common english words (e.g. like, have, almost, maybe, ...)
    $article_arr = remove_most_common_words($article_arr, $most_common_english_words);

    //step 5 - get words with their amount of occurrences (and only if the word is longer than 3 characters)
    $article_word_count = get_arr_with_count($article_arr);

    //step 6 - merge the count arrays
    $merged_count_arr = array_merge($most_important_words_with_count, $article_word_count);

    //step 7 - calculate importance based on number of occurrencies and whether or not starting with an uppercase
    $importance_weight_arr = get_words_with_importance($merged_count_arr);
    
    //step 8 - get the most important words in order of importance and limit the results to the given amount of key_words
    $top_words = get_top_words($importance_weight_arr, $amount);
    
    return $top_words;
    
}

/* USED FUNCTIONS */

function get_uppercase_words($text) {
    $array_longer_than_three = array();
    $n_words = preg_match_all('/([A-Z])\w+/', $text, $match_arr);
    
    foreach($match_arr[0] as $word) {
        //only add words with a length greater than 3 (so 'to', 'i' 'the', 'in', ... will be ignored)
        if(strlen($word) > 4) {
            array_push($array_longer_than_three, $word);
        }
    }
    
    return $array_longer_than_three;
}

function remove_most_common_words($array_to_check, $array_most_common) {
    foreach($array_to_check as $word) {
        if (in_array($word, $array_most_common)) {
            $array_to_check = array_diff($array_to_check, (array)$word);
        }
    }
    return $array_to_check;
}

//returns: array with count of each unique word (and if the word is longer than 3 characters)
function get_arr_with_count ($all_words_arr) {
    $with_words_count_arr = array();

    foreach($all_words_arr as $word) {
        //only add words with a length greater than 3 (so 'to', 'i' 'the', 'in', ... will be ignored)
        $min_length = 3;
        //if it's an uppercase word, the minimum length is 4
        if(starts_with_upper($word)) {
            $min_length = 4;
        }
        if(strlen($word) > $min_length) {
            //if the word already exists, add up the count
            if (array_key_exists($word, $with_words_count_arr)) {
                $with_words_count_arr[$word]++;
            }
            //if the word doesn't exist, add it to the array with count 1
            else {
                $with_words_count_arr[$word] = 1;
            }
        }
    }
    return $with_words_count_arr;
}

//remove words from full array that also exist in array to remove
function remove_matches($full_array, $array_to_remove) {
    for($i = 0; $i < count($full_array); $i++) {
        if(in_array($full_array[$i], $array_to_remove)) {
            $full_array[$i] = "";
        }
    }
    return $full_array;
}

//for each word check if it starts with an uppercase letter, if so --> importance doubles
function get_words_with_importance($array) {
    //foreach word check if it starts with a lower or uppercase letter
    foreach($array as $word => $item) {
        if(starts_with_upper($word)) {
            if($array[$word] == 1) {
                $array[$word] = 2;
            }
            else {
                $array[$word] *= 2;
            }
        }
    }
    return $array;
}

function get_top_words($array_with_count, $amount) {
    $top_words = [];
    arsort($array_with_count);
    $array_with_count = array_slice($array_with_count, 0, $amount, true);
    
    foreach ($array_with_count as $key => $val) {
        array_push($top_words, $key);
    }
    
    return $top_words;
}

// function source: http://stackoverflow.com/questions/2814880/how-to-check-if-letter-is-upper-or-lower-in-php
function starts_with_upper($str) {
    $first_chr = substr ($str, 0, 1);
    if(strtolower($first_chr) != $first_chr) {
        return true;
    }
    else {
        return false;
    }
}

function find_search_words($title, $top_words) {
    
    $search_words = array();
    
    $title_arr  = explode(" ", str_replace(array("’", PHP_EOL), " ", str_replace(array('.', ',', '?'), "", $title)));
    //for each title word, check if it is in the top words or is similar to a top word, if yes -> searchword
    foreach($title_arr as $title_word) {
        foreach($top_words as $top_word) {
            similar_text($title_word, $top_word, $percent);
            if($percent > 75) {
                if(!in_array($title_word, $search_words)) {
                    array_push($search_words, $title_word);
                }
            }
        }
    }
    
    return $search_words;
}


?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Open search result</title>
    <style>
        .snopes_result {
            background-color: #cecece;
        }
    </style>
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
                <div>
                    <label for="title">Titel:</label>
                    <input type="text" name="title" id="title">
                </div>
                <div>
                    <label for="article">Artikel</label>
                    <textarea name="article" id="article"></textarea>
                </div>
                <!--
                <div>
                    <label for="date">Datum van publicatie:</label>
                    <input type="date" name="date" id="date">
                </div>
                -->
                <select name="do_what">
                    <option value="searchwords" selected>Get searchwords</option>
                    <option value="article_reliability">Article reliability</option>
                    <option value="both">Both</option>
                </select>
                <input type="submit" name="submit" value="Zoek">
            </form>
            
        </div>
        
        <div>
            <h2>Most important words:</h2>
            <?php if(isset($top_twenty)): ?>
            <?php foreach($top_twenty as $result): ?>
            <div><?php echo $result ?></div>
            <?php endforeach ?>
            <?php endif ?>
    
        </div>
        
        <?php if(isset($true_false)): ?>
        <div class="snopes_result">
            <h2>Snopes result:</h2>
            <p>The article was found on snopes.com and was declared <?php echo $true_false ?></p>
        </div>
        <?php endif ?>
        
    </main>
    
    
</body>
</html>