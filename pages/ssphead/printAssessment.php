<?php
include '../../includes/dbconfig.php';
$cat = $_GET['category'];
$ay = $_GET['ay'];
$dbRef = $database->getReference('data/' . $ay . '/' . $cat);
$assList = $dbRef->getValue();

$database->getReference('system/logs/' . round(microtime(true) * 1000))->update([
    'title' => 'Exported Data',
    'message' => $_SESSION['uid'] . ' has exported the data for ' . $cat . ' assessment'
]);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Assessment</title>
    <style>
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            text-align: center;
        }

        table {
            width: 100%;
            table-layout: fixed;
        }

        @media print {
            .pagebreak {
                page-break-before: always;
            }

            /* page-break-after works, as well */
        }
    </style>
</head>

<body>
    <?php
    foreach ($assList as $ts => $data) {
        $date = date('F d, Y', floor($ts / 1000));
        $cat = ucfirst($cat);
        echo <<<HTML
            <h2>PHINMA UPang Student Support Module</h2>
            <h4>PUSSM</h4>
            <hr>
            <h3>{$cat}</h3>
            <br>
            <table>
                <tr>
                    <td>Full Name</td>
                    <td>{$data['firstname']} {$data['middlename']} {$data['lastname']}</td>
                    <td>Date</td>
                    <td>{$date}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{$data['email']}</td>
                    <td>Contact Number</td>
                    <td>{$data['cNo']}</td>
                </tr>
                <tr>
                    <td>Subject</td>
                    <td>{$data['sub']}</td>
                    <td>Section</td>
                    <td>{$data['sec']}</td>
                </tr>
                <tr><td>&nbsp</td></tr>
                <tr>
                    <th colspan="2">Physical Score</th>
                    <th colspan="2">Mental Score</th>
                </tr>
                <tr>
                    <th colspan="2">{$data['physical']}</th>
                    <th colspan="2">{$data['mental']}</th>
                </tr>
            </table>
        HTML;
        echo '<div class="pagebreak"> </div>';
    }
    ?>
    <script>
        window.onload = function(e) {
            window.print();
            if (confirm("Do you wish to hide the exported data from the pending assessments page?")) {
                ay = getCookie('AY') ? getCookie('AY') : '<?php echo $database->getReference('system/current')->getValue(); ?>';
                $.ajax({
                    url: 'Assessment.php',
                    method: 'POST',
                    type: 'POST',
                    data: {
                        'action': 'export',
                        'ay': ay
                    }
                }).done(function(data) {
                    alert('Data has been hidden. You may now close the window!');
                });
            }
        }
    </script>
</body>

</html>