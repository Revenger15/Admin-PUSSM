<?php
// error_reporting(E_ERROR | E_PARSE);
include '../../includes/dbconfig.php';
$_SESSION['uid'] = 'eOnUIApmfOP7ntvx8iydcm8E82j2';

$userInfo = $database->getReference('users/' . $_SESSION['uid'])->getValue();
$auth = $firebase->createAuth();
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
                    Cum Sex Daddy Lick
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
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; <?php echo $auth->getUser($_SESSION['uid'])->__get('email'); ?></li>
                      <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Position:</strong> &nbsp; <?php echo $userInfo['position']; ?></li>
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
                        <label class="mb-0" for="">Fullname</label>
                        <input type="textfield" class="form-control ps-2" id="name">
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Gender</label>
                        <select class="form-control ps-2" id="gender">
                          <option value="" selected>-select-</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                          <option value="Prefer-not-to-say">Prefer not to say</option>
                        </select>
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Contact Number</label>
                        <input type="tel" class="form-control ps-2" id="contactnumber">
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Email</label>
                        <input type="email" class="form-control ps-2" id="email">
                      </div>
                      <div class="form-group mt-1">
                        <label class="mb-0" for="">Position</label>
                        <input type="textfield" class="form-control ps-2" id="position">
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