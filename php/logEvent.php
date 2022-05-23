<?php
if(!function_exists('logEvent')) {
    function logEvent($title, $message) {
        // echo __DIR__;
        // echo 'e';
        include __DIR__.'/../includes/dbconfig.php';
        $database->getReference('system/logs/'.round(microtime(true) * 1000))->update([
            'title' => $title,
            'message' => $message
          ]);
    }
}
?>