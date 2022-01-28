<?php
use Firebase\Auth\Token\Exception\InvalidToken;

// Calls the Firebase API/Library
include '../includes/dbconfig.php';
$auth = $firebase->createAuth();

session_start();

if(isset($_SESSION['error'])) {
    echo "<script>alert('". $_SESSION['error']."');</script>";
    unset($_SESSION['error']);
}

// Checks if form contains submit variable
if(isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];

    try {
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $token = $signInResult->idToken();
        try {
            $verIdToken = $auth->verifyIdToken($token);
            $uid = $verIdToken->claims()->get('sub');
            
            $_SESSION['uid'] = $uid;
            $_SESSION['token'] = $token;
            
            // Check if user exists in selected type
            if($type == "sspteacher") {
                $reference = $database->getReference("users/" . $uid);
                if(!$reference->getSnapshot()->hasChildren()) {
                    $_SESSION['error'] = "Invalid user type selected!";
                    header('Location: sign-out.php');
                }
            } else {
                $reference = $database->getReference("admin/" . $type);
                if(!$reference->getSnapshot()->hasChildren()) {
                    $_SESSION['error'] = "Invalid user type selected!";
                    header('Location: sign-out.php');
                }
            }

            // TODO: Change links based acct type
            if($type=="sspteacher") {
                header('Location: #sspteacher');
            } elseif($type=="ssphead") {
                header('Location: #ssphead');
            } elseif($type=="referM") {
                header('Location: #referM');
            } elseif($type=="referP") {
                header('Location: #referP');
            }
        } catch (InvalidToken $e) {
            echo '<script>alert("The token is invalid!")</script>';
        } catch (\InvalidArgumentException $e) {
            echo '<script>alert("The token could not be parsed!")</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Invalid Email and/or Password!")</script>';
    }

}
