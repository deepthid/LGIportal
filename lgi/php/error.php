<?php
/**
*	This file displays an error page
*	@author Deepthi
*/

include 'utilities/errors.php';
	$errormessage=getErrorMessage();
	echo $errormessage;
	clearErrorMessage();

?>