<?php
include '../../../includes/dbconfig.php';
session_start();
$key = $_POST['key'];
$mode = $_POST['mode'];
if($mode != 'OK') {
  $pool = 'result';
  $state = '';
} else {
  $pool = 'ok';
  $state = "disabled";
}

$currAY = $database->getReference('system/current')->getValue();
$resultRef = $database->getReference('data/'.$currAY.'/'.$pool.'/'.$key);
$stdUID = $resultRef->getChild('uid')->getValue();
$date = Date('d/m/y', $key/1000);
// $stdRef = $database->getReference('users/'.$stdUID);

echo '
  <div class="modal-header">
    <h5 class="modal-title" id="userInformationlLabel">Student Record</h5>
    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
    <p>Name: '. $resultRef->getChild('lastname')->getValue() . ', ' . $resultRef->getChild('firstname')->getValue() . ' ' . $resultRef->getChild('middlename')->getValue() .'</p>
    <p>Section: ' . $resultRef->getChild('section')->getValue() . ' </p>
    <p>Contact Number: ' . $resultRef->getChild('contact')->getValue() . '</p>
    <div style="display: flex; justify-content: space-between;">
      <p>Results:</p>
      <p>Date: '. $date .' </p>
    </div>
      <div class="container-fluid py-4" style="display: flex; justify-content: space-around;">
        <div role="progressbar" aria-valuenow="'.$resultRef->getChild('physical')->getValue().'" aria-valuemin="0" aria-valuemax="100" style="--value:'.$resultRef->getChild('physical')->getValue().'; --size: 7.5rem">
          <p class="text-xs font-weight-bold mb-0">Physical</p>
        </div>
        <div role="progressbar" aria-valuenow="'.$resultRef->getChild('mental')->getValue().'" aria-valuemin="0" aria-valuemax="100" style="--value:'.$resultRef->getChild('mental')->getValue().'; --size: 7.5rem">
          <p class="text-xs font-weight-bold mb-0">Mental</p>
        </div>
        <div role="progressbar" aria-valuenow="'.$resultRef->getChild('total')->getValue().'" aria-valuemin="0" aria-valuemax="100" style="--value:'.$resultRef->getChild('total')->getValue().'; --size: 7.5rem">
          <p class="text-xs font-weight-bold mb-0">Over All</p>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <button type="button" onclick="setRecordStatus(\''.$stdUID.'\', \''.$key.'\', \''.$mode.'\', \'TE_MEN\');" '.$state.' class="btn btn-success">Refer(Mental)</button>
    <button type="button" onclick="setRecordStatus(\''.$stdUID.'\', \''.$key.'\', \''.$mode.'\', \'TE_PHY\');" '.$state.' class="btn btn-warning">Refer(Physical)</button>
    <button type="button" onclick="setRecordStatus(\''.$stdUID.'\', \''.$key.'\', \''.$mode.'\', \'OK\');" '.$state.' class="btn btn-info">Good Condition</button>
  </div>
';
?>
<!--script>
var bar = new ProgressBar.Circle(physical, {
    color: '#fff',
    strokeWidth: 4,
    trailWidth: 1,
    easing: 'easeInOut',
    duration: 1400,
    text: {
      autoStyleContainer: false
    },
    from: { color: '#aaa', width: 1 },
    to: { color: '#333', width: 4 },
    // Set default step function for all animate calls
    step: function(state, circle) {
      circle.path.setAttribute('stroke', state.color);
      circle.path.setAttribute('stroke-width', state.width);
  
      var value = Math.round(circle.value() * 100);
      if (value === 0) {
        circle.setText('');
      } else {
        circle.setText(value);
      }
  
    }
  });
  bar.text.style.fontSize = '2rem';
  
  bar.animate(1.0); 
  </script-->
<!-- // <p>Name: </p>
//           <p>Section: </p>
//           <p>Contact Number: </p>
//           <div>
//             <p>Results</p>
//             <p>Date: </p>
//           </div>
//           <p>Actions</p> -->
