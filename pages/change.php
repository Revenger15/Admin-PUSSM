<?php
if(isset($_POST['inputPasswordOld'])) {
    include '../includes/dbconfig.php';
    session_start();

    $auth = $firebase->createAuth();

    $cur = $_POST['inputPasswordOld'];
    $newp = $_POST['inputPasswordNew'];
    
    $uid = $_SESSION['uid'];

    try {
        $auth->signInWithEmailAndPassword($auth->getUser($uid)->__get('email'), $cur);
        
        $auth->changeUserPassword($uid, $newp);

        echo '
        <script>
        alert("Updated Password");
        </script>';
    } catch (Exception $e) {
        echo '
        <script>
        alert(\'Invalid Email and/or Password!\');
        </script>';
    }
    echo '
    <script>
      if (\'referrer\' in document) {
        window.location = document.referrer;
        /* OR */
        //location.replace(document.referrer);
    } else {
        window.history.back();
    }
    </script>';

    include '../../php/logEvent.php';
    logEvent('Change Password', $_SESSION['uid'] . ' has changed their own password.');

    exit();
}
?>