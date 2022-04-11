<?php
include '../../includes/dbconfig.php';
session_start();

if (isset($_POST['action'])) {
  $action = $_POST['action'];
  $uid = $_POST['uid'];
  if ($action == "delete") {
    $auth = $firebase->createAuth();
    $dbUser = $database->getReference('users');
    $dbCoords = $database->getReference('system/sspcoord');

    $dbUser->getChild($uid)->set(null);
    $dbCoords->getChild($uid)->set(null);
    $auth->deleteUser($uid);
    exit();
  }
} elseif (isset($_POST['page'])) {
  $page = $_POST['page'];
  $search = $_POST['search'];
  $nEntries = $_POST['nEntries'];

  if (!function_exists('fetchData')) {
    function fetchData($page, $search, $nEntries)
    {
      include '../../includes/dbconfig.php';

      $dbUser = $database->getReference('users');
      $dbCoords = $database->getReference('system/sspcoord');
      $list = $dbCoords->getValue();
      $userData = [];
      $filteredData = [];

      $numA = ($nEntries == 5) ? "selected" : "";

      //Get user data
      if ($list != '') {
        foreach ($list as $key => $value) {
          $userData[$value] = $dbUser->getChild($value)->getValue();
        }
      }

      if ($userData != [] && $search != '') {
        foreach ($userData as $uid => $data) {
          foreach ($data as $key => $value) {
            if (str_icontains($value, $search)) {
              $filteredData[$uid] = $data;
            }
          }
        }
      } else {
        $filteredData = $userData;
      }

      echo '
      <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
        <div class="bg-gradient-faded-success  shadow-dark border-radius-lg pt-4 pb-3">
          <h6 class="text-white text-capitalize ps-3">Lists of SSP CO Teachers</h6>
        </div>
      </div>
      <div class="card-body px-0 pb-2">
        <div class="table-responsive p-0">
      <table class="table align-items-center justify-content-center mb-0">
      <thead>
        <tr>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"></th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee ID</th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
          <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2"></th>
          <th></th>
        </tr>
      </thead>
      <tbody>
      ';

      if ($filteredData != []) {
        $numChild = count($filteredData);
        $tPage = ceil($numChild / $nEntries);
        $page = ($page <= $tPage && $page > 0) ? $page : 1;

        $pagedData = array_slice($filteredData, ($page - 1) * $nEntries);

        foreach ($pagedData as $uid => $data) {
          echo '
          <tr>
          <td>
            <div class="d-flex px-2 py-1">
              <div>
                <img src="../../assets/img/ficon.png" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
              </div>
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm text-start">' . $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'] . '</h6>
                <p class="text-xs text-secondary mb-0">' . $data['email'] . '</p>
              </div>
            </div>
          </td>
          <td>
            <p class="text-xs font-weight-bold mb-0"></p>
          </td>
          <td>
            <p class="text-xs font-weight-bold mb-0">' . $uid . '</p>
          </td>
          <td>
            <p class="text-xs font-weight-bold mb-0">' . $data['department'] . '</p>
          </td>
          <td class="align-middle">
          </td>
          <td>
            <ul class="list-unstyled mb-0 d-flex">
              <li><a onclick="deleteUser(\'$uid\', \'' . $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'] . '\')" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
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
            <button type="button" class="btn btn-outline-success mt-2 ms-1 mb-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"></path>
              </svg> Print
            </button>
          </div>
          <div class="float-left pagination">
            <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="entries">
              <option value="3">3 entries</option>
              <option value="5" ' . $numA . '>5 entries</option>
            </select>
          </div>
          <div class="float-right pagination">
            <ul class="pagination">';

        // Pagination <<
        echo '<li class="page-item"><a class="page-link"';
        if ($page == 1) {
          echo ' style="pointer-events: none;"';
        }
        echo ' aria-label="previous page" onclick="loadData(' . $page - 1 . ', \'' . $search . '\');">« Prev</a></li>';

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
          echo 'aria-label="to page ' . $x . '"  onclick="loadData(' . $x . ', \'' . $search . '\');">' . $x . '</a></li>';
        }

        // Pagination >>
        echo '<li class="page-item"><a class="page-link"';
        if ($page == $tPage) {
          echo ' style="pointer-events: none;"';
        }
        echo ' aria-label="next page" onclick="loadData(' . $page + 1 . ', \'' . $search . '\');">Next »</a></li>
          </ul>
        </div>
      </div>';
      } else {
        echo '
        <tr>
          <td colspan="5">No data found</td>
        </tr>
        </tbody>
    </table>
  </div>
</div>
<div class="fixed-table-pagination">
  <div class="float-left pagination">
    <button type="button" class="btn btn-outline-success mt-2 ms-1 mb-1" disabled>
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"></path>
      </svg> Print
    </button>
  </div>
  <div class="float-left pagination">
    <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="entries">
      <option value="3">3 entries</option>
      <option value="5" ' . $numA . '>5 entries</option>
    </select>
  </div>
  <div class="float-right pagination">
    <ul class="pagination">
      <li class="page-item"><a class="page-link" style="pointer-events: none;" aria-label="previous page" href="">« Prev</a></li>
      <li class="page-item active bg-gradient-faded-success-vertical border-radius-2xl"><a class="page-link" style="pointer-events: none;" aria-label="to page 1" href="">1</a></li>
      <li class="page-item"><a class="page-link" style="pointer-events: none;" aria-label="next page" href="">Next »</a></li>
    </ul>
  </div>
</div>
        ';
      }
      exit();
    }
  }

  fetchData($page, $search, $nEntries);
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
    CO SSP
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
        <span class="ms-1 font-weight-bold text-white fs-2">Create</span>
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
          <a class="nav-link text-whitee active bg-gradient-faded-dark-vertical" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assignment</i>
            </div>
            <span class="nav-link-text ms-1">CO Teachers</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="testbank.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">folder</i>
            </div>
            <span class="nav-link-text ms-1">Test Bank</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="assessment.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Assessment</span>
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
        <li class="nav-item">
          <a class="nav-link dropdown-toggle pt-1 px-0" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="media d-flex align-items-center ps-3 pt-2">
              <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="material-icons opacity-10">settings</i>
              </div>
              <div class="media-body ms-2 text-dark align-items-center d-none d-lg-block">
                <span class="nav-link-text text-white">Setting</span>
              </div>
            </div>
          </a>
          <div class="dropdown-menu dashboard-dropdown dropdown-menu-start mt-2 py-1 bg-light">
            <select class="dropdown-item d-flex align-items-center bg-transparent" aria-label=".form-select-lg example">
              <option selected>School Year</option>
              <option value="1" selected>1SEM | AY-21/22</option>
              <option value="2">2SEM | AY-21/22</option>
              <option value="3">1SEM | AY-22/23</option>
              <option value="4">2SEM | AY-22/23</option>
            </select>
          </div>
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
            <h6 class="font-weight-bolder mb-0">SSP Coordinators</h6>
          </nav>
          <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="col-5 pe-md-3 d-flex align-items-center">
              <div class="input-group input-group-outline">
                <label class="form-label">Type here...</label>
                <input type="text" class="form-control" id="inpSearch">
              </div>
              <button class="btn bg-gradient-success mt-3 ms-1 ps-3 text-center font-monospace text-capitalize" onclick="loadData(1, $('#inpSearch').val());">Search</button>
            </div>
            <ul class="navbar-nav  justify-content-end">
            </ul>
          </div>
        </div>
      </nav>
      <!-- end of nav -->
      <div class="page-header ">
        <div class="container">
          <div class="row">
            <div class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 start-0 text-center justify-content-center flex-column">
              <div class="card my-4" id="dynTable">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                  <div class="bg-gradient-faded-success  shadow-dark border-radius-lg pt-4 pb-3">
                    <h6 class="text-white text-capitalize ps-3">Lists of SSP CO Teachers</h6>
                  </div>
                </div>
                <div class="card-body px-0 pb-2">
                  <div class="table-responsive p-0">
                    <table class="table align-items-center justify-content-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2"></th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee ID</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                          <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2"></th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td colspan="6">
                            Loading data...
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="fixed-table-pagination">
                  <div class="float-left pagination">
                    <button type="button" class="btn btn-outline-success mt-2 ms-1 mb-1">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"></path>
                      </svg> Print
                    </button>
                  </div>
                  <div class="float-left pagination">
                    <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="entries">
                      <option value="3">3 entries</option>
                      <option value="5" selected>5 entries</option>
                    </select>
                  </div>
                  <div class="float-right pagination">
                    <ul class="pagination">
                      <li class="page-item"><a class="page-link" aria-label="previous page" href="">« Prev</a></li>
                      <li class="page-item active bg-gradient-faded-success-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="">1</a></li>
                      <li class="page-item"><a class="page-link" aria-label="to page 2" href="">2</a></li>
                      <li class="page-item"><a class="page-link" aria-label="to page 3" href="">3</a></li>
                      <li class="page-item"><a class="page-link" aria-label="next page" href="">Next »</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column ms-auto me-auto ms-lg-auto me-lg-5">
              <div class="card card-plain">
                <div class="card-header">
                  <h4 class="font-weight-bolder">Create Account</h4>
                  <p class="mb-0">Account for SSP Coordinator</p>
                </div>
                <div class="card-body">
                  <form method="POST" enctype="multipart/form-data" name="teacher-register" id="teacher-register">
                    <p>Fill up:</p>
                    <div class="input-group input-group-outline mb-3">
                      <select class="form-control" name="gender" id="gender" required="">
                        <option value="" selected>-Select Gender-</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Prefer not to say">Prefer not to say</option>
                      </select>
                      <div class="invalid-feedback">
                        Please provide gender.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <select class="form-control" name="dept" id="dept" required="">
                        <option value="" selected>-Select Department-</option>
                        <option value="CITE">CITE</option>
                        <option value="CEA">CEA</option>
                        <option value="CMA">CMA</option>
                        <option value="CAS">CAS</option>
                        <option value="CHS">CHS</option>
                        <option value="CELA">CELA</option>
                        <option value="SHS">SHS</option>
                        <option value="PUCO">PUCO</option>
                      </select>
                      <div class="invalid-feedback">
                        Please provide a valid Department.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">LastName</label>
                      <input type="text" name="lName" class="form-control" required="">
                      <div class="invalid-feedback">
                        Please provide a valid LastName.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">FirstName</label>
                      <input type="text" name="fName" class="form-control" required="">
                      <div class="invalid-feedback">
                        Please provide a valid FirstName.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">MiddleName</label>
                      <input type="text" name="mName" class="form-control" required="">
                      <div class="invalid-feedback">
                        Please provide a valid MiddleName.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Employee Number</label>
                      <input type="text" name="empNo" class="form-control" required="">
                      <div class="invalid-feedback">
                        Please provide a valid Employee Number.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" name="email" class="form-control" required="">
                      <div class="invalid-feedback">
                        Please provide a valid Email.
                      </div>
                    </div>
                    <div class="input-group input-group-outline mb-3">
                      <label class="form-label">Password</label>
                      <input type="password" name="password" id="password" class="form-control" style="border-radius: 0.375em;" required="">
                      <button type="button" style="position:absolute; cursor: pointer; z-index: 3; width: 9%; border: none; background: none; right: 0%; top: 50%; transform: translate(0%, -50%);" id="togglePassword">
                        <i id="password-icon" class="fa fa-eye-slash"></i>
                      </button>
                      <div class="invalid-feedback">
                        Please provide Password.
                      </div>
                    </div>
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
      href: 'PUSSM_COTeacher-Template.csv'
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
    });

    loadData(1, "");

    function loadData(page, search) {
      $.ajax({
        url: "coteacher.php",
        type: "POST",
        data: {
          "page": page,
          "search": search,
          "nEntries": $("#entries").val()
        }
      }).done(function(data) {
        console.log(data);
        $("#dynTable").html(data);
      });
    }

    function deleteUser(uid, name) {
      if (confirm("Are you sure that you want to delete " + name + "(" + uid + ")?")) {
        $.ajax({
          url: "coteacher.php",
          type: "POST",
          data: {
            "uid": uid,
            "action": "delete"
          }
        }).done(loadData("1", ""));
      }
    }

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