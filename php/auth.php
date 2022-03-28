<?php

use Firebase\Auth\Token\Exception\InvalidToken;

// Calls the Firebase API/Library
include '../includes/dbconfig.php';
$auth = $firebase->createAuth();
session_start();

// if(isset($_POST['google'])) {
//     $signInResult = $auth->signInWithIdpAccessToken("google.com", "244006904642-pcsrk139t8rv3j293kpe4q8ci1fo99pa.apps.googleusercontent.com", null, "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");
// }

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}

// Checks if form contains submit variable
if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if($email == 'admin') {
        if(!$database->getReference("users")->getSnapshot()->hasChildren()) {
            if($password == "admin") {
                try {
                    $signInResult = $auth->signInAnonymously();
                    $token = $signInResult->idToken();
                    try {
                        $verIdToken = $auth->verifyIdToken($token);
                        $uid = $verIdToken->claims()->get('sub');
            
                        $_SESSION['uid'] = $uid;
                        $_SESSION['token'] = $token;
                        $_SESSION['type'] = 'CSDL';

                        var_dump($_SESSION);
            
                        header('Location: ../pages/superadmin/dashboard.php');
                    } catch (InvalidToken $e) {
                        echo '<script>alert("The token is invalid!")</script>';
                    } catch (\InvalidArgumentException $e) {
                        echo '<script>alert("The token could not be parsed!")</script>';
                    }
                } catch (Exception $e) {
                    echo '<script>alert("Invalid Email and/or Password!"); window.location = "../";</script>';
                }
            } else {
                echo '<script>alert("Invalid Email and/or Password!"); window.location = "../";</script>';
            }
        } else {
            echo '<script>
                alert("Invalid email/password");
                //window.location = "../";
            </script>';
        }
        exit();
    }

    try {
        $signInResult = $auth->signInWithEmailAndPassword($email, $password);
        $token = $signInResult->idToken();
        try {
            $verIdToken = $auth->verifyIdToken($token);
            $uid = $verIdToken->claims()->get('sub');

            $_SESSION['uid'] = $uid;
            $_SESSION['token'] = $token;
            $_SESSION['type'] = $database->getReference("users/" . $uid . "/type")->getValue();

            switch ($_SESSION['type']) {
                case 'CSDL':
                    header('Location: ../pages/superadmin/dashboard.php');
                    break;
                case 'nurse':
                    header('Location: ../pages/nurse/dashboard.php');
                    break;
                case 'ssphead':
                    header('Location: ../pages/ssphead/dashboard.php');
                    break;
                case 'sspteacher':
                    header('Location: ../pages/sspteacher/dashboard.php');
                    break;
                case 'student':
                    header('Location: ../pages/sign-out.php');
                    break;
                default:
                    echo '<script>alert("User type not found: '.$_SESSION['type'].'\nPlease contact the Admin.");
                    location.href = "../pages/sign-in.html";
                    </script>';
            }

            // Removed. New system implemented for usertype detection
            // $success = true;
            // Check if user exists in selected type
            // if ($type == "sspteacher") {
            //     echo 'a';
            //     $reference = $database->getReference("users/" . $uid);
            //     if (!$reference->getSnapshot()->hasChildren()) {
            //         $success = false;
            //     }
            // } else {
            //     $reference = $database->getReference("admin/" . $uid);
            //     if (!$reference->getSnapshot()->hasChildren()) {
            //         $success = false;
            //     }
            // }
            // if($success) {
            //     if($type=="sspteacher") {
            //         header('Location: ../pages/sspteacher/dashboard.php');
            //     } elseif($type=="ssphead") {
            //         header('Location: ../pages/ssphead/dashboard.php');
            //     } elseif($type=="referM" && $type=="referP") {
            //         header('Location: ../pages/refer/tables.php');
            //     }
            // } else {
            //     $_SESSION['error'] = "Invalid user type selected!";
            //     header('Location: ../pages/sign-out.php');
            // }
        } catch (InvalidToken $e) {
            echo '<script>alert("The token is invalid!")</script>';
        } catch (\InvalidArgumentException $e) {
            echo '<script>alert("The token could not be parsed!")</script>';
        }
    } catch (Exception $e) {
        echo '<script>alert("Invalid Email and/or Password!"); window.location = "../";</script>';
    }
}
