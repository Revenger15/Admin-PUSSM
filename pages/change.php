<?php
if(isset($_POST['old-pw'])) {
    include '../includes/dbconfig.php';
    session_start();

    $auth = $firebase->createAuth();

    $cur = $_POST['inputPasswordOld'];
    $newp = $_POST['inputPasswordNew'];
    
    $uid = $_SESSION['uid'];

    try {
        $auth->signInWithEmailAndPassword($auth->getUser($uid)->__get('email'), $cur);
        
        $auth->changeUserPassword($uid, $newp);
    } catch (Exception $e) {
        echo 'Invalid Email and/or Password!';
    }

    exit();
}
?>