<?php
/**
 * This file stores the user specified configurations to use this application
 * 
 */

/**
* $DB_CONFIG_FILE - Absolute Path to file where Database access details are specified. Expecting a php file.
*/
$DB_CONFIG_FILE="/var/www/html/lgi/php/includes/db.inc.php";
/**
 * CA certificate file to be used when requesting to project server
 */
$CA_FILE="/var/local/home/apache/lgikey/ca.crt";

/**
 * _AUTH_MECHANISM_ - defines which authentication mechanism to use. Currently two methods are possible.
 *  1. Form based - username and password stored in database
 *  2. HTTP digest authentication
 */
define('_AUTH_MECHANISM_',"DATABASE");  //Possible "DATABASE","DIGEST"

/**
 * _LGI_ROOT_ - Defines the root folder where this application is deployed. The path should be with respect to web root.
 */
define('_LGI_ROOT_',"/lgi/");
 
?>