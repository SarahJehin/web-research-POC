# Logboek

## 08/12/2016
	Nagedacht over mogelijke aanpak:
	 > Belangrijkste woorden uit artikel halen
	 > Artikels ophalen via API's (enkel NY Times en The Guardian)
	 > PHP scraping van de archief-pagina van andere sites

	Uitwerking belangrijkste woorden ophalen (filters):
	 > get words starting with an uppercase, not preceded by a .
		"Donald Trump decided to build the Mexican wall.  He told this to the Daily Mail." => Donald,Trump,Mexican,Daily,Mail
	 > filter words shorter than 4 characters
		"The big boss took a look in the factory." => boss,took,look,factory
	 > filter most common English words
		"They asked Hillary what she thought about the wall" => asked,Hillary,thought,wall
	 > get most common words in article (by amount of occurrences)
		"Toyota proposed to move to Spain.  'It's all fixed', says Goldman, speechman of Toyota" => Toyota

## 15, 16, 17/12/2016
	Get most important words from article works okay
	Filters:
		   //extract words starting with an uppercase
		1) $most_important_words_arr = get_uppercase_words($article); 
		   //get amount of occurrencies of these uppercase words
		2) $most_important_words_with_count = get_arr_with_count($most_important_words_arr);
		   //convert article to array of words
		3) $article_arr  = explode(" ", str_replace(array("’", PHP_EOL), " ", str_replace(array('.', ','), "", $article)));
		   //remove words that are already in the $most_important_words_arr 
		4) $article_arr = remove_matches($article_arr, $most_important_words_arr);
		   //filter out the most common English words (e.g. like, have, maybe, ...)
		5) $article_arr = remove_most_common_words($article_arr, $most_common_english_words);
		   //get amount of occurrencies of the remaining words in the article
		6) $article_word_count = get_arr_with_count($article_arr);
		   //merge the two word arrays
		7) $merged_count_arr = array_merge($most_important_words_with_count, $article_word_count);
		   //calculate the importance weight based on amount of occurrencies and upper/lowercase first character
		8) $importance_weight_arr = get_words_with_importance($merged_count_arr);
		   //order this arr from most important to least important and get the first 8 results
		9) var_dump(get_top_words($importance_weight_arr, 8));

## 17/12/2016
**First tests with php scraping (using PHP Simple HTML DOM Parser)**
   - Get simple elements (by id, by classname)
   - Get children of find results

 -> kan lang duren als het om veel content gaat :confused:


## Geraadpleegde bronnen
### Filteren:
https://en.wikipedia.org/wiki/Tf%E2%80%93idf
### Beschikbare API's
https://developer.nytimes.com/
http://open-platform.theguardian.com/documentation/
https://newsapi.org/the-washington-post-api
### Scraping with PHP:
http://timvaniersel.com/web-scraping-met-php/
https://sourceforge.net/projects/simplehtmldom/files/
http://nimishprabhu.com/top-10-best-usage-examples-php-simple-html-dom-parser.html
http://simplehtmldom.sourceforge.net/manual.htm


