<?php
include '../../includes/dbconfig.php';
if (session_status() !== PHP_SESSION_NONE) {
  session_start();
}
$_SESSION['uid'] = 'eOnUIApmfOP7ntvx8iydcm8E82j2';

$dbUser = $database->getReference('users/' . $_SESSION['uid']);
$userInfo = $dbUser->getValue();
$auth = $firebase->createAuth();
$email = $auth->getUser($_SESSION['uid'])->__get('email');

if (isset($_POST['email'])) {
  $firstname = $_POST['firstname'];
  $middlename = $_POST['middlename'];
  $lastname = $_POST['lastname'];
  $gender = $_POST['gender'];
  $contact = $_POST['contactnumber'];
  $newEmail = $_POST['email'];

  if ($email != $newEmail) {
    $auth->changeUserEmail($_SESSION['uid'], $newEmail);
    $email = $newEmail;
  }

  $dbUser->update([
    "firstname" => $firstname,
    "middlename" => $middlename,
    "lastname" => $lastname,
    "gender" => $gender,
    "contact" => $contact,
    "email" => $email,
  ]);
  echo '
    <script>
      alert("Updated Information!");
      if (\'referrer\' in document) {
        window.location = document.referrer;
        /* OR */
        //location.replace(document.referrer);
    } else {
        window.history.back();
    }
    </script>';
  exit();
}
?>
<div class="modal" id="profile" tabindex="-1" role="dialog" aria-labelledby="profileLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileLabel">User Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- End Navbar -->
        <div class="container-fluid px-2 px-md-4">
          <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../../assets/img/ivancik.jpg');">
            <span class="mask  bg-gradient-faded-dark opacity-6"></span>
          </div>
          <div class="card card-body mx-3 mx-md-4 mt-n6">
            <div class="row gx-4 mb-2">
              <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                  <img src="../../assets/img/micon.png" alt="profile_image" class="w-100 border-radius-lg shadow-sm">
                </div>
              </div>
              <div class="col-auto my-auto">
                <div class="h-100">
                  <h5 class="mb-1">
                    <?php echo $userInfo['firstname'] . ' ' . $userInfo['middlename'] . ' ' . $userInfo['lastname']; ?>
                  </h5>
                  <p class="mb-0 font-weight-normal text-sm">
                    SSP HEAD
                  </p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-xl-6">
                <div class="card card-plain h-100">
                  <div class="card-header pb-0 p-3 bg-gradient-faded-white">
                    <div class="row">
                      <div class="col-md-auto d-flex align-items-center">
                        <h6 class="mb-2">Profile Information</h6>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-3">
                    <ul class="list-group">
                      <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; <?php echo $userInfo['firstname'] . ' ' . $userInfo['middlename'] . ' ' . $userInfo['lastname']; ?></li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Gender:</strong> &nbsp; <?php echo $userInfo['gender']; ?></li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; <?php echo $userInfo['contact']; ?></li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; <?php echo $email ?></li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark"></strong></li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-12 col-xl-6">
                <div class="card card-plain h-100">
                  <div class="card-header pb-0 p-3 bg-gradient-faded-white">
                    <div class="row">
                      <div class="col-md-auto d-flex align-items-center">
                        <h6 class="mb-2">Edit Information</h6>
                      </div>
                    </div>
                  </div>
                  <div class="card-body p-3">
                  <form class="form" role="form" autocomplete="off">
                    <div class="form-group mt-1">
                        <label class="mb-0" for="">First Name</label>
                        <input type="text" name="firstname" class="form-control ps-2" id="firstname" value="<?php echo $userInfo['firstname'] ?>" required>
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Middle Name</label>
                        <input type="text" name="middlename" class="form-control ps-2" id="middlename" value="<?php echo $userInfo['middlename'] ?>" required>
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Last Name</label>
                        <input type="text" name="lastname" class="form-control ps-2" id="lastname" value="<?php echo $userInfo['lastname'] ?>" required>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Gender</label>
                          <select class="form-control ps-2" id="" >
                              <option value="" selected>-select</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Prefer-not-to-say">Prefer not to say</option>
                          </select>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Number</label>
                          <input type="textfield" class="form-control ps-2" id="" >
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Email</label>
                          <input type="textfield" class="form-control ps-2" id="" >
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Department</label>
                          <select class="form-control ps-2" id="" >
                              <option value="" selected>-select</option>
                              <option value="CITE">CITE</option>
                              <option value="CEA">CEA</option>
                              <option value="CAS">CAS</option>
                              <option value="CHS">CHS</option>
                              <option value="CSS">CSS</option>
                              <option value="PUCO">PUCO</option>
                          </select>
                      </div>
                      <center>
                      <div class="form-group pt-2">
                          <button type="submit" class="btn btn-success btn-lg float-right">Update</button>
                      </div>
                      </center>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $("#gender").val("<?php echo $userInfo['gender']?>");
</script>