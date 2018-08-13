<?php

session_start();

define('INCLUDE_CHECK',true);
include 'php/DatabaseConfig.php';
include 'php/UserHandling.php';
include 'connect.php';


if (isset($_POST["markread"]))
{
    UserHandling::MarkBookAsRead($_SESSION['usr'],$_POST['markread']);
}

if (isset($_POST["markasunread"]))
{
    UserHandling::MarkBookAsUnread($_SESSION['usr'],$_POST['markasunread']);
}

?>