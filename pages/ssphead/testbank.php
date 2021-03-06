<?php
include '../../includes/dbconfig.php';
session_start();

if (isset($_POST["load"])) {
  $load = $_POST["load"];
  $page = $_POST["page"];
  $search = $_POST["search"];
  $nEntries = $_POST["entries"];

  if (!function_exists('fetchData')) {
    function fetchData($page, $search, $nEntries, $load)
    {
      include '../../includes/dbconfig.php';

      $question = $database->getReference("tbank/" . $load . "q");
      $list = $question->getValue();
      $filteredData = [];

      if ($search != '') {
        foreach ($list as $ts => $q) {
          if (str_icontains($q, $search)) {
            $filteredData[$ts] = $q;
          }
        }
      } else {
        $filteredData = $list;
      }

      $e5  = ($nEntries == 5)  ? 'selected' : '';
      $e15 = ($nEntries == 15) ? 'selected' : '';
      $e25 = ($nEntries == 25) ? 'selected' : '';
      $e50 = ($nEntries == 50) ? 'selected' : '';

      echo '<div class="card-body px-0 pb-2">
      <div class="table-responsive p-0">
          <table class="table align-items-center justify-content-center mb-0">
              <thead>
                  <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">No.</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Question</th>
                      <th></th>
                  </tr>
              </thead>
              <tbody>';

      if ($filteredData != []) {
        $numChild = count($filteredData);
        $tPage = ceil($numChild / $nEntries);
        $page = ($page <= $tPage && $page > 0) ? $page : 1;

        $pagedData = array_slice($filteredData, ($page - 1) * $nEntries, $nEntries, true);
        $cnt = ($page - 1) * $nEntries;

        foreach ($pagedData as $id => $val) {
          echo '<tr>
          <td>
              <h6 class="mb-0 text-sm justify-content-center">' . ++$cnt . '</h6>
          </td>
          <td>
              <p class="text-s mb-0">' . $val . '</p>
          </td>
          <td>
              <ul class="list-unstyled mb-0 d-flex">
                  <li><a onclick="deleteItem(\'' . $id . '\', \'' . $load . '\')" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
              </ul>
          </td>
      </tr>';
        }

        echo '</tbody>
            </table>
        </div>
    </div>
    <div class="fixed-table-pagination">
        <div class="float-left pagination">
            <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="ent' . $load . '">
                <option value="5" ' . $e5 . '>5 entries</option>
                <option value="15" ' . $e15 . '>15 entries</option>
                <option value="25" ' . $e25 . '>25 entries</option>
                <option value="50" ' . $e50 . '>50 entries</option>
            </select>
        </div>
        <div class="float-right pagination">
            <ul class="pagination">';

        echo '<li class="page-item"><a class="page-link"';
        if ($page == 1) {
          echo ' style="pointer-events: none;"';
        }
        echo ' aria-label="previous page" onclick="loadData(' . $page - 1 . ', \'' . $search . '\', \'' . $load . '\');">?? Prev</a></li>';

        // Pagination Number
        for ($x = 1; $x <= $tPage; $x++) {
          echo '<li class="page-item';
          if ($x == $page) {
            echo ' active bg-gradient-faded-success-vertical border-radius-2xl';
          }
          echo '"><a class="page-link" ';
          if ($x == $page) {
            echo ' style="pointer-events: none;"';
          }
          echo 'aria-label="to page ' . $x . '"  onclick="loadData(' . $x . ', \'' . $search . '\', \'' . $load . '\');">' . $x . '</a></li>';
        }

        echo '<li class="page-item"><a class="page-link"';
        if ($page == $tPage) {
          echo ' style="pointer-events: none;"';
        }
        echo ' aria-label="next page" onclick="loadData(' . $page + 1 . ', \'' . $search . '\', \'' . $load . '\');">Next ??</a></li>
          </ul>
        </div>
      </div>';
        // var_dump($list);
      } else {
        echo '<tr>
                <td colspan="3">
                    <h6 class="mb-0 text-sm justify-content-center">No data</h6>
                </td>
            </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="fixed-table-pagination">
            <div class="float-left pagination">
                <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="ent' . $load . '">
                  <option value="5" ' . $e5 . '>5 entries</option>
                  <option value="15" ' . $e15 . '>15 entries</option>
                  <option value="25" ' . $e25 . '>25 entries</option>
                  <option value="50" ' . $e50 . '>50 entries</option>
                </select>
            </div>
            <div class="float-right pagination">
                    <ul class="pagination">
                        <li class="page-item"><a style="pointer-events: none;" class="page-link" aria-label="previous page">?? Prev</a></li>
                        <li class="page-item active bg-gradient-faded-success-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" style="pointer-events: none;">1</a></li>
                        <li class="page-item"><a style="pointer-events: none;" class="page-link" aria-label="next page">Next ??</a></li>
                    </ul>
            </div>
        </div>';
        // var_dump($filteredData);
      }
      exit();
    }
  }

  fetchData($page, $search, $nEntries, $load);
} elseif (isset($_POST['question'])) {
  // var_dump($_POST);
  // var_dump($_FILES);
  // exit();
  $type = $_POST['type'];
  $question = $database->getReference("tbank/" . $type . "q");
  $csv = $_FILES['batch-csv'];
  echo '<h3>The Following data has been added:</h3>
  <table class="result">
      <tr>
          <th>
              Question
          </th>
      </tr>';

  if ($csv['name'] != '') {
    // CSV Registration
    $file = fopen($csv['tmp_name'], 'r');

    $x = 0;
    while (($line = fgetcsv($file)) !== FALSE) {
      if (str_contains($line[0], 'Question') && strlen($line[0]) <= 11) {
        continue;
      }
      //$line is an array of the csv elements

      $question->update([
        round(microtime(true) * 1000) => $line[0],
      ]);

      echo '<tr><td>' . $line[0] . '</td></tr>';
      // print_r($line);
      $x++;
      // echo '<br><br>';
    }

    include '../../php/logEvent.php';
    logEvent('Added Questions', $_SESSION['uid'] . ' has added '. $x .' questions to '.$type);

    fclose($file);
  } else {
    $input = $_POST['question'];
    $question->update([
      round(microtime(true) * 1000) => $input
    ]);
    echo '<tr><td>' . $input . '</td></tr>';

    include '../../php/logEvent.php';
    logEvent('Added a Question', $_SESSION['uid'] . ' has added '. $input .' to '.$type);
  }
  exit();
} elseif (isset($_POST['action'])) {
  echo 'YASSS';
  $action = $_POST['action'];
  if ($action == 'delete') {
    echo 'YESS';
    $cat = $_POST['category'];
    $id = $_POST['id'];
    $database->getReference('tbank/' . $cat . 'q/' . $id)->set(null);

    include '../../php/logEvent.php';
    logEvent('Added Questions', $_SESSION['uid'] . ' has removed '. $id .' questions to '.$cat);
  }
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../../assets/img/favicon.png">
  <title>
    Test Bank
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
        <span class="ms-1 font-weight-bold text-white fs-2">Test Bank</span>
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
          <a class="nav-link text-whitee" href="coteacher.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assignment</i>
            </div>
            <span class="nav-link-text ms-1">CO Teachers</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-faded-dark-vertical" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">folder</i>
            </div>
            <span class="nav-link-text ms-1">Test Bank</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="Assessment.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Assessment</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="archived.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">archive</i>
            </div>
            <span class="nav-link-text ms-1">Archived Data</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="userlogs.php">
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
      <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
        <div class="container-fluid py-1 px-3">
          <img class="icon-shape me-2" src="../../assets\img\favicon.png" alt="">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
              <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Admin</a></li>
              <li class="breadcrumb-item text-sm text-dark active" aria-current="page">PHINMA-UPang Student Support Module</li>
            </ol>
            <h6 class="font-weight-bolder mb-0">Test Bank</h6>
          </nav>
          <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="col-5 pe-md-3 d-flex align-items-center">
              <div class="input-group input-group-outline">
                <label class="form-label">Type here...</label>
                <input type="text" class="form-control" id="inpSearch">
                <select name="tsearch" id="searchTable" class="form-label border-0 bg-transparent mt-advsearch cursor-pointer">
                  <option value="mental">Mental Table</option>
                  <option value="physical">Physical Table</option>
                </select>
              </div>
              <button class="btn bg-gradient-success mt-3 ms-1 ps-3 text-center font-monospace text-capitalize" onclick="loadData(1, $('#inpSearch').val(), $('#searchTable').val());">Search</button>
            </div>
            <ul class="navbar-nav  justify-content-end">
            </ul>
          </div>
        </div>
      </nav>
      <!-- end of nav -->
      <!-- Mental -->
      <div class="page-header h-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                  <div class="bg-gradient-faded-success  shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">List of Mental Questions</h6>
                  </div>
                </div>
                <div id="datamental">
                  <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                      <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Question</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="3">
                              <h6 class="mb-0 text-sm justify-content-center">Loading data</h6>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="fixed-table-pagination">
                    <div class="float-left pagination">
                      <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="entmental">
                        <option value="5" Selected>5 entries</option>
                        <option value="15">15 entries</option>
                        <option value="25">25 entries</option>
                        <option value="50">50 entries</option>
                      </select>
                    </div>
                    <div class="float-right pagination">
                      <ul class="pagination">
                        <li class="page-item"><a class="page-link" aria-label="previous page">?? Prev</a></li>
                        <li class="page-item active bg-gradient-faded-success-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1">1</a></li>
                        <li class="page-item"><a class="page-link" aria-label="next page">Next ??</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Create Mental Questions</h4>
                  <p class="mb-0"></p>
                </div>
                <div class="card-body">
                  <form method="POST" enctype="multipart/form-data" name="men-question" id="men-question">
                    <p>Batch Create:</p>
                    <div class="custom-file">
                      <input type="file" accept=".csv" name="batch-csv" class="custom-file-input" id="men-file" onchange="csvInput('men');">
                      <label class="custom-file-label" for="customFile" id="men-fileLabel">No File Selected (.csv)</label>
                      <a href="#dl-template" data-toggle="modal" data-target="#instructionBatch" style="float: right;">Download Template</a>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                    </div>
                    <p>Single Question:</p>
                    <div class="input-group input-group-outline mb-3">
                      <textarea placeholder="Type here..." class="form-control bg-white" rows="5" id="men-single-question" name="question"></textarea>
                      <input type="hidden" name="type" value="mental">
                    </div>
                    <div class="text-center">
                      <button type="button" onclick="addQuestion('men');" class="btn btn-lg bg-gradient-success btn-lg w-100 mt-4 mb-0">Upload</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Physical -->
      <div class="page-header h-100">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                  <div class="bg-gradient-faded-warning  shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">List of Physical Questions</h6>
                  </div>
                </div>
                <div id="dataphysical">
                  <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                      <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">No.</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Question</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="3">
                              <h6 class="mb-0 text-sm justify-content-center">Loading data</h6>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="fixed-table-pagination">
                    <div class="float-left pagination">
                      <select class="btn btn-outline-warning mt-2 ms-1 mb-1" name="page" id="entphysical">
                        <option value="5" Selected>5 entries</option>
                        <option value="15">15 entries</option>
                        <option value="25">25 entries</option>
                        <option value="50">50 entries</option>
                      </select>
                    </div>
                    <div class="float-right pagination">
                      <ul class="pagination">
                        <li class="page-item"><a class="page-link" aria-label="previous page">?? Prev</a></li>
                        <li class="page-item active bg-gradient-faded-warning-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1">1</a></li>
                        <li class="page-item"><a class="page-link" aria-label="next page">Next ??</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Create Physical Questions</h4>
                  <p class="mb-0"></p>
                </div>
                <div class="card-body">
                  <form method="POST" enctype="multipart/form-data" name="phy-question" id="phy-question">
                    <p>Batch Create:</p>
                    <div class="custom-file">
                      <input type="file" accept=".csv" name="batch-csv" class="custom-file-input" id="phy-file" onchange="csvInput('phy');">
                      <label class="custom-file-label" for="phy-file" id="phy-fileLabel">No File Selected (.csv)</label>
                      <a href="#dl-template" data-toggle="modal" data-target="#instructionBatch" style="float: right;">Download Template</a>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                    </div>
                    <div class="input-group input-group-outline mb-3">
                    </div>
                    <p>Single Question:</p>
                    <div class="input-group input-group-outline mb-3">
                      <textarea placeholder="Type here..." class="form-control bg-white" rows="5" id="phy-single-question" name="question"></textarea>
                      <input type="hidden" name="type" value="physical">
                    </div>
                    <div class="text-center">
                      <button type="button" onclick="addQuestion('phy');" class="btn btn-lg bg-gradient-warning btn-lg w-100 mt-4 mb-0">Upload</button>
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
    function resetDialog() {
      def_state = `
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
      `;

      $('#content').html(def_state);
    }

    function addQuestion(category) {
      resetDialog();

      $("#processing").modal({
        backdrop: 'static',
        keyboard: false
      });

      $("#processing").modal('show');
      $("#proc-close").hide();

      var data = new FormData($('#' + category + '-question')[0]);
      jQuery.each(jQuery('#' + category + '-file')[0].files, function(i, file) {
        data.append('file-' + i, file);
      });

      console.log("Created FormData, " + [...data.keys()] + " keys in data");

      // console.log(new FormData($('#teacher-register')));
      console.log('started ajax')
      $.ajax({
        url: "testbank.php",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST', // For jQuery < 1.9
        success: function(data) {
          $("#proc-close").show();
          $("#content").html(data);
        }
      }).done(function(data) {
        console.log(data);
        if(category == 'phy') {
          loadData(1, "", "physical");
        } else {
          loadData(1, "", "mental");
        }
        // Clear input
        $('#' + category + '-file').val(null);
        csvInput(category);
      });
    }

    function deleteItem(id, cat) {
      if (confirm("Are you sure you want to delete this question?")) {
        $.ajax({
          url: "testbank.php",
          type: "POST",
          data: {
            "id": id,
            "category": cat,
            "action": "delete"
          }
        }).done(function(data) {
          console.log(data);
          loadData(1, '', cat);
        });
      }
    }

    function csvInput(category) {
      var filename = $('#'+category+'-file').val().split('\\').pop();
      if (filename == "") {
        filename = "No file selected (.csv)";
      }
      $("#"+category+"-fileLabel").text(filename);
    }

    function templateModal() {
      // $("#instructionBatch").modal('show');
    }

    loadData(1, "", "physical");
    loadData(1, "", "mental");

    function loadData(page, search, category) {
      $.ajax({
        url: "testbank.php",
        type: "POST",
        data: {
          "load": category,
          "page": page,
          "search": search,
          "entries": $("#ent" + category).val()
        }
      }).done(function(data) {
        console.log(data);
        $("#data" + category).html(data)
      });
    }
  </script>
  <div class="modal" id="instructionBatch" tabindex="-1" role="dialog" aria-labelledby="instructionBatchLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="instructionBatchLabel">Batch User Sign Up Instructions</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
          <a href="PUSSM_questions-Template.csv" download="PUSSM_questions-Template.csv" target="_blank" class="btn btn-secondary dl-template">Download Template</a>
          <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">Close</button>
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
  <?php
  include 'acadYear.php';
  ?>
</body>

</html>