<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>
    Profile
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />
  <!-- Nucleo Icons -->
  <link href="../../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-200">
  <div class="main-content position-relative bg-gray-100 max-height-vh-100 h-100">
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
                Silver Swan
              </h5>
              <p class="mb-0 font-weight-normal text-sm">
                CSDL
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
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">Full Name:</strong> &nbsp; Silver S. Swan</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Gender:</strong> &nbsp; Male</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Mobile:</strong> &nbsp; (+63)123 123 1234</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Email:</strong> &nbsp; Datoputi@mail.com</li>
                    <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">Position:</strong> &nbsp; CEO</li>
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
                        <input type="textfield" class="form-control ps-2" id="" >
                    </div>
                    <div class="form-group mt-1">
                        <label class="mb-0" for="">Gender</label>
                        <select class="form-control ps-2" id="" >
                            <option value="" selected>-select-</option>
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
                        <label class="mb-0" for="">Position</label>
                        <input type="textfield" class="form-control ps-2" id="" >
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
  <!--   Core JS Files   -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../../assets/js/material-dashboard.min.js?v=3.0.0"></script>
</body>

</html>