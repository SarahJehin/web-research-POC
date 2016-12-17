<?php

//echo('test');

//The most common English words should be filtered from the article (source: https://en.wikipedia.org/wiki/Most_common_words_in_English)
$most_common_english_words = array("have", "with", "this", "from", "they", "will", "would", "there", "their", "what", "about", "which", "when", "make", "like", "time", "just", "know", "tak", "into", "your", "good", "some", "could", "them", "than", "then", "look", "only", "come", "over", "think", "also", "back", "after", "these", "here", "made", "like");

//If these words are in the article, it will be downgraded
$critical_words = array("hoax", "false");

//array with the most important words from the article
$most_important_words_arr = array();


$title = "Tied to Europe, Britain’s Car Industry Is Vulnerable After ‘Brexit’";
$article = "BURNASTON, England — On Toyota’s brightly lit assembly line here, workers guide wheel and engine assemblies into unfinished sedans. Driverless carts carry parts through narrow aisles to work stations. The assembly line moves with clockwork precision, able to pop out a vehicle every 72 seconds.

Britain’s automotive industry, once ailing and plagued by strikes, now hums with the vibrancy of a global manufacturing hub. Most of the cars made in Burnaston, models like Auris and Avensis, will make their way beyond the British borders. Toyota buys parts and hires workers from across the European Union.

But the level of integration, previously lauded, has made the carmakers especially vulnerable after Britain’s vote to leave the bloc. If a messy divorce follows, Toyota and others face the prospect of higher tariffs, a smaller labor pool and less access to the 500 million potential customers in Europe — all of which will be negotiated in the coming months and years.

The drop in the British pound since the vote has not been much help, either. Many of the carmakers’ contracts here are priced in euros, even with suppliers in the same country like Johnson Controls, which makes seats for Toyota.";
$article2 = "Gisteren bereikte de luchtvervuiling in de Chinese hoofdstad het hoogste waarschuwingsniveau. Zevenhonderd van de fabrieken hebben opdracht gekregen om hun productie in te perken. De overige vijfhonderd fabrieken moeten hun werkzaamheden opschorten.

Onder de bedrijven bevinden zich onder andere een grote olieraffinaderij van Sinopec en een staalproductiebedrijf van Shougang Group.

De Chinese milieubeschermingsautoriteit heeft een vijfdaagse waarschuwing afgegeven. Niet alleen moeten veel fabrieken sluiten door de hevige smog, mensen worden ook gevraagd om binnen te blijven. Daarnaast zijn beperkingen opgelegd voor autogebruik en werkzaamheden in de bouw. De komende vier dagen zijn scholen gesloten.

De Air Quality Index (AQI) is al vier dagen op rij hoger dan 200. Vandaag stond de teller in Peking op 297. In meer dan veertig Chinese steden zijn waarschuwingen afgegeven voor de smog, waaronder 22 met rood alarm.

Er zijn in China de jongste dertig jaar 465 procent meer longkankers, en dat komt voornamelijk door de vervuiling, want het aantal Chinezen dat rookt is in diezelfde periode gedaald met maar liefst 63 procent.

Wie nu 18 is en in Beijing woont, kan er op rekenen dat hij 41 procent van de rest van zijn leven ziek zal zijn door de gevolgen van de luchtvervuiling concludeerde niet zo lang geleden het Beijings Centre for Disease Control and Prevention dat samen met de Wereldgezondheidsorganisatie daar een studie naar deed.

De luchtvervuiling in China veroorzaakt sowieso al 4.000 overlijdens per dag. Vooral het verbranden van steenkool en het klein stof dat dit veroorzaakt, is verantwoordelijk voor 17 % van de overlijdens. De luchtvervuiling in Peking - onder “normale” omstandigheden - heeft hetzelfde effect op de gezondheid als iemand die om het uur een sigaret rookt.

38 procent van de Chinese bevolking leeft constant in lucht die verschillende keren de norm overschrijdt van wat de Wereldgezondheidsorganisatie als veilig acht.

Under The Dome
China verbood vorig jaar nog een documentaire die een onthutsend beeld schetst van de luchtvervuiling in het land. De film  van onderzoeksjournaliste Chai Jing duurt 103 minuten en gaat in detail in op de oorzaken en gevolgen van de zware smog die talrijke Chinese grootsteden treft.

De film vertelt ook persoonlijke verhalen, zoals dat van een zesjarig meisje die in de koolmijnprovincie Shanxi woont, een van de meest vervuilde gebieden ter wereld. Ze zegt nog nooit witte wolken of sterren te hebben gezien. De film is ook kritisch voor de autoriteiten, die worden verweten nalatig te zijn geweest. Eén op de negen Chinezen bekeek de film in de eerste uren dat hij online stond, daarna greep de overheid in. Dit is de die documentaire (met Engelse ondertitels):"


//step 1 - get uppercase words from article (ignore words shorter than 4 characters)
$most_important_words_arr = get_uppercase_words($article);
//var_dump($most_important_words_arr);
//get uppercase words with count
$most_important_words_with_count = get_arr_with_count($most_important_words_arr);

//step 2 - set strings to lowercase
//$title_lower = strtolower($title);
//$article_lower = strtolower($article);

//step 3 - create arrays from these strings (remove spaces, commas and points)
$title_arr = explode(" ", $title);
//$article_arr  = explode(" ", str_replace("’", " ", str_replace(array('.', ','), "", $article)));
$article_arr  = explode(" ", str_replace(array("’", PHP_EOL), " ", str_replace(array('.', ','), "", $article)));
//var_dump($article_arr);

//step 3.1 - verwijder array van uppercases van article_arr
$article_arr = remove_matches($article_arr, $most_important_words_arr);

//step 4 - remove all most common english words
$article_arr = remove_most_common_words($article_arr, $most_common_english_words);
//var_dump($article_arr);

//step 5 - get words with their amount of occurrences (and only if the word is longer than 3 characters)
$article_word_count = get_arr_with_count($article_arr);
//var_dump($article_word_count);

//step 5.1 - merge the count arrays
$merged_count_arr = array_merge($most_important_words_with_count, $article_word_count);
//var_dump($merged_count_arr);

//step 5.2 - calculate importance based on number of occurrencies and whether or not starting with an uppercase
$importance_weight_arr = get_words_with_importance($merged_count_arr);
//var_dump($importance_weight_arr);

var_dump(get_top_words($importance_weight_arr, 8));

//step 6 - get the most occurring words
//$article_most_common_words = get_most_common_words($article_word_count, 5);
//var_dump($article_most_common_words);

//step 7 - push the most common words to the most important words array
//$most_important_words_arr = array_merge($most_important_words_arr, $article_most_common_words);
//var_dump($most_important_words_arr);

//step 8 - whole array back to lowercase

//step 9 words that are in the title are more important


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


//var_dump(remove_matches($article_arr, $most_important_words_arr));

//remove words from full array that also exist in array to remove
function remove_matches($full_array, $array_to_remove) {
    for($i = 0; $i < count($full_array); $i++) {
        if(in_array($full_array[$i], $array_to_remove)) {
            $full_array[$i] = "";
        }
    }
    return $full_array;
}

//parameter ($array with words and their counts, the amount of top words you want to receive)
//returns an array of most common words
function get_most_common_words ( $array, $amount) {
    $array_of_maxs = array();
    
    for($i = 0; $i < $amount; $i++) {
        //get the word with the highest count
        $top_word = array_keys($array, max($array))[0];
        //push it to the max's array
        array_push($array_of_maxs, $top_word);
        //remove it from the original array
        unset($array[$top_word]);
    }
    return $array_of_maxs;
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

/*

//get all words starting with an uppercase and return as an array
$n_words = preg_match_all('/([A-Z])\w+/', $article, $match_arr);
$word_arr = $match_arr[0];
var_dump("test", $word_arr);

/*$result = preg_replace("/".$character_to_replace."/", "<span class='replacer'>#</span>", $string);

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


$test01 = ['test', 'blab', 'xx'];
$test02 = ['test22', 'blab22', 'xx22'];

var_dump(array_merge($test01, $test02));

/*
 *
 * param: array of string
 * returns: array with count of each unique word (and if the word is longer than 3 characters)
 *//*
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
 *//*
function get_most_common_words ( $array, $amount) {
    //
    $array_of_maxs = array();
    
    for($i = 0; $i < $amount; $i++) {
        $top_word = array_keys($array, max($array))[0];
        //echo($top_word);
        array_push($array_of_maxs, $top_word);
        //var_dump($array_of_maxs);
        echo("<br>");
        unset($array[$top_word]);
    }
    
    return array_keys($array, max($array));
}

*/



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