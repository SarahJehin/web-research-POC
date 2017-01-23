//console.log("hello there!");
//phantom.exit();


var page = require('webpage').create();

var system = require('system');
var args = system.args;
/*
if (args.length === 1) {
  console.log('Try to pass some arguments when invoking this script!');
} else {
  args.forEach(function(arg, i) {
    console.log(i + ': ' + arg);
  });
}
phantom.exit()
*/

var link_to_scrape = args[1];


page.open(link_to_scrape, function() {

page.includeJs("http://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js", function() {

    
    search = page.evaluate(function() { 
        //return  $('.pb-feed-item:first-child .pb-feed-headline a').attr('href');
        var hrefs = [];
        for(var i = 0; i < 5; i++) {
            //console.log($('.pb-feed-item:nth-child(' +  (i+1) + ') .pb-feed-headline a').attr('href'));
            hrefs.push($('.pb-feed-item:nth-child(' +  (i+1) + ') .pb-feed-headline a').attr('href'));
        }
        return hrefs;
    });

    console.log(search);

    phantom.exit()
  });
})