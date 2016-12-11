<?php

//echo('test');

//The most common English words should be filtered from the article (source: https://en.wikipedia.org/wiki/Most_common_words_in_English)
$most_common_english_words = array("have", "with", "this", "from", "they", "will", "would", "there", "their", "what", "about", "which", "when", "make", "like", "time", "just", "know", "tak", "into", "your", "good", "some", "could", "them", "than", "then", "look", "only", "come", "over", "think", "also", "back", "after", "these");

//If these words are in the article, it will be downgraded
$critical_words = array("hoax", "false");


$title = "Tied to Europe, Britain’s Car Industry Is Vulnerable After ‘Brexit’";
$article = "BURNASTON, England — On Toyota’s brightly lit assembly line here, workers guide wheel and engine assemblies into unfinished sedans. Driverless carts carry parts through narrow aisles to work stations. The assembly line moves with clockwork precision, able to pop out a vehicle every 72 seconds.

Britain’s automotive industry, once ailing and plagued by strikes, now hums with the vibrancy of a global manufacturing hub. Most of the cars made in Burnaston, models like Auris and Avensis, will make their way beyond the British borders. Toyota buys parts and hires workers from across the European Union.

But the level of integration, previously lauded, has made the carmakers especially vulnerable after Britain’s vote to leave the bloc. If a messy divorce follows, Toyota and others face the prospect of higher tariffs, a smaller labor pool and less access to the 500 million potential customers in Europe — all of which will be negotiated in the coming months and years.

The drop in the British pound since the vote has not been much help, either. Many of the carmakers’ contracts here are priced in euros, even with suppliers in the same country like Johnson Controls, which makes seats for Toyota.";

$title_lower = strtolower($title);
$article_lower = strtolower($article);
$title_arr = explode(" ", $title_lower);
$article_arr  = explode(" ", str_replace(array('.', ','), "", $article_lower));

//var_dump($article_arr);
//var_dump($title_arr, $article_arr);
//var_dump($title_arr);



//var_dump(get_arr_with_count($article_arr));
var_dump(get_most_common_words(get_arr_with_count($article_arr), 5));
//var_dump(get_arr_with_count($article_arr)["toyota"]);

/*
 *
 * param: array of string
 * returns: array with count of each unique word (and if the word is longer than 3 characters)
 */
function get_arr_with_count ( $all_words_arr) {
    $with_words_count_arr = array();

    foreach($all_words_arr as $word) {
        //only add words with a length greater than 3 (so 'to', 'i' 'the', 'in', ... will be ignored)
        if(strlen($word) > 3) {
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

/*
 *
 * param: array, amount of maximums
 * returns: array with count of each unique word (and if the word is longer than 3 characters)
 */
function get_most_common_words ( $array, $amount) {
    //
    $array_of_maxs = array();
    
    for($i = 0; $i < $amount; $i++) {
        $top_word = array_keys($array, max($array))[0];
        //echo($top_word);
        array_push($array_of_maxs, $top_word);
        var_dump($array_of_maxs);
        echo("<br>");
        unset($array[$top_word]);
    }
    
    return array_keys($array, max($array));
}










// F I R S T   T E S T S

$title_words_count = array();

//multi_dimen arr with words and count
foreach($title_arr as $title_word) {
    //echo($title_word);
    if (array_key_exists($title_word, $title_words_count)) {
        $title_words_count[$title_word]++;
    }
    else {
        //array_push($title_words_count, $title_word => 1);
        $title_words_count[$title_word] = 1;
    }
}

//var_dump($title_words_count);
//echo(count($title_words_count));






?>