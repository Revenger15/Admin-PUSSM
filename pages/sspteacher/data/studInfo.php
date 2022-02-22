<?php
include '../../../includes/dbconfig.php';
session_start();
$key = $_POST['key'];
$resultRef = $database->getReference('result/'.$key);
$stdUID = $resultRef->getChild('uid')->getValue();
$stdRef = $database->getReference('users/'.$stdUID);

echo '
<p>Name: '. $stdRef->getChild('lastname')->getValue() . ', ' . $stdRef->getChild('firstname')->getValue() . ' ' . $stdRef->getChild('middlename')->getValue() .'</p>
<p>Section: ' . $stdRef->getChild('section')->getValue() . ' </p>
<p>Contact Number: ' . $stdRef->getChild('contact')->getValue() . '</p>
<div style="display: flex; justify-content: space-between;">
<p>Results:</p>
<p>Date: '. $resultRef->getChild('date')->getValue() .' </p>
</div>
<div style="display: flex; justify-content: space-around;">
<div class="circular-progress" id="phy-prog">
    <div class="value-container" id="phy-val">0%</div>
</div>
<div class="circular-progress" id="men-prog">
    <div class="value-container" id="men-val">0%</div>
</div>
<div class="circular-progress" id="tot-prog">
    <div class="value-container" id="tot-val">0%</div>
</div>
</div>

<script>
    circleBar("#phy-prog", "#phy-val", '.$resultRef->getChild('physical')->getValue().');
    circleBar("#men-prog", "#men-val", '.$resultRef->getChild('mental')->getValue().');
    circleBar("#tot-prog", "#tot-val", '.$resultRef->getChild('overall')->getValue().');
</script>
</div>
';
?>
<script>
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
  </script>
<!-- // <p>Name: </p>
//           <p>Section: </p>
//           <p>Contact Number: </p>
//           <div>
//             <p>Results</p>
//             <p>Date: </p>
//           </div>
//           <p>Actions</p> -->
