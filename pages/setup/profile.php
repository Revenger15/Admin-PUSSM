<?php

use Firebase\Auth\Token\Exception\InvalidToken;

include '../../includes/dbconfig.php';
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

$auth = $firebase->createAuth();

if (isset($_POST['email'])) {
  $firstname = $_POST['firstname'];
  $middlename = $_POST['middlename'];
  $lastname = $_POST['lastname'];
  $gender = $_POST['gender'];
  $contact = $_POST['contact'];
  $email = $_POST['email'];
  $empNo = $_POST['empNo'];
  $password = $_POST['password'];

  $userProperties = [
    'email' => $email,
    'password' => $password,
    'uid' => $empNo,
    'emailVerified' => true,
  ];

  $new = $auth->createUser($userProperties);
  $dbUser = $database->getReference('users/' . $empNo);
  $_SESSION['uid'] = $empNo;

  // var_dump($up);
  // var_dump($_SESSION);
  // var_dump($updatedInfo);

  $dbUser->update([
    "firstname" => $firstname,
    "middlename" => $middlename,
    "lastname" => $lastname,
    "gender" => $gender,
    "contact" => $contact,
    "email" => $email,
    "type" => "ssphead"
  ]);

  try {
    $signInResult = $auth->signInAsUser($empNo);
    $token = $signInResult->idToken();
    try {
      $verIdToken = $auth->verifyIdToken($token);
      $uid = $verIdToken->claims()->get('sub');

      $_SESSION['uid'] = $uid;
      $_SESSION['token'] = $token;
    } catch (InvalidToken $e) {
        echo '<script>alert("The token is invalid!")</script>';
    } catch (\InvalidArgumentException $e) {
        echo '<script>alert("The token could not be parsed!")</script>';
    }
  } catch (Exception $e) {
    echo '<script>alert("Invalid Email and/or Password!"); window.location = "../";</script>';
  }

  include '../../php/logEvent.php';
  logEvent('Profile Update', $_SESSION['uid'] . ' has updated their own profile.');

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
                    NULL
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
                      <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; NULL</li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Gender:</strong> &nbsp; NULL</li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; NULL</li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; NULL</li>
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
                  <form class="form" role="form" autocomplete="off" id="profileForm">
                    <div class="form-group mt-1">
                        <label class="mb-0" for="">First Name</label>
                        <input type="text" name="firstname" class="form-control ps-2" id="firstname" value="" required>
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Middle Name</label>
                        <input type="text" name="middlename" class="form-control ps-2" id="middlename" value="" required>
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Last Name</label>
                        <input type="text" name="lastname" class="form-control ps-2" id="lastname" value="" required>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Gender</label>
                          <select class="form-control ps-2" id="gender" name="gender" required>
                              <option value="" selected>-select</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Prefer-not-to-say">Prefer not to say</option>
                          </select>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Contact Number</label>
                          <input type="textfield" class="form-control ps-2" id="cNo" name="contact" required>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Email</label>
                          <input type="textfield" class="form-control ps-2" id="email"  name="email" required>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Password</label>
                          <input type="password" class="form-control ps-2" id="password"  name="password" required>
                      </div>
                      <div class="form-group mt-1">
                          <label class="mb-0" for="">Employee Number</label>
                          <input type="textfield" class="form-control ps-2" id="empNo"  name="empNo" required>
                      </div>
                      <center>
                      <div class="form-group pt-2">
                          <button type="button" id="updateData" class="btn btn-success btn-lg float-right">Update</button>
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
  $('#updateData').click(function() {
    $.ajax({
      url: 'profile.php',
      method: 'POST',
      type: 'POST',
      data: $('#profileForm').serialize()
    }).done(function(data) {
      console.log(data);
      if(!data.includes('error')) {
        $('#profile').modal('toggle');
        $('#addYear').modal('show');
      } else {
        console.log(data);
        alert('An error has occurred! Please check local DevTools Console.');
      }
    });
  });
</script>