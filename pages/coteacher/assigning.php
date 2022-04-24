<?php
include '../../includes/dbconfig.php';
session_start();

if (isset($_POST['page'])) {
  $page = $_POST['page'];
  $search = $_POST['search'];
  $entries = $_POST['entries'];
  $category = $_POST['cat'];

  if (!function_exists('fetchData')) {
    function fetchData($page, $search, $nEntries, $category)
    {
      include '../../includes/dbconfig.php';

      $dbInstructor = $database->getReference('system/sspcoord/' . $_SESSION['uid'] . '/advisers/');
      $dbUsers = $database->getReference('users');
      $currAY = $database->getReference('system/current')->getValue();
      $uidList = $dbInstructor->getValue();
      $userData = [];
      $filteredData = [];

      $e5  = ($nEntries == 5)  ? 'selected' : '';
      $e15 = ($nEntries == 15) ? 'selected' : '';
      $e25 = ($nEntries == 25) ? 'selected' : '';
      $e50 = ($nEntries == 50) ? 'selected' : '';

      // Fetch User data based from List
      if ($uidList != '') {
        foreach ($uidList as $key => $value) {
          $userData[$value] = $dbUsers->getChild($value)->getValue();
          $subs = $database->getReference('data/' . $currAY . '/users/' . $value . '/subjects/')->getSnapshot();
          if ($subs->hasChildren()) {
            $listSub = $subs->getValue();
            $userData[$value]['subject'] = array_keys($listSub);
            foreach ($listSub as $sub => $sect) {
              $userData[$value]['section'] = array_keys($sect);
            }
          }
        }
      }

      // Search
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

      if ($category == 'assign') {
        $theme = 'dark';
        $temp = $filteredData;
        unset($filteredData);

        foreach ($temp as $uid => $data) {
          if (array_key_exists('subject', $data) || array_key_exists('section', $data)) {
            unset($temp[$uid]);
          }
        }

        $filteredData = $temp;

        echo '
        <div class="mt-3 mb-4">
          <div class="col-lg-12 mt-4 mt-lg-0">
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive mb-0 bg-white">
                  <table class="table align-items-center justify-content-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Employee ID</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
        ';
      } else {
        $theme = 'success';
        echo '
        <div class="card-body px-0 pb-2">
          <div class="table-responsive p-0">
            <table class="table align-items-center justify-content-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder  opacity-7 ps-2">Section</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Reassign</th>
                </tr>
              </thead>
              <tbody>
        ';
      }

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
                <img src="../../assets/img/micon.png" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
              </div>
              <div class="d-flex flex-column justify-content-center">
                <h6 class="mb-0 text-sm"> ' . $data['firstname'] . ' ' . $data['middlename'] . ' ' . $data['lastname'] . ' </h6>
                <p class="text-xs text-secondary mb-0">' . $data['email'] . '</p>
              </div>
            </div>
          </td>';
          if ($category == 'assign') {
            echo '
                <td>
                  <button type="button" class="btn btn-' . $theme . ' mt-2 ms-1 mb-1" onclick="assignUser(\'' . $uid . '\')">
                    Assign
                  </button>
                </td>
              </tr>
            ';
          } else {
            if (!isset($data['section'])) {
              $sect = 'NOT YET ASSIGNED';
              $subj = 'NOT YET ASSIGNED';
            } else {
              $sect = '';
              $subj = '';
              foreach ($data['section'] as $k => $v) {
                $sect .= $v . ', ';
              }

              foreach ($data['subject'] as $k => $v) {
                $subj .= $v . ', ';
              }
              $sect = substr($sect, 0, -2);
              $subj = substr($subj, 0, -2);
            }
            echo '
                <td>
                <p class="text-xs font-weight-bold mb-0">' . $subj . '</p>
              </td>
              <td>
                <span class="text-xs font-weight-bold mb-0">' . $sect . '</span>
              </td>
              <td>
                <button type="button" class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1"  onclick="editAssign(\'' . $uid . '\')">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill text-' . $theme . '" viewBox="0 0 16 16">
                    <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001z" />
                  </svg>
                </button>
              </td>
            </tr>
            ';
          }
          echo '
                    </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="fixed-table-pagination">
            <div class="float-left pagination">
              <button type="button" class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                  <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
                  <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"></path>
                </svg> Print
              </button>
            </div>
            <div class="float-left pagination">
              <select class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1" name="page" id="ent' . $category . '">
                <option value="5"  ' . $e5 . '>5 entries</option>
                <option value="15" ' . $e15 . '>15 entries</option>
                <option value="25" ' . $e25 . '>25 entries</option>
                <option value="50" ' . $e50 . '>50 entries</option>
              </select>
            </div>
            <div class="float-right pagination">
              <ul class="pagination">
          ';

          // Pagination <<
          echo '<li class="page-item"><a class="page-link"';
          if ($page == 1) {
            echo ' style="pointer-events: none;"';
          }
          echo ' aria-label="previous page" onclick="loadData(' . $page - 1 . ', \'' . $search . '\', \'' . $category . '\');">« Prev</a></li>';

          // Pagination Number
          for ($x = 1; $x <= $tPage; $x++) {
            echo '<li class="page-item';
            if ($x == $page) {
              echo ' active bg-gradient-faded-' . $theme . '-vertical border-radius-2xl';
            }
            echo '"><a class="page-link" ';
            if ($x == $page) {
              echo ' style="pointer-events: none;"';
            }
            echo 'aria-label="to page ' . $x . '"  onclick="loadData(' . $x . ', \'' . $search . '\', \'' . $category . '\');">' . $x . '</a></li>';
          }

          // Pagination >>
          echo '<li class="page-item"><a class="page-link"';
          if ($page == $tPage) {
            echo ' style="pointer-events: none;"';
          }
          echo ' aria-label="next page" onclick="loadData(' . $page + 1 . ', \'' . $search . '\', \'' . $category . '\');">Next »</a></li>
              </ul>
            </div>
          </div>';
        }
      } else {
        $colspan = ($category == 'assign') ? 2 : 4;
        echo '
                          <tr>
                            <td colspan="' . $colspan . '">
                              No Data Found
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="fixed-table-pagination">
              <div class="float-left pagination">
                <button type="button" class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                    <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
                    <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"></path>
                  </svg> Print
                </button>
              </div>
              <div class="float-left pagination">
                <select class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1" name="page" id="entassign">
                  <option value="5"  ' . $e5 . '>5 entries</option>
                  <option value="15" ' . $e15 . '>15 entries</option>
                  <option value="25" ' . $e25 . '>25 entries</option>
                  <option value="50" ' . $e50 . '>50 entries</option>
                </select>
              </div>
              <div class="float-right pagination">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" style="pointer-events: none;" aria-label="previous page" href="">« Prev</a></li>
                  <li class="page-item active bg-gradient-faded-' . $theme . '-vertical border-radius-2xl"><a class="page-link" style="pointer-events: none;" aria-label="to page 1" href="">1</a></li>
                  <li class="page-item"><a class="page-link" style="pointer-events: none;" aria-label="next page" href="">Next »</a></li>
                </ul>
              </div>
            </div>
          </div>
        ';
      }
      exit();
    }
  }

  fetchData($page, $search, $entries, $category);
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
    Assigning
  </title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
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

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <link id="pagestyle" href="../../assets/css/material-dashboard.css?v=3.0.0" rel="stylesheet" />
  <style>
    .table td,
    .table th {
      border-top: unset;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" target="_blank">
        <span class="ms-1 font-weight-bold text-white fs-2">Subjects</span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" href="dashboard.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">dashboard</i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white " href="teachers.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assignment</i>
            </div>
            <span class="nav-link-text ms-1">Teachers</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white active bg-gradient-faded-dark-vertical" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
              </svg>
            </div>
            <span class="nav-link-text ms-1">Assigning</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="students.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">assignment</i>
            </div>
            <span class="nav-link-text ms-1">Students</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="useracc.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-person-fill material-icons" viewBox="0 0 16 16">
                <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0zm2 5.755V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1v-.245S4 12 8 12s5 1.755 5 1.755z" />
              </svg>
            </div>
            <span class="nav-link-text ms-1">User Accounts</span>
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
          <a class="nav-link text-white " href="../sign-out.php">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">login</i>
            </div>
            <span class="nav-link-text ms-1">Log Out</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
      <div class="container-fluid py-1 px-3">
        <img class="icon-shape me-2" src="../../assets\img\favicon.png" alt="">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">SSP Coordinator</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">PHINMA-UPang Student Support Module</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Assigning</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="col-5 pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
              <select name="tsearch" id="searchTable" class="form-label border-0 bg-transparent mt-advsearch cursor-pointer">
                <option value="asssign">Assigning Table</option>
                <option value="assigned">Adviser Table</option>
              </select>
            </div>
            <button class="btn bg-gradient-success mt-3 ms-1 ps-3 text-center font-monospace text-capitalize" onclick="loadData(1, $('#inpSearch').val(), $('#searchTable').val());">Search</button>
          </div>
          <ul class="navbar-nav  justify-content-end">
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-faded-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">To be Asign</h6>
              </div>
            </div>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
            <div id="conassign">
              <div class="mt-3 mb-4">
                <div class="col-lg-12 mt-4 mt-lg-0">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="table-responsive mb-0 bg-white">
                        <table class="table align-items-center justify-content-center mb-0">
                          <thead>
                            <tr>
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                              <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th> -->
                              <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">SEM/AY</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td colspan="2">
                                Loading data...
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="fixed-table-pagination">
                <div class="float-left pagination">
                  <button type="button" class="btn btn-outline-dark mt-2 ms-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                      <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"></path>
                      <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"></path>
                    </svg> Print
                  </button>
                </div>
                <div class="float-left pagination">
                  <select class="btn btn-outline-dark mt-2 ms-1 mb-1" name="page" id="entassign">
                    <option value="5" Selected>5 entries</option>
                    <option value="15">15 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                  </select>
                </div>
                <div class="float-right pagination">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" aria-label="previous page" href="">« Prev</a></li>
                    <li class="page-item active bg-gradient-faded-dark-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="">1</a></li>
                    <li class="page-item"><a class="page-link" aria-label="to page 2" href="">2</a></li>
                    <li class="page-item"><a class="page-link" aria-label="to page 3" href="">3</a></li>
                    <li class="page-item"><a class="page-link" aria-label="to page 3" href="">...</a></li>
                    <li class="page-item"><a class="page-link" aria-label="to page 3" href="">10</a></li>
                    <li class="page-item"><a class="page-link" aria-label="next page" href="">Next »</a></li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-faded-success shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">List of Advisers</h6>
              </div>
            </div>
            <div id="conassigned">
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center justify-content-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder  opacity-7 ps-2">Section</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-4">Reassign</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="4">
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
                  <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="entassigned">
                    <option value="5" Selected>5 entries</option>
                    <option value="15">15 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                  </select>
                </div>
                <div class="float-left pagination">
                  <!-- <select class="btn btn-outline-success mt-2 ms-1 mb-1" name="page" id="">
                    <option value="">-subject-</option>
                    <option value="" Selected>NST-001</option>
                    <option value="">NST-002</option>
                    <option value="">SSP-001</option>
                    <option value="">SSP-002</option>
                    <option value="">SSP-003</option>
                    <option value="">SSP-004</option>
                    <option value="">SSP-005</option>
                    <option value="">SSP-006</option>
                    <option value="">SSP-007</option>
                    <option value="">SSP-008</option>
                    <option value="">SSP-009</option>
                  </select>
                </div> -->
                  <div class="float-right pagination">
                    <ul class="pagination">
                      <li class="page-item"><a class="page-link" aria-label="previous page" href="">« Prev</a></li>
                      <li class="page-item active bg-gradient-faded-success-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="">1</a></li>
                      <li class="page-item"><a class="page-link" aria-label="to page 2" href="">2</a></li>
                      <li class="page-item"><a class="page-link" aria-label="to page 3" href="">3</a></li>
                      <li class="page-item"><a class="page-link" aria-label="to page 3" href="">...</a></li>
                      <li class="page-item"><a class="page-link" aria-label="to page 3" href="">10</a></li>
                      <li class="page-item"><a class="page-link" aria-label="next page" href="">Next »</a></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </main>
  <div class="fixed-plugin">
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Settings</h5>
          <p>Students Record options</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="material-icons">clear</i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-dark px-3 mb-2 active" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
          <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
        <!-- Navbar Fixed -->
        <div class="mt-3 d-flex">
          <h6 class="mb-0">Navbar Fixed</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
          </div>
        </div>
        <hr class="horizontal dark my-3">
        <div class="mt-2 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JQuery -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
  jQuery Modal
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" /> -->

  <!--   Core JS Files   -->
  <script src="../../assets/js/core/popper.min.js"></script>
  <script src="../../assets/js/core/bootstrap.min.js"></script>
  <script src="../../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../../assets/js/plugins/smooth-scrollbar.min.js"></script>

  <!-- JQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>

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
    function showDetails(key) {
      $("#userInformation").modal('show');

      $.ajax({
        url: "data/studInfo.php",
        type: "POST",
        data: {
          "key": key
        }
      }).done(function(data) {
        $("#user-info-modal").html(data);
        // var modalBody = document.getElementById('user-info-modal');
        // modalBody.html(data);
      });
    }

    loadData(1, '', 'assign')
    loadData(1, '', 'assigned')

    function loadData(page, search, cat) {
      // Cat: assign, and assigned
      $.ajax({
        url: 'assigning.php',
        method: 'POST',
        type: 'POST',
        data: {
          'page': page,
          'search': search,
          'entries': $('#ent' + cat).val(),
          'cat': cat
        }
      }).done(function(data) {
        console.log(data);
        $("#con" + cat).html(data);
      });

      function editAssign(uid) {

      }
    }
  </script>

  <div class="modal fade" id="assigning" tabindex="-1" role="dialog" aria-labelledby="assigningLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="assigningLabel">Teacher Assigning</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="user-info-modal">
          <h3>Assigning</h3>
          <p>Name: </p>
          <p>Section: </p>
          <p>Contact Number: </p>
          <h5 class="text-center">Assign</h5>
          <div id="tabAssign">
            <table style="width: 100%;">
              <tr>
                <th>Subject</th>
                <th>Section</th>
                <th>Action</th>
              </tr>
              <tr>
                <td colspan="3">Loading data...</td>
              </tr>
            </table>
          </div>
          <br>
          <div>
            <tr>
              <td>
                <label for="">Subject</label>
                <select class="form-select text-start border-1 ps-2" aria-label=".form-select-lg example">
                  <option selected>-select-</option>
                  <option value="">NST-001</option>
                  <option value="">NST-002</option>
                  <option value="">SSP-001</option>
                  <option value="">SSP-002</option>
                  <option value="">SSP-003</option>
                  <option value="">SSP-004</option>
                  <option value="">SSP-005</option>
                  <option value="">SSP-006</option>
                  <option value="">SSP-007</option>
                  <option value="">SSP-008</option>
                  <option value="">SSP-009</option>
                </select>
              </td>
              <td>
                <label for="">Section(put "," if multiple)</label>
                <input type="text" class="form-control ps-2" id="" required="">
              </td>
            </tr>
            <center>
              <div class="form-group pt-2">
                <button type="submit" class="btn btn-success btn-lg">ASSIGN</button>
              </div>
            </center>
          </div>
        </div>
      </div>
    </div>
</body>

</html>