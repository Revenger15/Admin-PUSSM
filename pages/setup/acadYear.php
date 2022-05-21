<?php
// var_dump($_POST);
if (isset($_POST['year'])) {
    include '../../includes/dbconfig.php';
    $yr = $_POST['year'];
    $sem = $_POST['sem'];

    if ($database->getReference('data/' . $yr . '-' . $sem)->getSnapshot()->exists()) {
        echo '<script>
            alert("AY and Semester already exists in the server.");
            window.location.href = "'. $_SERVER['HTTP_REFERER'] .'";
        </script>';
        exit();
        // header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        $database->getReference('data/' . $yr . '-' . $sem)->update([
            'createAt' => round(microtime(true) * 1000),
            'AY' => $yr,
            'sem' => $sem
        ]);

        $database->getReference('system/AY/' . $yr . '-' . $sem)->set($yr . '-' . $sem);
        $database->getReference('system/current')->set($yr . '-' . $sem);

        include '../../php/logEvent.php';
        logEvent('Academic Year', $_SESSION['uid'] . ' has created and changed the academic year: ' . $yr . '-' . $sem);
        // header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
    exit();
}
?>

<div class="modal" id="addYear" tabindex="-1" role="dialog" aria-labelledby="addYearLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addYearLabel">Add Period</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="acadForm" method="POST" action="acadYear.php" role="form" autocomplete="off">
                    <label for="yr"></label>
                    <input type="number" id="yr" name="year" placeholder="1920">
                    <label for="sem"></label>
                    <select name="sem" id="sem">
                        <option value="A">First Semester</option>
                        <option value="B">Second Semester</option>
                        <option value="C">Third Semester (Summer)</option>
                    </select>
                    <center>
                        <div class="form-group pt-2">
                          <button type="button" id="submit" class="btn btn-success btn-lg float-right">Add</button>
                        </div>
                    </center>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $('#submit').click(function() {
        $.ajax({
            url: 'acadYear.php',
            method: 'POST',
            type: 'POST',
            data: $('#acadForm').serialize()
        }).done(function(data) {
            console.log(data);
            if(!data.includes('error')) {
                alert('Setup Success! Now transferring to dashboard');
                window.location = '../ssphead/dashboard.php';
            } else {
                console.log(data);
                alert('An error has occurred! Please check local DevTools Console.');
            }
        });
    });
    function acadYear(select) {
        var sel = select.value;
        if (sel == 'add') {
            $('#addYear').modal('show');
        } else {
            setCookie("AY", sel, 14);
            location.reload();
        }
    }
</script>