<h3>The Following data has been added/updated:</h3>
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
            <?php echo ucfirst($_POST['reg-type']); ?> Number
        </th>
        <th>
            Gender
        </th>
        <th>
            Email
        </th>
        <th>
            Password
        </th>
        <?php
        include '../../includes/dbconfig.php';
        session_start();

        $type = $_POST['reg-type'];
        if ($type == 'student')
            echo '
        <th>
            Department
        </th>
        <th>
            Subject
        </th>
        <th>
            Section
        </th>
        <th>
            Contact Number
        </th>
        ';
        echo '</tr>';

        $csv = $_FILES['batch-csv'];
        $auth = $firebase->createAuth();
        $usersRef = $database->getReference('users/');

        // Batch Registration
        if ($csv['name'] != '') {
            // CSV Registration
            $file = fopen($csv['tmp_name'], 'r');
            $x = 0;
            while (($line = fgetcsv($file)) !== FALSE) {
                if (str_contains($line[0], "Gender")) {
                    continue;
                }
                //$line is an array of the csv elements

                echo '<tr>';
                echo '<td>' . $line[1] . '</td>';
                echo '<td>' . $line[2] . '</td>';
                echo '<td>' . $line[3] . '</td>';
                echo '<td>' . $line[4] . '</td>';
                echo '<td>' . $line[0] . '</td>';
                echo '<td>' . $line[5] . '</td>';
                echo '<td>' . $line[6] . '</td>';

                $info = [
                    'gender' => $line[0],
                    'lastname' => $line[1],
                    'firstname' => $line[2],
                    'middlename' => $line[3],
                    'empNo' => $line[4],
                    'email' => $line[5],
                    'type' => $type
                ];

                if ($type == 'student') {
                    echo '<td>' . $line[7] . '</td>';
                    echo '<td>' . $line[8] . '</td>';
                    echo '<td>' . $line[9] . '</td>';
                    echo '<td>' . $line[10] . '</td>';

                    // Additional information needed for "Users" branch
                    $addInfo = [
                        'idnumber' => $line[4],
                        'contact' => $line[10],
                        'department' => $line[7],
                    ];

                    // Info needed for "data/%AY%/student"
                    $acadInfo = [
                        'subject' => $line[8],
                        'section' => $line[9],
                    ];

                    // Merge additional information with info array.
                    unset($info['empNo']);
                    $info = array_merge($info, $addInfo);

                    // Register $acadInfo to "data/%AY%/student" branch
                    $currAY = $database->getReference('system/current')->getValue();
                    $database->getReference('data/' . $currAY . '/student/' . $line[4])->update($acadInfo);

                    // Register under "data/%AY%/studentList"
                    $database->getReference('data/' . $currAY . '/studentList/' . $line[8] . '/'  . $line[9] . '/' . $line[4])->update([
                        'enrolledAt' => floor(microtime(true) * 1000)
                    ]);
                }

                echo '</tr>';

                $userProperties = [
                    'uid' => $line[4],
                    'email' => $line[5],
                    'password' => $line[6],
                    'emailVerified' => true,
                ];

                // Check if user exists
                try {
                    $user = $auth->getUser($line[4]);
                } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                    $createdUser = $auth->createUser($userProperties);
                }

                // Register User Information under "Users" branch.
                $usersRef->getChild($line[4])->update($info);

                $x++;
            }

            include '../../php/logEvent.php';
            logEvent('Created User', $_SESSION['uid'] . ' has created '. $x . ' new accounts.');

            fclose($file);
            echo '</table>';

            // Single Registration
        } else {

            echo '<tr>';
            echo '<td>' . $_POST['lastname'] . '</td>';
            echo '<td>' . $_POST['firstname'] . '</td>';
            echo '<td>' . $_POST['middlename'] . '</td>';
            echo '<td>' . $_POST['idNum'] . '</td>';
            echo '<td>' . $_POST['gender'] . '</td>';
            echo '<td>' . $_POST['email'] . '</td>';
            echo '<td>' . $_POST['password'] . '</td>';

            $info = [
                'gender' => $_POST['gender'],
                'lastname' => $_POST['lastname'],
                'firstname' => $_POST['firstname'],
                'middlename' => $_POST['middlename'],
                'empNo' => $_POST['idNum'],
                'email' => $_POST['email'],
                'type' => $type
            ];

            if ($type == 'student') {
                echo '<td>' . $_POST['department'] . '</td>';
                echo '<td>' . $_POST['section'] . '</td>';
                echo '<td>' . $_POST['subject'] . '</td>';
                echo '<td>' . $_POST['contact'] . '</td>';

                // Additional information needed for "Users" branch
                $addInfo = [
                    'idnumber' => $_POST['idNum'],
                    'department' => $_POST['department'],
                ];

                // Info needed for "data/%AY%/student"
                $acadInfo = [
                    'subject' => $_POST['subject'],
                    'section' => $_POST['section'],
                ];

                // Merge additional information with info array.
                unset($info['empNo']);
                $info = array_merge($info, $addInfo);

                // Register $acadInfo to "data/%AY%/student" branch
                $currAY = $database->getReference('system/current')->getValue();
                $database->getReference('data/' . $currAY . '/student/' . $_POST['idNum'])->update($acadInfo);

                // Register under "data/%AY%/studentList"
                $database->getReference('data/' . $currAY . '/studentList/' . $_POST['subject'] . '/'  . $_POST['section'] . '/' . $_POST['idNum'])->update([
                    'enrolledAt' => floor(microtime(true) * 1000)
                ]);
            }
            echo '</tr>';

            // Register User to Firebase Auth
            $userProperties = [
                'uid' => $_POST['idNum'],
                'email' => $_POST['email'],
                'password' => $_POST['password'],
                'emailVerified' => true,
            ];

            // Check if user exists
            try {
                $user = $auth->getUser($_POST['idNum']);
            } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                $createdUser = $auth->createUser($userProperties);
            }

            // Register User Information under "Users" branch.
            $usersRef->getChild($_POST['idNum'])->update($info);

            include '../../php/logEvent.php';
            logEvent('Create User', $_SESSION['uid'] . ' has created '. $_POST['idNum']);
        }
        ?>
</table>