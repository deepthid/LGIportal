
 <?php

include 'Dwoo/dwooAutoload.php';

// Create the controller, it is reusable and can render multiple templates
$dwoo = new Dwoo(); 

   session_start();
   session_destroy();
   header("Location: ../index.php");
  ;
?>

