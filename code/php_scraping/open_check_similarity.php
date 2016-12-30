<?php

include 'simple_html_dom.php';

//The most common English words should be filtered from the article (source: https://en.wikipedia.org/wiki/Most_common_words_in_English)
$most_common_english_words = array("have", "with", "this", "that", "were", "from", "they", "will", "would", "there", "their", "what", "about", "which", "when", "make", "like", "time", "just", "know", "tak", "into", "your", "good", "some", "could", "them", "than", "then", "look", "only", "come", "over", "think", "also", "back", "after", "these", "here", "made", "like", "almost", "later", "told", "said", "been", "didn", "most");

//get important words from article
//check search results on snopes
//open first result
//check similarities in first result --> do the important words come back in the result article (if yes, this means the result is relevant)
    //yes --> check whether it's true or false
    //no --> open next search result


if(isset($_POST['submit'])) {
    $results = get_key_words($_POST['article']);
    $url = search_snopes($_POST['searchwords'], 0);
    
    //hieronder moet er ook nog eerst gechecked worden of de belangrijkste 5 woorden er in voorkomen
    open_article_and_check($url, $results, 0);
}



function search_snopes($searchwords, $result_number) {
    $search_string = str_replace(" ", "+", $searchwords);
    $search_test = file_get_html('http://www.snopes.com/search/?q=' . $searchwords);
    
    if(!empty($search_test)){
        
        $url = $search_test->find('.search-results .item h3 a', $result_number)->href;
        
        //bovenstaande geeft /trump-sends-unpresidented-tweet/ , maar moet omgevormd worden naar url hieronder
        //http://www.snopes.com/trump-sends-unpresidented-tweet/
        $url = 'http://www.snopes.com' . $url;
        //print_r($url);
        return $url;

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
        
        echo($string_to_search_in);
        $article_relevant = true;
        
        $string_to_search_in = str_replace('"', "", $string_to_search_in);
        
        
        foreach($top_words as $top_word) {
            $position = strpos($string_to_search_in , $top_word);
            
            if(!$position) {
                $article_relevant = false;
                break;
            }
        }
        
        /*
        if($article_relevant) {
            echo("article relevant : yes");
        }
        else {
            echo("article relevant : no");
        }*/
        
        
        if($article_relevant) {
            echo("article seems to be relevant and the result on snopes was ");
            $true_false = $page->find('.claim-old span span', 0)->plaintext;
            echo($true_false);
        }
        else {
            echo("article was not relevant, let's try the next result");
            $result_number++;
            if($result_number < 5) {
                //$next_result = $search_test->find('.search-results .item h3 a', $result_number)->href;
                //$next_url = 'http://www.snopes.com' . $next_result;
                
                $next_url = search_snopes($_POST['searchwords'], $result_number);
                echo("next url: " .$next_url);
                
                return open_article_and_check($next_url, $top_words, $result_number);
            }
        }
        
        
    }
}


// get most important words from article

function get_key_words($text) {
    
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
    
    //step 8 - get the most important words in order of importance and limit the results to 5 key_words
    $top_words = get_top_words($importance_weight_arr, 5);
    
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
                <div>
                    <label for="article">Artikel</label>
                    <textarea name="article" id="article"></textarea>
                </div>
                <input type="submit" name="submit" value="Zoek">
            </form>
            
        </div>
        
        <div>
            <h2>Most important words:</h2>
            <?php if(isset($results)): ?>
            <?php foreach($results as $result): ?>
            <div><?php echo $result ?></div>
            <?php endforeach ?>
            <?php endif ?>
    
        </div>
    </main>
    
    
</body>
</html>