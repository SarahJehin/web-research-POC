<?php


//php post
//$data = json_decode(file_get_contents('https://api.textgears.com/check.php?text=I+is+an+engeneer!+Can+I+chaange+somethink+in+this+text+is+this+abaut%2C+can+you+find+the+eror%3F&key=DEMO_KEY'), true);
//print_r($data);
/*
$arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5, 'errors' => array("id" => "blab", "iidd" => "bjbeb"));
$blab = json_encode($arr);
echo($blab);
$data = (array)json_decode($blab);
print_r(count((array)$data['errors']));
*/


$score = 50;

if(isset($_POST["submit"])) {
    
    $limited_text_for_check = substr($_POST["text"], 0, 5000);
    
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
    
    $score = $score-$down_score;
    
    
    //echo("amount of errors in the text: " . $errors_count);
    
    //$score -= $errors_count;
    echo($score);
    
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spell check</title>
</head>
<body>
    
    
    <form method="post" action="<?php $_SERVER['PHP_SELF']?>">
        
        <textarea class="text" name="text"></textarea>
        
        <input type="submit" name="submit">
        
    </form>
    
    <div class="submit">Submit</div>
    
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script>
        
        $score = 50;
        
        //beter met php doen, dan kan alles in php (zie bovenaan)
        $(".submit").click(function() {
            $text = $(".text").val();
            $text = $text.split(' ').join('+');
            $text = $text.split("?").join("%3F");
            $text = $text.split(",").join("%2C");
            
            console.log($text);
            
            /*$.post( "https://api.textgears.com/check.php", { text: $text, key:'oHTebMK40oLRkxGq' }, function( data ) {
              console.log( data );
            }, "json");*/
            
            /*
            $.getJSON( "https://api.textgears.com/check.php?text=" + $text + "&key=DEMO_KEY", function( data ) {
              console.log(data);
                //console.log(data.errors.length);
                $score -= data.errors.length;
                console.log("score1: " + $score); //juist !!
                
            });
            */
            
            //console.log("score2: " + $score); //fout !!
            
        });
        
        
    </script>
    
    
</body>
</html>