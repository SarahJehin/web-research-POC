# Logboek Proof of Concept - Check reliabilty of an article found on the internet

## 08/12/2016
**Mogelijke aanpak:**
   Belangrijkste woorden uit artikel halen  
   Artikels ophalen via API's (enkel NY Times en The Guardian)  
   PHP scraping van de archief-pagina van andere sites  

**Uitwerking belangrijkste woorden ophalen (filters):**
   get words starting with an uppercase, not preceded by a .  
		"Donald Trump decided to build the Mexican wall.  He told this to the Daily Mail." => Donald,Trump,Mexican,Daily,Mail  
   filter words shorter than 4 characters  
		"The big boss took a look in the factory." => boss,took,look,factory  
   filter most common English words  
		"They asked Hillary what she thought about the wall" => asked,Hillary,thought,wall  
   get most common words in article (by amount of occurrences)  
		"Toyota proposed to move to Spain.  'It's all fixed', says Goldman, speechman of Toyota" => Toyota  

## 15, 16, 17/12/2016
**Get most important words from article works pretty good**  
  Filters:
  1. extract words starting with an uppercase  
	$most_important_words_arr = get_uppercase_words($article); 
  2. get amount of occurrencies of these uppercase words  
	$most_important_words_with_count = get_arr_with_count($most_important_words_arr);
  3. convert article to array of words  
	$article_arr  = explode(" ", str_replace(array("�", PHP_EOL), " ", str_replace(array('.', ','), "", $article)));
  4. remove words that are already in the $most_important_words_arr   
	$article_arr = remove_matches($article_arr, $most_important_words_arr);
  5. filter out the most common English words (e.g. like, have, maybe, ...)  
	$article_arr = remove_most_common_words($article_arr, $most_common_english_words);
  6. get amount of occurrencies of the remaining words in the article  
	$article_word_count = get_arr_with_count($article_arr);
  7. merge the two word arrays  
	$merged_count_arr = array_merge($most_important_words_with_count, $article_word_count);
  8. calculate the importance weight based on amount of occurrencies and upper/lowercase first character  
	$importance_weight_arr = get_words_with_importance($merged_count_arr);
  9. order this arr from most important to least important and get the first 8 results  
	var_dump(get_top_words($importance_weight_arr, 8));

## 17/12/2016
**First tests with php scraping (using PHP Simple HTML DOM Parser)**
   - Get simple elements (by id, by classname)
   - Get children of find results <br />
  
**Problemen**
   - kan lang duren als het om veel content gaat
   - de content van pagina's die ingeladen worden met Angular wordt niet opgehaald (bvb probleem bij NY Times en Washington Post)

## 18/12/2016
   - scraping van newssites en scopes.com (haal de titel van zoekresultaten van bepaalde termen op)
   - op snopes checken of het eerste zoekresultaat van bepaalde termen true of false is  <br />
**Volgende stappen:**  
      op snopes checken of de inhoud van het eerste zoekresultaat minstens 5 van de belangrijkste woorden van het oorspronkelijke artikel bevat
         indien ja: checken of het door snopes.com als true of false beschouwt wordt en returnen
         indien nee: het volgende zoekresultaat openen en checken of er minstens 5 van de belangrijkste woorden in voorkomen (enzoverder enzoverder)
      ook op andere nieuwssites de resultaten 1 voor 1 scrapen en checken op vergelijkbare inhoud

## 30/12/2016
   - geef je zoekwoorden in
   - geef je volledige artikel in
   - op snopes eerste zoekresultaat openen en kijken of 5 belangrijkste woorden van artikel erin voorkomen
   - indien ja: kijk welk resultaat snopes teruggeeft
   - indien nee: open het volgende resultaat en kijk weer of de belangrijkste woorden erin voorkomen
   - bovenstaande stappen worden herhaald voor de eerste 5 resultaten  
   - is artikel terug te vinden op bbc (-> moet nog serieus verfijnd worden...)  <br />
  
**Volgende stappen:**  
   - kijken of het artikel terug te vinden is in een belangrijk mediakanaal
   - hoe meer van de belangrijkste woorden erin terugkomen, des te betrouwbaarder
   - indien het woord 'hoax' of 'false' erin voorkomt --> heel wat minder betrouwbaar

## 31/12/2016
   - loader while executing php
   - check on bbc en the independent (betrouwbaarheid wordt toegevoegd als er een vergelijkbaar artikel wordt teruggevonden)

## 18, 19/01/2017
   - hoe goed is het artikel geschreven --> met online spelling checkers
   - http://www.onlinecorrection.com/ geeft het aantal fouten terug + vindt ook fouten zoals *"your"* vs *"you're"*
   - met curl post naar onlinecorrection.com --> blijft oneindig loaden.  Ontbreekt er data bij post??  

## 20/01/2017
   - spellingcheck met api ipv curl
   - mogelijkheid is https://textgears.com/api/ , zij geven het aantal errors terug + verbeteringen
   - per fout worden er punten afgetrokken van de betrouwbaarheid van het artikel

## 21/01/2017
   - score van api gebruiken ipv punten aftrekken per fout
   - pspell van php zelf is ook een optie om te zien hoeveel fouten erin voorkomen (maar api werkt beter)

## 22/01/2017
   - zoekwoorden afleiden uit combinatie van titel en artikel (--> werkt goed voor bepaalde artikels, maar is vrij onnauwkeurig voor andere artikels)
   - aanpak van sites waarbij de zoekresultaten worden ingeladen met angular? --> ophalen met PhantomJS

## 23/01/2017
   - Scraping met PhantomJS --> maakt gebruik van jQuery om elementen op een pagina te vinden  
   - PhantomJS script aanroepen vanuit php en de juiste url als parameter meegeven
   - Resultaten van javascript opvangen om die daarna te gaan scrapen met PHP


## Geraadpleegde bronnen
### Filteren:
https://en.wikipedia.org/wiki/Tf%E2%80%93idf  
http://softwareengineering.stackexchange.com/questions/179791/language-parsing-to-find-important-words  
http://hlt.di.fct.unl.pt/jfs/Unigrams.pdf  
### Beschikbare API's
https://developer.nytimes.com/  
http://open-platform.theguardian.com/documentation/  
https://newsapi.org/the-washington-post-api  
### Scraping with PHP:
http://timvaniersel.com/web-scraping-met-php/  
https://sourceforge.net/projects/simplehtmldom/files/  
http://nimishprabhu.com/top-10-best-usage-examples-php-simple-html-dom-parser.html  
http://simplehtmldom.sourceforge.net/manual.htm  
http://stackoverflow.com/questions/36275859/simple-html-dom-parsing-first-child-into-an-array  
### Post naar url met curl
http://www.html-form-guide.com/php-form/php-form-submit.html  
http://stackoverflow.com/questions/17270335/creating-a-robot-to-fill-form-with-some-pages-in  
https://davidwalsh.name/curl-post  
### Online English spelling checkers (incl API's)
http://www.onlinecorrection.com/ (dit is ��n van de weinige die ook het aantal fouten teruggeeft)  
https://textgears.com/api/  
http://www.hackingwithphp.com/16/7/1/calculating-similarity-of-words (met pspell fouten terugvinden)  
### Vergelijken van overeenkomst van strings / artikels (allemaal te onnauwkeurig...)  
http://php.net/manual/en/function.similar-text.php  
https://cambiatablog.wordpress.com/2011/03/25/algorithm-for-string-similarity-better-than-levenshtein-and-similar_text/  
https://github.com/akalongman/php-string-compare  
### Scraping with PhantomJS (+ aanroepen vanuit PHP)  
http://stackoverflow.com/questions/39789333/scraping-web-page-loaded-through-angularjs-curl-php  
http://phantomjs.org/quick-start.html  
http://phantomjs.org/api/webpage/method/evaluate.html  
http://stackoverflow.com/questions/17448439/screen-scraping-js-page  
http://php.net/manual/en/function.exec.php  
http://php.net/manual/en/function.sprintf.php  











