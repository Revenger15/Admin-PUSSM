<?php
if(!function_exists('logEvent')) {
    function logEvent($title, $message) {
        include '../includes/dbconfig.php';
        $database->getReference('system/logs/'.round(microtime(true) * 1000))->update([
            'title' => $title,
            'message' => $message
          ]);
    }
}
?>