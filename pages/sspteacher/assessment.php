<?php
include '../../includes/dbconfig.php';
date_default_timezone_set('Asia/Manila');
session_start();

if (isset($_POST['page'])) {
  $page = $_POST['page'];
  $search = $_POST['search'];
  $entries = $_POST['entries'];
  $category = $_POST['cat'];

  if (!function_exists('fetchData')) {
    function fetchData($page, $search, $nEntries, $cat)
    {
      include '../../includes/dbconfig.php';

      $currAY = $database->getReference('system/current')->getValue();
      $teacherDB = $database->getReference('data/' . $currAY . '/adviser/' . $_SESSION['uid']);
      $subSect = $teacherDB->getValue();
      $filteredData = [];

      $e5  = ($nEntries == 5)  ? 'selected' : '';
      $e15 = ($nEntries == 15) ? 'selected' : '';
      $e25 = ($nEntries == 25) ? 'selected' : '';
      $e50 = ($nEntries == 50) ? 'selected' : '';

      $rawResult = [];
      switch ($cat) {
        case 'assess':
          $theme = 'dark';
          $stats = 'RECORDED';
          $mode = 'RECORDED';
          $pool = 'result';
          break;
        case 'physical':
          $theme = 'warning';
          $stats = 'TE_PHY';
          $mode = 'TE_PHY';
          $pool = 'result';
          break;
        case 'mental':
          $theme = 'success';
          $stats = 'TE_MEN';
          $mode = 'TE_MEN';
          $pool = 'result';
          break;
        case 'ok':
          $theme = 'info';
          $stats = 'OK';
          $mode = 'OK';
          $pool = 'ok';
      }

      // Get teacher subj and sect
      foreach ($subSect as $subj => $sect) {
        foreach ($sect as $k => $v) {
          $stdListDB = $database->getReference('data/' . $currAY . '/studentList/' . $subj . '/' . $v);
          $stdResultDB = $stdListDB->getValue() ? $stdListDB->getValue() : [];

          // var_dump($stdResultDB);
          // Get student list and result
          foreach ($stdResultDB as $uid => $result) {
            foreach ($result['result'] as $ts => $status) {
              if ($status == $stats) {
                $resultDB = $database->getReference('data/' . $currAY . '/' . $pool . '/' . $ts);
                $rawResult[$subj][$v][$uid][$ts] = $resultDB->getValue();
              }
            }
          }
        }
      }

      // Search data
      if ($search != '' && $rawResult != []) {
        foreach ($rawResult as $subj => $data1) {
          if (str_icontains($subj, $search)) {
            $filteredData[$subj] = $data1;
          }
          foreach ($data1 as $sect => $data2) {
            if (str_icontains($sect, $search)) {
              $filteredData[$subj][$sect] = $data2;
            }
            foreach ($data2 as $uid => $data3) {
              if (str_icontains($uid, $search)) {
                $filteredData[$subj][$sect][$uid] = $data3;
              }
              foreach ($data3 as $ts => $data4) {
                $json = json_encode($data4);
                if (str_icontains($json, $search)) {
                  $filteredData[$subj][$sect][$uid][$ts] = $data4;
                }
              }
            }
          }
        }
      } else {
        $filteredData = $rawResult;
      }

      echo '
          <div class="card-body px-0 pb-2">
        <div class="table-responsive p-0">
            <table class="table align-items-center justify-content-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
      ';

      if ($filteredData != []) {

        $numChild = count($filteredData);
        $tPage = ceil($numChild / $nEntries);
        $page = ($page <= $tPage && $page > 0) ? $page : 1;

        $pagedData = array_slice($filteredData, ($page - 1) * $nEntries, $preserve_keys = true);

        foreach ($pagedData as $subj => $data1) {
          foreach ($data1 as $sect => $data2) {
            foreach ($data2 as $uid => $data3) {
              foreach ($data3 as $ts => $data4) {
                $date = date('d/m/Y', $ts / 1000);
                echo <<<HTML
                  <tr>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div>
                              <img src="../../assets/img/micon.png" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{$data4['firstname']} {$data4['middlename']} {$data4['lastname']}</h6>
                                <p class="text-xs text-secondary mb-0">{$data4['email']}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0">{$data4['section']}</p>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0">{$data4['subject']}</p>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0">{$data4['contact']}</p>
                    </td>
                    <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">{$date}</span>
                    </td>
                    <td class="align-middle">
                        <a href="#" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-{$theme}" data-toggle="tooltip" data-original-title="View assessment" onclick="showDetails('{$ts}', '{$mode}')">
                            Result
                        </a>
                    </td>
                </tr>
                HTML;
              }
            }
          }
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
        ';

        if (str_contains($stats, 'TE')) {
          echo '
          <div class="float-left pagination">
            <button type="button" class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1" onclick="exportData(\''. $stats .'\', \''. $cat .'\')">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
              <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
            </svg> Send
            </button>
          </div>
          ';
        }
        echo '
            <div class="float-left pagination">
              <select class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1" name="page" id="ent' . $cat . '">
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
        echo ' aria-label="previous page" onclick="loadData(' . $page - 1 . ', \'' . $search . '\', \'' . $cat . '\');">« Prev</a></li>';

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
          echo 'aria-label="to page ' . $x . '"  onclick="loadData(' . $x . ', \'' . $search . '\', \'' . $cat . '\');">' . $x . '</a></li>';
        }

        // Pagination >>
        echo '<li class="page-item"><a class="page-link"';
        if ($page == $tPage) {
          echo ' style="pointer-events: none;"';
        }
        echo ' aria-label="next page" onclick="loadData(' . $page + 1 . ', \'' . $search . '\', \'' . $cat . '\');">Next »</a></li>
              </ul>
            </div>
          </div>';
      } else {
        if (str_contains($stats, 'TE')) {
          $action = '
          <div class="float-left pagination">
            <button type="button" class="btn btn-outline-' . $theme . ' mt-2 ms-1 mb-1">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
              <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
            </svg> Send
            </button>
          </div>
          ';
        }

        echo <<<HTML
                      <tr>
                        <th colspan="5">No data found.</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="fixed-table-pagination">
                {$action}
                <div class="float-left pagination">
                  <select class="btn btn-outline-{$theme} mt-2 ms-1 mb-1" name="page" id="ent{$cat}">
                    <option value="5" Selected>5 entries</option>
                    <option value="15">15 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                  </select>
                </div>
                <div class="float-right pagination">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" aria-label="previous page" href="#">« Prev</a></li>
                    <li class="page-item active bg-gradient-faded-{$theme}-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" aria-label="next page" href="#">Next »</a></li>
                  </ul>
                </div>
              </div>
        HTML;
      }
    }
  }

  fetchData($page, $search, $entries, $category);
  exit();
} elseif (isset($_POST['updateRecordStatus'])) {
  $uid = $_POST['uid'];
  $key = $_POST['key'];
  $oldStatus = $_POST['oldStatus'];
  $newStatus = $_POST['newStatus'];

  if ($oldStatus != 'OK') {
    $oldPool = 'result';
  } else {
    $oldPool = 'ok';
  }

  if ($newStatus != 'OK') {
    $newPool = 'result';
  } else {
    $newPool = 'ok';
  }

  $currAY = $database->getReference('system/current')->getValue();
  $oldReference = $database->getReference('data/' . $currAY . '/' . $oldPool . '/' . $key);
  $newReference = $database->getReference('data/' . $currAY . '/' . $newPool . '/' . $key);
  $stdUID = $oldReference->getChild('uid')->getValue();
  $subj = $oldReference->getChild('subject')->getValue();
  $sect = $oldReference->getChild('section')->getValue();

  $record = $oldReference->getValue();
  $oldReference->set(NULL);
  $record['status'] = $newStatus;
  $newReference->set($record);

  // echo 'data/'.$currAY.'/'.$subj.'/'.$sect.'/'.$stdUID.'/result/'.$key;

  $database->getReference('data/' . $currAY . '/studentList/' . $subj . '/' . $sect . '/' . $stdUID . '/result/' . $key)->set($newStatus);
  exit();
} elseif (isset($_POST['exportData'])) {
  $cat = $_POST['exportData']; //TE_MEN or TE_PHY
  $mode = $_POST['mode'];

  $currAY = $database->getReference('system/current')->getValue();
  $teacherDB = $database->getReference('data/' . $currAY . '/adviser/' . $_SESSION['uid']);
  $subSect = $teacherDB->getValue();

  $rawResult = [];

  // Get teacher subj and sect
  foreach ($subSect as $subj => $sect) {
    foreach ($sect as $k => $v) {
      $stdListDB = $database->getReference('data/' . $currAY . '/studentList/' . $subj . '/' . $v);
      $stdResultDB = $stdListDB->getValue() ? $stdListDB->getValue() : [];

      // var_dump($stdResultDB);
      // Get student list and result
      foreach ($stdResultDB as $uid => $result) {
        foreach ($result['result'] as $ts => $status) {
          if ($status == $cat) {
            $stdListDB->getChild($uid . '/result/' . $ts)->set(str_replace('TE', 'REF', $cat)); //Change TE to REF
            $resultDB = $database->getReference('data/' . $currAY . '/result/' . $ts);
            $rawResult[$ts] = $resultDB->getValue();
            $resultDB->set(NULL);
          }
        }
      }
    }
  }

  $database->getReference('data/' . $currAY . '/' . $mode)->update($rawResult);
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
    Assessment
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
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" target="_blank">
        <span class="ms-1 font-weight-bold text-white fs-2">Record</span>
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
          <a class="nav-link text-white active bg-gradient-faded-dark-vertical" href="#">
            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
              <i class="material-icons opacity-10">table_view</i>
            </div>
            <span class="nav-link-text ms-1">Assessment</span>
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
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">SSP Adviser</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">PHINMA-UPang Student Support Module</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Student Record</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="col-5 pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" id="inpSearch" class="form-control">
              <select name="tsearch" id="searchTable" class="form-label border-0 bg-transparent mt-advsearch cursor-pointer">
                <option value="assess">Assessed Table</option>
                <option value="physical">Physical Table</option>
                <option value="mental">Mental Table</option>
                <option value="ok">Good Condition Table</option>
              </select>
            </div>
            <button class="btn bg-gradient-success mt-3 ms-1 ps-3 text-center font-monospace text-capitalize" onclick="loadData(1, $('#inpSearch').val(), $('#searchTable').val());">Search</button>
          </div>
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
                <h6 class="text-white text-capitalize ps-3">Students Assessed</h6>
              </div>
            </div>
            <div id="conassess">
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th colspan="5">Loading data...</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="fixed-table-pagination">
                <div class="float-left pagination">
                  <select class="btn btn-outline-dark mt-2 ms-1 mb-1" name="page" id="entassess">
                    <option value="5" Selected>5 entries</option>
                    <option value="15">15 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                  </select>
                </div>
                <div class="float-right pagination">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" aria-label="previous page" href="#">« Prev</a></li>
                    <li class="page-item active bg-gradient-faded-dark-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" aria-label="next page" href="#">Next »</a></li>
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
              <div class="bg-gradient-faded-warning shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Need to Refer(Physical)</h6>
              </div>
            </div>
            <div id="conphysical">
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th colspan="5">Loading data...</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="fixed-table-pagination">
                <div class="float-left pagination">
                  <button type="button" class="btn btn-outline-warning mt-2 ms-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                      <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
                    </svg> Send
                  </button>
                </div>
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
                    <li class="page-item"><a class="page-link" aria-label="previous page" href="#">« Prev</a></li>
                    <li class="page-item active bg-gradient-faded-warning-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" aria-label="next page" href="#">Next »</a></li>
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
                <h6 class="text-white text-capitalize ps-3">Need to Refer(Mental)</h6>
              </div>
            </div>
            <div id="conmental">
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th colspan="5">Loading data...</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="fixed-table-pagination">
                <div class="float-left pagination">
                  <button type="button" class="btn btn-outline-success mt-2 ms-1 mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                      <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z" />
                    </svg> Send
                  </button>
                </div>
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
                    <li class="page-item"><a class="page-link" aria-label="previous page" href="#">« Prev</a></li>
                    <li class="page-item active bg-gradient-faded-success-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" aria-label="next page" href="#">Next »</a></li>
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
              <div class="bg-gradient-faded-info shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">List of Good Condition</h6>
              </div>
            </div>
            <div id="conok">
              <div class="card-body px-0 pb-2">
                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                        <th class="text-secondary opacity-7"></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th colspan="5">Loading data...</th>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="fixed-table-pagination">
                <div class="float-left pagination">
                  <select class="btn btn-outline-info mt-2 ms-1 mb-1" name="page" id="entok">
                    <option value="5" Selected>5 entries</option>
                    <option value="15">15 entries</option>
                    <option value="25">25 entries</option>
                    <option value="50">50 entries</option>
                  </select>
                </div>
                <div class="float-right pagination">
                  <ul class="pagination">
                    <li class="page-item"><a class="page-link" aria-label="previous page" href="#">« Prev</a></li>
                    <li class="page-item active bg-gradient-faded-info-vertical border-radius-2xl"><a class="page-link" aria-label="to page 1" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" aria-label="next page" href="#">Next »</a></li>
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
    function showDetails(key, mode) {
      $("#user-info-modal").html(`
      <div class="modal-header">
          <h5 class="modal-title" id="userInformationlLabel">Student Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Name: </p>
          <p>Section: </p>
          <p>Contact Number: </p>
          <div style="display: flex; justify-content: space-between;">
            <p>Results:</p>
            <p>Date: </p>
          </div>
          <div class="container-fluid py-4" style="display: flex; justify-content: space-around;">
            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="--value:0; --size: 7.5rem">
              <p class="text-xs font-weight-bold mb-0">Physical</p>
            </div>
            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="--value:0; --size: 7.5rem">
              <p class="text-xs font-weight-bold mb-0">Mental</p>
            </div>
            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="--value:0; --size: 7.5rem">
              <p class="text-xs font-weight-bold mb-0">Over All</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success">Refer(Mental)</button>
          <button type="button" class="btn btn-warning">Refer(Physical)</button>
          <button type="button" class="btn btn-info">Good Condition</button>
        </div>
      `);
      $("#userInformation").modal('show');

      $.ajax({
        url: "data/studInfo.php",
        type: "POST",
        data: {
          "key": key,
          "mode": mode
        }
      }).done(function(data) {
        $("#user-info-modal").html(data);
        // var modalBody = document.getElementById('user-info-modal');
        // modalBody.html(data);
      });
    }

    loadData(1, '', 'assess');
    loadData(1, '', 'physical');
    loadData(1, '', 'mental');
    loadData(1, '', 'ok');

    function loadData(page, search, cat) {
      // Cat: assign, and assigned
      $.ajax({
        url: 'assessment.php',
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
    }

    function setRecordStatus(uid, key, oldStatus, newStatus) {
      $.ajax({
        url: 'assessment.php',
        method: 'POST',
        type: 'POST',
        data: {
          'uid': uid,
          'key': key,
          'oldStatus': oldStatus,
          'newStatus': newStatus,
          'updateRecordStatus': 1
        }
      }).done(function(data) {
        console.log(data);
        $('#userInformation').modal('toggle');
        if (data == '') {
          // ok
          // refresh tables and close modal
          loadData(1, '', convertCode(oldStatus));
          loadData(1, '', convertCode(newStatus));
        } else {
          // error
          // close modal and show alert()
          alert('An error occurred!\n'+data);
        }
      });
    }

    function exportData(code, category) {
      $.ajax({
        url: 'assessment.php',
        type: 'POST',
        method: 'POST',
        data: {
          'mode' : mode,
          'exportData' : category
        }
      }).done(function(data) {
        console.log(data);
        if (data == '') {
          // ok
          // refresh tables and close modal
          loadData(1, '', category);
        } else {
          // error
          // close modal and show alert()
          alert('An error occurred!\n'+data);
        }
      });
    }
    
    function convertCode(code) {
      switch(code) {
        case 'RECORDED':
          return 'assess';
          break;
        case 'TE_PHY':
          return 'physical';
          break;
        case 'TE_MEN':
          return 'mental';
          break;
        case 'OK':
          return 'ok';
          break;
      }
    }
  </script>

  <div class="modal fade" id="userInformation" tabindex="-1" role="dialog" aria-labelledby="userInformationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content" id="user-info-modal">
      </div>
    </div>
  </div>

  <!-- <div id="userInformation" class="modal">
    <h1>Student Record</h1>
    <p>Name: </p>
    <p>Section: </p>
    <p>Contact Number: </p>
    <div>
      <p>Results</p>
      <p>Date: </p>
    </div>
    <p>Actions</p>
    <div>
      <button>Refer to Guidance</button>
      <button>Refer to Nurse</button>
      <button>Mark Contacted</button>
    </div>
  </div> -->
</body>

</html>