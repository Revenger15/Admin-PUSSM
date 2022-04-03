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

    // Batch Registration
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
            echo '<td>' . $line[6] . '</td>';
            echo '<td>' . $line[7] . '</td>';
            echo '</tr>';

            $userProperties = [
                'uid' => $line[3],
                'email' => $line[8],
                'password' => $line[9],
                'emailVerified' => true,
            ];

            $createdUser = $auth->createUser($userProperties);

            $usersRef->getChild($line[3])->update([
                'lastName' => $line[0],
                'firstName' => $line[1],
                'middleName' => $line[2],
                'empNo' => $line[3],
                'gender' => $line[4],
                'subj' => $line[5],
                'sect' => $line[6],
                'cNo' => $line[7],
            ]);

            $database->getReference("sspcoord")->update([
                $line[3] => $line[3]
            ]);

            // print_r($line);
            // echo '<br><br>';
        }
        fclose($file);

    // Single Registration
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
            'gender' => $_POST['gender'],
            'dept' => $_POST['dept'],
        ]);

        $database->getReference("sspcoord")->update([
            $createdUser->uid => $createdUser->uid
        ]);
    }
    ?>
</table>