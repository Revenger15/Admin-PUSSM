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
<p>Results</p>
<p>Date: '. $resultRef->getChild('date')->getValue() .' </p>
</div>
<p>INSERT DATA</p>
</div>
';

// <p>Name: </p>
//           <p>Section: </p>
//           <p>Contact Number: </p>
//           <div>
//             <p>Results</p>
//             <p>Date: </p>
//           </div>
//           <p>Actions</p>
