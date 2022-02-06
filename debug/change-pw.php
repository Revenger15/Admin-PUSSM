<?php 
include '../includes/dbconfig.php';

$auth = $firebase->createAuth();

if (isset($_POST['submit'])) {
$uid = $_POST['uid'];
$password = $_POST['password'];

$auth->changeUserPassword($uid, $password);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>

<body>
    <h1>PHINMA UPang Student Support Module</h1>
    <h3>P.U.S.S.M</h3>
    <form action="change-pw.php" method="post">
        <label for="uid">UID: </label>
        <input type="text" name="uid" id="uid">
        <label for="password">Password: </label>
        <input type="password" name="password" id="password">
        <input type="submit" value="Submit" name="submit">
    </form>
</body>

</html>