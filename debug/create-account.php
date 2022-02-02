<?php
include '../includes/dbconfig.php';

$auth = $firebase->createAuth();

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $type = $_POST['type'];
    $uid = $_POST['uid'];
    $verified = $_POST['confirmed'];

    $userProperties = [
        'email' => $email,
        'emailVerified' => $verified,
        'password' => $password,
    ];

    if ($uid != '') {
        $userProperties['uid'] = $uid;
    }

    try {
        $createdUser = $auth->createUser($userProperties);

        if ($type == 'ssphead') {
            $database->getReference('admin')->update([
                $createdUser->uid => [
                    'firstname' => $_POST['fname'],
                    'middlename' => $_POST['mname'],
                    'lastname' => $_POST['lname'],
                    'email' => $email,
                    'employeeNo' => $_POST['empid'],
                    'department' => $_POST['dept'],
                    'type' => $type,
                ]
            ]);
        } elseif($type == 'refP' || $type == 'refM') {
            $database->getReference('admin')->update([
                $createdUser->uid => [
                    'email' => $email,
                    'type' => $type,

                ]
            ]);
        } elseif($type == 'sspteacher') {
            $database->getReference('users')->update([
                $createdUser->uid => [
                    'firstname' => $_POST['fname'],
                    'middlename' => $_POST['mname'],
                    'lastname' => $_POST['lname'],
                    'email' => $email,
                    'employeeNo' => $_POST['empid'],
                    'department' => $_POST['dept'],
                    'section' => $_POST['section'],
                    'type' => $type,
                ]
            ]);
        } elseif($type == 'std') {
            $database->getReference('users')->update([
                $createdUser->uid => [
                    'contact' => $_POST['cno'],
                    'firstname' => $_POST['fname'],
                    'middlename' => $_POST['mname'],
                    'lastname' => $_POST['lname'],
                    'email' => $email,
                    'idnumber' => $_POST['stdid'],
                    'department' => $_POST['dept'],
                    'section' => $_POST['section'],
                    'type' => 'student',
                ]
            ]);
        }

    } catch (Exception $e) {
        echo '<script>alert("' . $e . '")</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - PUSSM Debug</title>
</head>

<body>
    <h1>PHINMA UPang Student Support Module</h1>
    <h3>P.U.S.S.M</h3>
    <form action="create-account.php" method="POST">
        <label for="email">Email Address</label>
        <input type="email" name="email" id="email" placeholder="sample@email.com" required><br>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" placeholder="**********" required><br>
        <label for="type">Account Type</label>
        <select name="type" id="type" onchange="showFields(this)" required>
            <option value="NULL" disabled selected>-Select Account Type-</option>
            <option value="ssphead">SSP Head</option>
            <option value="refP">Referral - Physical</option>
            <option value="refM">Referral - Mental</option>
            <option value="sspteacher">Instruct/SSP Teacher</option>
            <option value="std">Student</option>
        </select><br>
        <label for="UID">User ID</label>
        <input type="text" name="uid" id="uid" placeholder="Leave empty for random UID"><br>
        <label for="confirmed">Email Confirmed/Verified</label>
        <input type="checkbox" name="confirmed" id="confirmed"><br>

        <label class="fields std" for="stdid">Student Number</label>
        <input class="fields std" type="text" name="stdid" id="stdid"><br>
        <label class="fields std" for="cno">Contact Number</label>
        <input class="fields std" type="tel" name="cno" id="cno"><br>
        <label class="fields sspteacher ssphead" for="empid">Employee Number</label>
        <input class="fields sspteacher ssphead" type="text" name="empid" id="empid"><br>
        <label class="fields std sspteacher ssphead" for="fname">First Name</label>
        <input class="fields std sspteacher ssphead" type="text" name="fname" id="fname"><br>
        <label class="fields std sspteacher ssphead" for="mname">Middle Name</label>
        <input class="fields std sspteacher ssphead" type="text" name="mname" id="mname"><br>
        <label class="fields std sspteacher ssphead" for="lname">Last Name</label>
        <input class="fields std sspteacher ssphead" type="text" name="lname" id="lname"><br>
        <label class="fields std sspteacher" for="section">Section</label>
        <input class="fields std sspteacher" type="text" name="section" id="section"><br>
        <label class="fields std sspteacher ssphead" for="dept">Department</label>
        <input class="fields std sspteacher ssphead" type="text" name="dept" id="dept"><br>

        <input type="submit" name="submit" value="Submit">
    </form>
    <script>
        let fields = document.querySelectorAll(".fields");

        // alert(fields.length);

        fields.forEach(function(x) {
            x.style.display = "none"
            x.disabled = true;
        });

        function showFields(options) {
            // alert('a');
            let fields = document.querySelectorAll(".fields");

            // alert(fields.length);

            fields.forEach(function(x) {
                x.style.display = "none"
                x.disabled = true;
            });

            // alert(options.value);

            let selected = document.querySelectorAll("." + options.value);

            selected.forEach(function(x) {
                x.style.display = "inline-block";
                x.disabled = false;
            });
        }
    </script>

</body>

</html>