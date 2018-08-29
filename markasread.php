<?php

session_start();

define('INCLUDE_CHECK',true);
include 'php/DatabaseConfig.php';
include 'php/UserHandling.php';
include 'connect.php';


if (isset($_POST["work_id"]))
{
    UserHandling::setBookStatus($_SESSION['user_id'],$_POST['work_id'], $_POST['work_status']);
}

?>