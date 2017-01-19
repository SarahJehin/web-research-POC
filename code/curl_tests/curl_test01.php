<?php

if(isset($_POST['submit'])) {
    //var_dump($_POST);


    $autofix = 0;
    if(isset($_POST['autofix'])) {
        $autofix = $_POST['autofix'];
    }

    //extract data from the post
    //set POST variables
    $url = 'http://www.onlinecorrection.com/index.php';
    $fields = array(
        'mytext' => urlencode($_POST['text']),
        'autofix' => urlencode($autofix),
        'lang_var' => urlencode($_POST['lang_var'])
    );

    $fields_string = "";
    //url-ify the data for the POST
    foreach($fields as $key=>$value) {
        $fields_string .= $key.'='.$value.'&';
    }
    $fields_string = rtrim($fields_string, '&');

    //var_dump($fields);
    var_dump($fields_string);
    
    /*
    //open connection
    $ch = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, count($fields));
    curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

    //execute post
    $result = curl_exec($ch);

    //close connection
    curl_close($ch);
    */
    
    // other try
    
    $curl_connection = curl_init($url);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_USERAGENT,
    "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
    
    
    $post_data['mytext'] = urlencode($_POST['text']);
    $post_data['autofix'] = urlencode($autofix);
    $post_data['lang_var'] = urlencode($_POST['lang_var']);
    foreach ( $post_data as $key => $value) 
    {
        $post_items[] = $key . '=' . $value;
    }
    $post_string = implode ('&', $post_items);
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
    $result = curl_exec($curl_connection);
    
    print_r(curl_getinfo($curl_connection));
    print_r($result);
    
    curl_close($curl_connection);
    
    
    
    /*
    $curl_connection = curl_init('http://spellcheckplus.com/');
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_USERAGENT,
    "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
    //curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
    
    
    $post_data['noAjax'] = 1;
    $post_data['spellchecker'] = "";
    $post_data['typedText'] = "This is a test, can you pleassse check it. Thaank you!";
    foreach ( $post_data as $key => $value) 
    {
        $post_items[] = $key . '=' . $value;
    }
    $post_string = implode ('&', $post_items);
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
    $result = curl_exec($curl_connection);
    
    print_r(curl_getinfo($curl_connection));
    //print_r($result);
    
    curl_close($curl_connection);
    */
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Curl test</title>
</head>
<body>
    
    
    <div>
        <p>This is curl test 01</p>
    </div>
    
    <form action="<?php $_SERVER['PHP_SELF']?>" method="post">
        <textarea name="text"></textarea>
        <input type="checkbox" name="autofix" value="1">
        <select name="lang_var">
            <option selected="" value="en-US">American English</option>
            <option value="en-GB">British English</option>
        </select>
        
        <input type="submit" name="submit" value="go">
    </form>
    
    
</body>
</html>