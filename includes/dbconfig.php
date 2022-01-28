<?php

    require __DIR__.'/vendor/autoload.php';

    use Kreait\Firebase\Factory;
    use Kreait\Firebase\ServiceAccount;

    // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__. 'react-ebe8e-firebase-adminsdk-edc3p-3832332702.json');
    $firebase = (new Factory)
        ->withServiceAccount(__DIR__. '/assess-module-firebase-adminsdk-bgqta-e4cd5f3d27.json')
        ->withDatabaseUri('https://assess-module-default-rtdb.firebaseio.com/');

    $database = $firebase->createDatabase();

?>