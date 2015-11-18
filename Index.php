<?php

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

error_reporting(0);

//INCLUDE THE FILES NEEDED...
require_once('controller/MasterController.php');

//CREATE OBJECTS OF THE CONTROLLERS
$mc = new \controller\MasterController();

$mc->Run();