<?php
include '../includes/dbconfig.php';

$auth = $firebase->createAuth();

if (isset($_POST['submit'])) {
    $stdUID = $_POST['student'];
    $teacherUID = $_POST['teacher'];
    $physical = $_POST['physical'];
    $mental = $_POST['mental'];
    $total = $_POST['total'];
    $timestamp = $_POST['timestamp'];

    $resultRef = $database->getReference("result");
    
    echo($stdUID . '<br/>' . $teacherUID);
    
    if ($database->getReference("users/" . $stdUID)->getSnapshot()->hasChildren()) {
        if ($database->getReference("users/" . $teacherUID)->getSnapshot()->hasChildren()) {
            $resultRef->update([
                $timestamp => [
                    'uid' => $stdUID,
                    'mental' => $mental,
                    'physical' => $physical,
                    'overall' => $total,
                ],
            ]);
            
            $userRef = $database->getReference("users");

            $userRef->getChild($stdUID.'/result')->update([
                $timestamp => $timestamp,
            ]);

            $userRef->getChild($teacherUID.'/result')->update([
                $timestamp => $timestamp,
            ]);
        } else {
            echo '<script>alert("Teacher not found. Please check UID.")</script>';
        }
    } else {
        echo '<script>alert("Student not found. Please check UID.")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emulate Results</title>
</head>

<body>
    <h1>PHINMA UPang Student Support Module</h1>
    <h3>P.U.S.S.M</h3>
    <br><br>
    <h1>Emulate Account</h1>
    <form action="emu-results.php" method="POST">
        <label for="">Student UID</label>
        <input type="text" name="student"><br>
        <label for="">Teacher UID</label>
        <input type="text" name="teacher"><br>
        <label for="">Physical</label>
        <input type="number" name="physical" min="0" max="100" id="physical" onchange="calcTotal()" value="0"><br>
        <label for="">Mental</label>
        <input type="number" name="mental" min="0" max="100" id="mental" onchange="calcTotal()" value="0"><br>
        <label for="">Total</label>
        <input type="number" name="total" min="0" max="100" id="total" value="0" readonly><br>
        <input type="hidden" name="timestamp" id="timestamp">
        <input type="submit" value="Submit" name="submit">
    </form>

    <script>
        document.getElementById("timestamp").value = Date.now();

        function empty(value) {
            if (value.length == 0) {
                return true;
            } else {
                return false;
            }
        }

        function calcTotal() {
            var physical = document.getElementById("physical").value;
            var mental = document.getElementById("mental").value;
            if (!empty(physical) && !empty(mental)) {
                // alert(physical + " " + mental + " " + ((physical + mental) / 2).toFixed(2));
                document.getElementById("total").value = ((Number(physical) + Number(mental)) / 2).toFixed(2);
            }
        }
    </script>
</body>

</html>