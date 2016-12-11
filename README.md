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



## Geraadpleegde bronnen
	Filteren:
		https://en.wikipedia.org/wiki/Tf%E2%80%93idf
	Beschikbare API's
		https://developer.nytimes.com/
		http://open-platform.theguardian.com/documentation/
		https://newsapi.org/the-washington-post-api
	Scraping with PHP:
		http://timvaniersel.com/web-scraping-met-php/


