<?php

//The most common English words should be filtered from the article (source: https://en.wikipedia.org/wiki/Most_common_words_in_English)
$most_common_english_words = array("have", "with", "this", "that", "were", "from", "they", "will", "would", "there", "their", "what", "about", "which", "when", "make", "like", "time", "just", "know", "tak", "into", "your", "good", "some", "could", "them", "than", "then", "look", "only", "come", "over", "think", "also", "back", "after", "these", "here", "made", "like", "almost", "later", "told", "said", "been", "didn");

if(isset($_POST['submit'])) {
    //var_dump(get_key_words($_POST['article']));
    $results = get_key_words($_POST['article']);
}





//If these words are in the article, it will be downgraded
$critical_words = array("hoax", "false");


//example data for test purposes only
$title = "Tied to Europe, Britain’s Car Industry Is Vulnerable After ‘Brexit’";
$article1 = "BURNASTON, England — On Toyota’s brightly lit assembly line here, workers guide wheel and engine assemblies into unfinished sedans. Driverless carts carry parts through narrow aisles to work stations. The assembly line moves with clockwork precision, able to pop out a vehicle every 72 seconds.

Britain’s automotive industry, once ailing and plagued by strikes, now hums with the vibrancy of a global manufacturing hub. Most of the cars made in Burnaston, models like Auris and Avensis, will make their way beyond the British borders. Toyota buys parts and hires workers from across the European Union.

But the level of integration, previously lauded, has made the carmakers especially vulnerable after Britain’s vote to leave the bloc. If a messy divorce follows, Toyota and others face the prospect of higher tariffs, a smaller labor pool and less access to the 500 million potential customers in Europe — all of which will be negotiated in the coming months and years.

The drop in the British pound since the vote has not been much help, either. Many of the carmakers’ contracts here are priced in euros, even with suppliers in the same country like Johnson Controls, which makes seats for Toyota.";
$article = "Gisteren bereikte de luchtvervuiling in de Chinese hoofdstad het hoogste waarschuwingsniveau. Zevenhonderd van de fabrieken hebben opdracht gekregen om hun productie in te perken. De overige vijfhonderd fabrieken moeten hun werkzaamheden opschorten.

Onder de bedrijven bevinden zich onder andere een grote olieraffinaderij van Sinopec en een staalproductiebedrijf van Shougang Group.

De Chinese milieubeschermingsautoriteit heeft een vijfdaagse waarschuwing afgegeven. Niet alleen moeten veel fabrieken sluiten door de hevige smog, mensen worden ook gevraagd om binnen te blijven. Daarnaast zijn beperkingen opgelegd voor autogebruik en werkzaamheden in de bouw. De komende vier dagen zijn scholen gesloten.

De Air Quality Index (AQI) is al vier dagen op rij hoger dan 200. Vandaag stond de teller in Peking op 297. In meer dan veertig Chinese steden zijn waarschuwingen afgegeven voor de smog, waaronder 22 met rood alarm.

Er zijn in China de jongste dertig jaar 465 procent meer longkankers, en dat komt voornamelijk door de vervuiling, want het aantal Chinezen dat rookt is in diezelfde periode gedaald met maar liefst 63 procent.

Wie nu 18 is en in Beijing woont, kan er op rekenen dat hij 41 procent van de rest van zijn leven ziek zal zijn door de gevolgen van de luchtvervuiling concludeerde niet zo lang geleden het Beijings Centre for Disease Control and Prevention dat samen met de Wereldgezondheidsorganisatie daar een studie naar deed.

De luchtvervuiling in China veroorzaakt sowieso al 4.000 overlijdens per dag. Vooral het verbranden van steenkool en het klein stof dat dit veroorzaakt, is verantwoordelijk voor 17 % van de overlijdens. De luchtvervuiling in Peking - onder “normale” omstandigheden - heeft hetzelfde effect op de gezondheid als iemand die om het uur een sigaret rookt.

38 procent van de Chinese bevolking leeft constant in lucht die verschillende keren de norm overschrijdt van wat de Wereldgezondheidsorganisatie als veilig acht.

Under The Dome
China verbood vorig jaar nog een documentaire die een onthutsend beeld schetst van de luchtvervuiling in het land. De film  van onderzoeksjournaliste Chai Jing duurt 103 minuten en gaat in detail in op de oorzaken en gevolgen van de zware smog die talrijke Chinese grootsteden treft.

De film vertelt ook persoonlijke verhalen, zoals dat van een zesjarig meisje die in de koolmijnprovincie Shanxi woont, een van de meest vervuilde gebieden ter wereld. Ze zegt nog nooit witte wolken of sterren te hebben gezien. De film is ook kritisch voor de autoriteiten, die worden verweten nalatig te zijn geweest. Eén op de negen Chinezen bekeek de film in de eerste uren dat hij online stond, daarna greep de overheid in.";



//var_dump(get_key_words($article));

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
    
    //step 8 - get the most important words in order of importance and limit the results to 20 key_words
    $top_words = get_top_words($importance_weight_arr, 20);
    
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
    <title>How reliable is that article?!</title>
    <style>
        body {
            font-family: "Calibri", sans-serif;
        }
        label {
            display: block;
        }
        textarea {
            width: 800px;
            height: 200px;
            display: block;
        }
    </style>
</head>
<body>
    
    <header>
        <h1>Test the reliability of an article</h1>
    </header>
    
    <main>
        <div>
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div>
                    <label for="article">Artikel</label>
                    <textarea name="article" id="article"></textarea>
                </div>
                <input type="submit" name="submit" value="Find important words!">
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



