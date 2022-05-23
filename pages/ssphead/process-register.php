<h3>The Following data has been added:</h3>
<table class="result">
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
            Gender
        </th>
        <th>
            Department
        </th>
        <th>
            Contact Number
        </th>
        <th>
            Email
        </th>
    </tr>
    <?php
    include '../../includes/dbconfig.php';
    session_start();

    // echo $_POST['file-0'];
    // var_dump($_FILES);
    // var_dump($_POST);
    // $csv = $_FILES['batch-csv'];
    $auth = $firebase->createAuth();
    $usersRef = $database->getReference('users/');

    $userProperties = [
        'uid' => $_POST['empNo'],
        'email' => $_POST['email'],
        'password' => $_POST['password'],
        'emailVerified' => true,
    ];

    $createdUser = $auth->createUser($userProperties);

    $usersRef->getChild($createdUser->uid)->update([
        'email' => $_POST['email'],
        'lastname' => $_POST['lName'],
        'firstname' => $_POST['fName'],
        'middlename' => $_POST['mName'],
        'empNo' => $_POST['empNo'],
        'contact' => $_POST['contact'],
        'department' => $_POST['dept'],
        'gender' => $_POST['gender'],
        'type' => 'sspcoord'
    ]);

    $database->getReference('system/sspcoord')->update([
        $createdUser->uid => $createdUser->uid
    ]);

    $database->getReference('system/logs/'.round(microtime(true) * 1000))->update([
        'title' => 'Created User',
        'message' => $_SESSION['uid'].' has created user '.$createdUser->uid
        ]);

    echo <<<HTML
        <tr>
            <td>
                {$_POST['lName']}
            </td>
            <td>
                {$_POST['fName']}
            </td>
            <td>
                {$_POST['mName']}
            </td>
            <td>
                {$_POST['empNo']}
            </td>
            <td>
                {$_POST['gender']}
            </td>
            <td>
                {$_POST['dept']}
            </td>
            <td>
                {$_POST['contact']}
            </td>
            <td>
                {$_POST['email']}
            </td>
        </tr>
    </table>
    HTML;
    ?>
</table>