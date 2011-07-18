<?php

// Include the main class, the rest will be automatically loaded
require_once 'Dwoo/dwooAutoload.php';
require_once 'php/utilities/sessions.php';
require_once 'php/utilities/data.php';
require_once 'lgi.config.php';

session_start();
if(checkValidSession()) //if already logged in redirect it to home
{	
	header("Location:./php/home.php");
	//echo "Hi! You have logged in.";
}
else
{
         //check which authentication mechanism
          if(strcmp(_AUTH_MECHANISM_,"DATABASE")==0)
          {
               $dwoo =new Dwoo();
               $dwoo->output('dwoo/login.tpl',createDwooData());
          }
          else if(strcmp(_AUTH_MECHANISM_,"DIGEST")==0)
          {
               //authenticateDigest()
               header("Location: php/login.php");
          }
}
	
?>