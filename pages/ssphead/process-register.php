<h3>The Following data has been added:</h3>
<table>
    <tr>
        <th>
            Last Name
        </th>
        <th>
            First Name
        </th>
        <th>
            Middle Name
        </th>
        <th>
            Employee Number
        </th>
        <th>
            Email
        </th>
        <th>
            Password
        </th>
    </tr>
    <?php
    include '../../includes/dbconfig.php';
    session_start();

    // echo $_POST['file-0'];
    // var_dump($_FILES);
    // var_dump($_POST);
    $csv = $_FILES['batch-csv'];
    $auth = $firebase->createAuth();
    $usersRef = $database->getReference('users/');

    if ($csv['name'] != '') {
        // CSV Registration
        $file = fopen($csv['tmp_name'], 'r');
        while (($line = fgetcsv($file)) !== FALSE) {
            if ($line[3] == 'Employee Number') {
                continue;
            }
            //$line is an array of the csv elements

            echo '<tr>';
            echo '<td>' . $line[0] . '</td>';
            echo '<td>' . $line[1] . '</td>';
            echo '<td>' . $line[2] . '</td>';
            echo '<td>' . $line[3] . '</td>';
            echo '<td>' . $line[4] . '</td>';
            echo '<td>' . $line[5] . '</td>';
            echo '</tr>';

            // $userProperties = [
            //     'uid' => $line[3],
            //     'email' => $line[4],
            //     'password' => $line[5],
            //     'emailVerified' => true,
            // ];

            // $createdUser = $auth->createUser($userProperties);

            $usersRef->getChild($line[3])->update([
                'lastName' => $line[0],
                'firstName' => $line[1],
                'middleName' => $line[2],
                'empNo' => $line[3],
                'password' => $line[4]
            ]);


            // print_r($line);
            // echo '<br><br>';
        }
        fclose($file);
    } else {
        $userProperties = [
            'uid' => $_POST['empNo'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'emailVerified' => true,
        ];

        $createdUser = $auth->createUser($userProperties);

        $usersRef->getChild($createdUser->uid)->update([
            'email' => $_POST['email'],
            'lastName' => $_POST['lName'],
            'firstName' => $_POST['fName'],
            'middleName' => $_POST['mName'],
            'empNo' => $_POST['empNo'],
        ]);
    }
    ?>
</table>