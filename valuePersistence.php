<?php
// update the menu selections with the current settings.
$selected100 = "";
$selected50 = "";
$selected25 = "";

switch ($numtitles)
{ 
case "100":
    $selected100 = "selected" ; 
    break;
case "50":
    $selected50 = "selected" ; 
    break;
case "25":
    $selected25 = "selected" ; 
    break;
}

//cause the total number of books in the form to carry over after the form is submitted
$totalbooks100 = "";
$totalbooks250 = "";
$totalbooks500 = "";
$totalbooks1000 = "";
$totalbooks3000 = "";

switch ($totalbooks)
{
case "100":
    $totalbooks100 = "selected" ; 
    break;
case "250":
    $totalbooks250 = "selected" ; 
    break;
case "500":
    $totalbooks500 = "selected" ; 
    break;
case "1000":
    $totalbooks1000 = "selected" ; 
    break;
case "3000":
    $totalbooks3000 = "selected" ; 
    break;
default: 
    $totalbooks500 = "selected" ;
}

//cause the order to be carried over after the form is submitted
$selectedrank = "";
$selectedtitle = "";
$selectedauthor = "";
$selectedyear = "";

switch ($order)
{
case "newscore":
    $selectedrank = "selected"; 
    break;
case "title":
    $selectedtitle = "selected"; 
    break;
case "fullname":
    $selectedauthor = "selected"; 
    break;
case "year":
    $selectedyear = "selected";
    break;
}

$oneauthcheck = "";
$womenonlycheck = "";
$novelscheck = "";
$collectionscheck = "";
$novellascheck = "";
$othercheck = "";
//have checked values carry over
if ($oneBookPerAuthor) { $oneauthcheck = "checked" ; }
if ($authorgender == "female") { $womenonlycheck = "checked" ; }
if (in_array("novel",$genres)) { $novelscheck = "checked" ; }
if (in_array("collection",$genres)) { $collectionscheck = "checked" ; }
if (in_array("novella",$genres)) { $novellascheck = "checked" ; }
if (in_array("other",$genres)) { $othercheck = "checked" ; }
?>
