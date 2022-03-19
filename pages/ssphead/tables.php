<?php
include '../../includes/dbconfig.php';
session_start();

// DEBUG: UID
$uid = "UP-21-090-F";

$resultReference = $database->getReference("result");
$userReference = $database->getReference("users/" . $uid . "/result");
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
  <style>
    .table td, .table th {
    border-top: unset;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-200">
<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3   bg-gradient-dark" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" target="_blank">
        <span class="ms-1 font-weight-bold text-white fs-2">Assessment</span>
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
            <span class="nav-link-text ms-1">Teachers</span>
          </a>
        </li>
        
        <li class="nav-item">
          <a class="nav-link text-whitee " href="Useracc.php">
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
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">SSP Head</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Teachers</li>
          </ol>
          <h6 class="font-weight-bolder mb-0">Teachers</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="col-5 pe-md-3 d-flex align-items-center">
            <div class="input-group input-group-outline">
              <label class="form-label">Type here...</label>
              <input type="text" class="form-control">
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
    <!-- Pagination -->

    <div class="row">
      <nav class="pagination-outer" aria-label="Page navigation">
        <ul class="pagination">
            <li class="page-item">
                <a href="#" class="page-link" aria-label="Previous">
                    <span aria-hidden="true">« Previous</span>
                </a>
            </li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item">
                <a href="#" class="page-link" aria-label="Next">
                    <span aria-hidden="true">Next »</span>
                </a>
            </li>
        </ul>
      </nav>
    </div>

    <div class="row">  
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-faded-success shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Teacher Asigning</h6>
              </div>
            </div>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
            <div class="mt-3 mb-4">
            <div class="col-lg-12 mt-4 mt-lg-0">
                <div class="row">
                  <div class="col-md-12">
                    <div class="user-dashboard-info-box table-responsive mb-0 bg-white">
                      <table class="table manage-candidates-top mb-0">
                        <thead>
                          <tr>
                            <th> SSP Teacher</th>
                            <th class="text-center">Section</th>
                            <th class="action text-right"></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr class="candidates-list">
                            <td class="title">
                              <div class="thumb">
                                <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="">
                              </div>
                              <div class="candidate-list-details">
                                <div class="candidate-list-info">
                                  <div class="candidate-list-title">
                                    <h5 class="mb-0"><a href="#">Brooke Kelly</a></h5>
                                  </div>
                                  <div class="candidate-list-option">
                                    <ul class="list-unstyled">
                                      <li><i class="fas fa-filter pr-1"></i>Information Technology</li>
                                      <li><i class="fas fa-map-marker-alt pr-1"></i>Rolling Meadows, IL 60008</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="candidate-list-favourite-time text-center">
                              <a class="candidate-list-favourite order-2 text-danger" href="#"></a>
                              <span class="candidate-list-time order-1">section</span>
                            </td>
                            <td>
                              <ul class="list-unstyled mb-0 d-flex justify-content-end">
                                <li><a href="#" class="text-primary" data-toggle="tooltip" title="" data-original-title="view"><i class="far fa-eye"></i></a></li>
                                <li><a href="#" class="text-info" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="#" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
                              </ul>
                            </td>
                          </tr>
                          <tr class="candidates-list">
                            <td class="title">
                              <div class="thumb">
                                <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="">
                              </div>
                              <div class="candidate-list-details">
                                <div class="candidate-list-info">
                                  <div class="candidate-list-title">
                                    <h5 class="mb-0"><a href="#">Ronald Bradley</a></h5>
                                  </div>
                                  <div class="candidate-list-option">
                                    <ul class="list-unstyled">
                                      <li><i class="fas fa-filter pr-1"></i>Human Resources</li>
                                      <li><i class="fas fa-map-marker-alt pr-1"></i>Monroe Township, NJ 08831</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="candidate-list-favourite-time text-center">
                              <a class="candidate-list-favourite order-2 text-danger" href="#"></a>
                              <span class="candidate-list-time order-1">section</span>
                            </td>
                            <td>
                              <ul class="list-unstyled mb-0 d-flex justify-content-end">
                                <li><a href="#" class="text-primary" data-toggle="tooltip" title="" data-original-title="view"><i class="far fa-eye"></i></a></li>
                                <li><a href="#" class="text-info" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="#" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
                              </ul>
                            </td>
                          </tr>
                          <tr class="candidates-list">
                            <td class="title">
                              <div class="thumb">
                                <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar2.png" alt="">
                              </div>
                              <div class="candidate-list-details">
                                <div class="candidate-list-info">
                                  <div class="candidate-list-title">
                                    <h5 class="mb-0"><a href="#">Rafael Briggs</a></h5>
                                  </div>
                                  <div class="candidate-list-option">
                                    <ul class="list-unstyled">
                                      <li><i class="fas fa-filter pr-1"></i>Recruitment Consultancy</li>
                                      <li><i class="fas fa-map-marker-alt pr-1"></i>Haines City, FL 33844</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="candidate-list-favourite-time text-center">
                              <a class="candidate-list-favourite order-2 text-danger" href="#"></a>
                              <span class="candidate-list-time order-1">section</span>
                            </td>
                            <td>
                              <ul class="list-unstyled mb-0 d-flex justify-content-end">
                                <li><a href="#" class="text-primary" data-toggle="tooltip" title="" data-original-title="view"><i class="far fa-eye"></i></a></li>
                                <li><a href="#" class="text-info" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="#" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
                              </ul>
                            </td>
                          </tr>
                          <tr class="candidates-list">
                            <td class="title">
                              <div class="thumb">
                              <img src="../../assets/img/team-1.png" class="avatar avatar-xl me-4 border-radius-lg" alt="user1">
                              </div>
                              <div class="candidate-list-details">
                                <div class="candidate-list-info">
                                  <div class="candidate-list-title">
                                    <h5 class="mb-0"><a href="#">Vickie Meyer</a></h5>
                                  </div>
                                  <div class="candidate-list-option">
                                    <ul class="list-unstyled">
                                      <li><i class="fas fa-filter pr-1"></i>Human Resources</li>
                                      <li><i class="fas fa-map-marker-alt pr-1"></i>Minneapolis, MN 55406</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="candidate-list-favourite-time text-center">
                              <a class="candidate-list-favourite order-2 text-danger" href="#"></a>
                              <span class="candidate-list-time order-1">section</span>
                            </td>
                            <td>
                              <ul class="list-unstyled mb-0 d-flex justify-content-end">
                                <li><a href="#" class="text-primary" data-toggle="tooltip" title="" data-original-title="view"><i class="far fa-eye"></i></a></li>
                                <li><a href="#" class="text-info" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="#" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
                              </ul>
                            </td>
                          </tr>
                          <tr class="candidates-list">
                            <td class="title">
                              <div class="thumb">
                                <img class="img-fluid" src="https://bootdey.com/img/Content/avatar/avatar4.png" alt="">
                              </div>
                              <div class="candidate-list-details">
                                <div class="candidate-list-info">
                                  <div class="candidate-list-title">
                                    <h5 class="mb-0"><a href="#">Nichole Haynes</a></h5>
                                  </div>
                                  <div class="candidate-list-option">
                                    <ul class="list-unstyled">
                                      <li><i class="fas fa-filter pr-1"></i>Information Technology</li>
                                      <li><i class="fas fa-map-marker-alt pr-1"></i>Botchergate, Carlisle</li>
                                    </ul>
                                  </div>
                                </div>
                              </div>
                            </td>
                            <td class="candidate-list-favourite-time text-center">
                              <a class="candidate-list-favourite order-2 text-danger" href="#"></a>
                              <span class="candidate-list-time order-1">section</span>
                            </td>
                            <td>
                              <ul class="list-unstyled mb-0 d-flex justify-content-end">
                                <li><a href="#" class="text-primary" data-toggle="tooltip" title="" data-original-title="view"><i class="far fa-eye"></i></a></li>
                                <li><a href="#" class="text-info" data-toggle="tooltip" title="" data-original-title="Edit"><i class="fas fa-pencil-alt"></i></a></li>
                                <li><a href="#" class="text-danger" data-toggle="tooltip" title="" data-original-title="Delete"><i class="far fa-trash-alt"></i></a></li>
                              </ul>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
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
                <h6 class="text-white text-capitalize ps-3">History of Contacted</h6>
              </div>
            </div>
            <div class="card-body px-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center justify-content-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Names</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Section</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Contact</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Date</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Arniel C. Fernandez</h6>
                            <p class="text-xs text-secondary mb-0">farniel1588@gmail.com</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">3BSITSYSDEV-1</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">09491050876</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-warning" data-toggle="tooltip" data-original-title="Edit user">
                          Contacted
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Arniel C. Fernandez</h6>
                            <p class="text-xs text-secondary mb-0">farniel1588@gmail.com</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">3BSITSYSDEV-1</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">09491050876</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-warning" data-toggle="tooltip" data-original-title="Edit user">
                          Contacted
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Arniel C. Fernandez</h6>
                            <p class="text-xs text-secondary mb-0">farniel1588@gmail.com</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">3BSITSYSDEV-1</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">09491050876</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-warning" data-toggle="tooltip" data-original-title="Edit user">
                          Contacted
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Arniel C. Fernandez</h6>
                            <p class="text-xs text-secondary mb-0">farniel1588@gmail.com</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">3BSITSYSDEV-1</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">09491050876</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-warning" data-toggle="tooltip" data-original-title="Edit user">
                          Contacted
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Arniel C. Fernandez</h6>
                            <p class="text-xs text-secondary mb-0">farniel1588@gmail.com</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">3BSITSYSDEV-1</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">09491050876</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-warning" data-toggle="tooltip" data-original-title="Edit user">
                          Contacted
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div>
                            <img src="../../assets/img/team-2.jpg" class="avatar avatar-sm me-3 border-radius-lg" alt="user1">
                          </div>
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">Arniel C. Fernandez</h6>
                            <p class="text-xs text-secondary mb-0">farniel1588@gmail.com</p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">3BSITSYSDEV-1</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">09491050876</p>
                      </td>
                      <td class="align-middle text-center">
                        <span class="text-secondary text-xs font-weight-bold">23/04/18</span>
                      </td>
                      <td class="align-middle">
                        <a href="javascript:;" class="text-sm font-weight-bold text-xs badge badge-sm bg-gradient-warning" data-toggle="tooltip" data-original-title="Edit user">
                          Contacted
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="material-icons py-2">settings</i>
    </a>
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
  </script>

  <div class="modal fade" id="userInformation" tabindex="-1" role="dialog" aria-labelledby="userInformationLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="userInformationlLabel">Student Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="user-info-modal">
          <p>Name: </p>
          <p>Section: </p>
          <p>Contact Number: </p>
          <div>
            <p>Results</p>
            <p>Date: </p>
          </div>
          <p>Actions</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary">Refer(Nurse)</button>
          <button type="button" class="btn btn-primary">Mark Contacted</button>
        </div>
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