<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>
    User Accounts
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
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />


  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body class="g-sidenav-show  bg-dark bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" target="_blank">
        <span class="ms-1 font-weight-bold text-white fs-2">Create User</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-whit" href="dashboard.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="tables.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Assessment</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-whitee active bg-gradient-faded-dark-vertical" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assignment</i>
            </div>
            <span class="nav-link-text ms-1">User Accounts</span>
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="UserLogs.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">person</i>
            </div>
            <span class="nav-link-text ms-1">User Log</span>
          </a>
        </li> 

        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs text-white font-weight-bolder opacity-8">___________________________________</h6>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white " href="../../sign-out.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">login</i>
            </div>
            <span class="nav-link-text ms-1">Log Out</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center" style="background-image: url('../../assets/img/illustrations/illustration-signup.jpg'); background-size: cover;">
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Create Account</h4>
                  <p class="mb-0">Account for the SSP HEAD</p>
                </div>
                <div class="card-body">
                  <form method="POST" enctype="multipart/form-data" name="teacher-register" id="teacher-register">
                    <p>Sign up:</p>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">LastName</label>
                      <input type="text" name="lName" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">FirstName</label>
                      <input type="text" name="fName" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">MiddleName</label>
                      <input type="text" name="mName" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Department</label>
                      <input type="text" name="mName" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Employee Number</label>
                      <input type="text" name="empNo" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="password" id="password" class="form-control" style="border-radius: 0.375em;">
                      <button style="position:absolute; cursor: pointer; z-index: 3; width: 9%; border: none; background: none; right: 0%; top: 50%; transform: translate(0%, -50%);" id="togglePassword">
                        <i id="password-icon" class="fa fa-eye-slash"></i>
                      </button>
                    </div>
                    <!-- Removed. Task 4b - Remove ToS -->
                    <!-- <div class="form-check form-check-info text-start ps-0">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                              I agree the <a href="javascript:;" class="text-dark font-weight-bolder">Terms and Conditions</a>
                            </label>
                          </div> -->
                    <div class="text-center">
                      <button type="button" id="btn-account" class="btn btn-lg bg-gradient-success btn-lg w-100 mt-4 mb-0">Create</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

  <!--   Core JS Files   -->
  <script>
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    const icon = document.querySelector("#password-icon");

    togglePassword.addEventListener("click", function() {
      // toggle the type attribute
      const type = password.getAttribute("type") === "password" ? "text" : "password";
      password.setAttribute("type", type);

      // toggle the icon
      icon.classList.toggle("fa-eye");
      icon.classList.toggle("fa-eye-slash");
      // prevent form submit
      password.focus();
      const form = document.querySelector("form");
      form.addEventListener('submit', function(e) {
        e.preventDefault();
      });
    });
  </script>
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

  <script>
    $('a#dl-template').attr({
      target: '_blank',
      href: 'PUSSM_Teacher-Template.csv'
    });

    $('#btn-account').click(function() {
      $("#processing").modal({
        backdrop: 'static',
        keyboard: false
      });
      $("#processing").modal('show');
      $("#proc-close").hide();

      var data = new FormData($('#teacher-register')[0]);
      jQuery.each(jQuery('#customFile')[0].files, function(i, file) {
        data.append('file-' + i, file);
      });

      console.log("Created FormData, " + [...data.keys()] + " keys in data");

      // console.log(new FormData($('#teacher-register')));
      console.log('started ajax')
      $.ajax({
        url: "process-register.php",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function(data) {
          $("#proc-dialog").addClass('modal-xl');
          $("#proc-close").show();
          $("#content").html(data);
        }
      });
      // $.ajax({
      //   url: "process-register.php",
      //   type: "POST",
      //   dataType: "JSON",
      //   data: data,
      //   processData: false,
      //   contentType: false,
      //   success: function(data, textStatus, xhr) {
      //     console.log(xhr.status);
      //   },
      //   complete: function(xhr, textStatus) {
      //     console.log(xhr.status);
      //   },
      //   error: function(result) {
      //     alert("hello1");
      //     alert(result.status + ' ' + result.statusText);
      //   }
      // }).done(function(data) {
      //   $("#proc-close").show();
      //   $("#content").html(data);
      //   // var modalBody = document.getElementById('user-info-modal');
      //   // modalBody.html(data);
      // }).always(function(jqXHR) {
      //   console.log(jqXHR.status);
      // });
    });

    function csvInput() {
      var filename = $('input[type=file]').val().split('\\').pop();
      if (filename == "") {
        filename = "No file selected (.csv)";
      }
      $("#fileLabel").text(filename);
    }

    function templateModal() {
      $("#instructionBatch").modal('show');
    }
  </script>
  <div class="modal" id="instructionBatch" tabindex="-1" role="dialog" aria-labelledby="instructionBatchLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="instructionBatchLabel">Batch User Sign Up Instructions</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ol>
            <li>Open the downloaded template(.csv) file.</li>
            <li>Enter in the user details required to create an account.
              <ul>
                <li>You may overwrite the first row if you wish. The system will auto detect if it will read the first row or not.</li>
              </ul>
            </li>
            <li>Return to the system and upload the CSV file
              <ul>
                <li>Depending on the amount of data uploaded, this process may take up a long time.</li>
              </ul>
            </li>
          </ol>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal" id="processing" tabindex="-1" role="dialog" aria-labelledby="processingLabel" aria-hidden="true">
    <div class="modal-dialog" id="proc-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="processingLabel">Registration Status</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" id="proc-close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="content">
          <h5 style="text-align: center;">Please wait as we process your request...</h5>
          <div class="loader"></div>

          <style>
            .loader {
              margin: auto;
              text-align: center;
              border: 8px solid #f3f3f3;
              /* Light grey */
              border-top: 8px solid #3498db;
              /* Blue */
              border-radius: 50%;
              width: 60px;
              height: 60px;
              animation: spin 2s linear infinite;
            }

            @keyframes spin {
              0% {
                transform: rotate(0deg);
              }

              100% {
                transform: rotate(360deg);
              }
            }
          </style>
        </div>
      </div>
    </div>
  </div>
</body>

</html>