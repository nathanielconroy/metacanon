<?php

session_start();

define('INCLUDE_CHECK',true);
include 'php/DatabaseConfig.php';
include 'php/UserHandling.php';
include 'connect.php';

//get mark as read and mark as unread data
if (empty($_POST['markread']))
{$markasread = "''";}
else {$markasread = "'" .$_POST['markread']. ",'";}

if (empty($_POST['markasunread']))
{$markasunread = "''";}
else {$markasunread = "'," .$_POST['markasunread']. ",'";}

//set current user to $user
$user = "'" .$_SESSION['usr']. "'";

if (isset($_POST["markread"]))
{
    UserHandling::MarkBookAsRead($user,$markasread);
}

if (isset($_POST["markasunread"]))
{
    UserHandling::MarkBookAsUnread($user,$markasunread);
}

?>