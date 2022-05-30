<?php

    require __DIR__.'/vendor/autoload.php';
    // error_reporting(0);

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\ServiceAccount;

    // error_reporting(0);

    // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__. 'react-ebe8e-firebase-adminsdk-edc3p-3832332702.json');
    $firebase = (new Factory)
        ->withServiceAccount(__DIR__. '/assess-module-firebase-adminsdk-bgqta-1fb0c9975e.json')
        ->withDatabaseUri('https://assess-module-default-rtdb.firebaseio.com/');

    $database = $firebase->createDatabase();

    if(!function_exists('str_icontains')) {
        // https://stackoverflow.com/questions/63121737/how-to-perform-case-insensitive-str-contains
        function str_icontains($haystack, $needle){
            $smallhaystack = strtolower($haystack);  // make the haystack lowercase, which essentially makes it case insensitive
            $smallneedle = strtolower($needle);  // makes the needle lowercase, which essentially makes it case insensitive
            if (str_contains($smallhaystack, $smallneedle)) {  // compares the lowercase strings
                return true;  // returns true (wow)
            } else {
                return false;  // returns false (wow)
            }
        }
    }
    
    if(!isset($_COOKIE['AY'])) {
        setcookie("AY", $database->getReference('system/current')->getValue());
    }