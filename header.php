<link rel="stylesheet" href="css/style.css">

<?php

if (isset($_POST['user_submit']) && $_POST['user_submit'] == 'LogOff')
{
	session_start();
	$_SESSION['usr'] = NULL;
	$_SESSION['id'] = NULL;
	session_destroy();
}
else
{
	session_start();
}

if (isset($_SESSION['id']))
{
    $id = true;
}
else 
{
    $id = false;
}


include 'vendor/autoload.php';
define('INCLUDE_CHECK',true);

include 'php/UserHandling.php';
require 'connect.php';	
require 'functions.php';

include 'php/FictionQueryBuilder.php';
include 'php/DatabaseAccessor.php';
include 'php/DatabaseConfig.php';
include 'php/HTMLGenerator.php';
include 'InitFromGet.php';
include 'functions2.php';
include 'valuePersistence.php';



if ((isset($_POST['user_submit']) && $_POST['user_submit']=='Register'))
{
	print 'hi';
	UserHandling::register();
}
else if (isset($_POST['username']))
{
	UserHandling::logIn();
}

$user = "none";
if (isset($_SESSION['usr']))
{
    $user = $_SESSION['usr'];
};

if (isset($_SESSION['user_id']))
{
	$user_id = $_SESSION['user_id'];
}
else
{
	$user_id = null;
}

$booksread = UserHandling::getBooksReadByUser($user_id);
$user_level = UserHandling::getUserLevel($user);

if (!isset($author)) {$author = 'all';}

$queryBuilder = new FictionQueryBuilder(
	$included_regions,
	$yearstart,
	$yearend,
	$authorgender,
	'work_id',
	$order,
	$numtitles,
	$startnumber,
	$weights['google_scholar'],
	$weights['jstor'],
	$weights['alh'],
	$weights['american_literature'],
	$weights['nyt'],
	$weights['jstor_lang_lit'],
	$weights['nba'],
	$weights['pulitzer'],
	$included_genres,
	$faulkner,
	$author,
	$included_tags,
	$user_id
);

if (!$oneBookPerAuthor)
{
	$fictionResults = DatabaseAccessor::getStandardResults($queryBuilder);
	$fictionCount = DatabaseAccessor::getFictionCount($queryBuilder);
}
else
{
	$fictionResults = DatabaseAccessor::getOneBookPerAuthor($queryBuilder);
	$fictionCount = DatabaseAccessor::getOnePerAuthFictionCount($queryBuilder);
}

$unread_icon = HTMLGenerator::UNREAD_ICON;
$read_icon = HTMLGenerator::READ_ICON;
$want_to_read_icon = HTMLGenerator::WISHLIST_ICON;
$partly_read_icon = HTMLGenerator::PARTLY_READ_ICON;
$mark_unread_tooltip = HTMLGenerator::getToolTip("Mark as unread.");
$mark_read_tooltip = HTMLGenerator::getToolTip("Mark as read.");
$mark_partly_read_tooltip = HTMLGenerator::getToolTip("Mark as partly read.");
$mark_want_to_read_tooltip = HTMLGenerator::getToolTip("Add to wishlist.");

echo "<script>
var unreadIcon = '$unread_icon';
var readIcon = '$read_icon';
var wantToReadIcon = '$want_to_read_icon';
var partlyReadIcon = '$partly_read_icon';
var markUnreadTooltip = '$mark_unread_tooltip';
var markReadTooltip = '$mark_read_tooltip';
var markPartlyReadTooltip = '$mark_partly_read_tooltip';
var markWantToReadTooltip = '$mark_want_to_read_tooltip';
</script>
";

?>

<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<script src="js/functions.js"></script>
<script src="js/jquery.watermarkinput.js" type="text/javascript"></script>

<script>
jQuery(function($){
   $("#search").Watermark("Search Author...");
});
</script>

<script>
jQuery(function($){
   $("#search2").Watermark("Author...");
});
</script>

<div class="fixedheader" style="min-width: 100%;position: fixed; z-index:9; ">
<header class="w3-container w3-border-bottom w3-row" style="background: linear-gradient(white, #eeeeee); min-height:50px;font-family:Helvetica;line-height:1.5">
  <div style="float:left">
  <div style="float:left;width: 50px"><a href="index.php"><img src="images/metacanonlogo8.gif" alt="metacanon logo"></a></div>
  <div style="float:left;margin-top:7px;width:220px"><a href="index.php" class="w3-xlarge">METACANON 0.7.2</a></div>
  </div>
  <div style="float:left;font-size:17px">
    <div style="float:left;margin:12px"><a href="about.php">about</a></div>
    <div style="float:left;margin:12px"><a href="statistics.php">statistics</a></div>
    <div style="float:left;margin:12px"><a href="userlogin.php"><?php echo isset($_SESSION['user_id']) ? $_SESSION['usr'] : 'login';?></a></div>
  </div>
  <div style="margin-top:11px;margin-bottom:11px;float:right;">
  <form id="searchform" action="search.php" method="get"><input id="search" name="search" type="text" style="border-radius: 5px 0px 0px 5px; border-width: 1px;  border-style: solid; border-color: #cccccc; height: 29px;"><input type="submit" value="?" style="border-radius: 0px 5px 5px 0px; border-width: 1px; border-style: solid solid solid none; border-color: #cccccc; height: 29px; background: linear-gradient(#eeeeee, white);"></form>
  </div>
</header>
<div class="w3-container" style="background: linear-gradient(#ddd, transparent); height: 5px"></div>
</div>

<div style="min-width: 100%;position:fixed;z-index:8">
  <div class="w3-container w3-border-bottom" style="min-height:50px;font-family:Helvetica;line-height:1.5;background-color: rgba(255,255,255,0.95);">
    <div style="float:left;width: 50px"><a href="index.php"><img src="images/metacanonlogo8.gif" alt="metacanon logo"></a></div>
    <div style="float:left;margin-top:7px;margin-left:4px;width:220px"><a href="index.php" class="w3-xlarge">METACANON 0.7.2</a></div>
    <div class="w3-dropdown-click" style="float:right;margin-top:8px" >
    <img onclick="myFunction()" src="images/menulogo.png" alt="Menu">
      <div id="Demo" class="w3-dropdown-content w3-card-4" style="right:0">
      <a href="about.php">about</a>
      <a href="statistics.php">statistics</a>
      <a href="userlogin.php"><?php echo isset($_SESSION['user_id']) ? $_SESSION['usr'] : 'login';?></a>
      <form id="searchform" action="search.php" method="get"><input id="search2" name="search" type="text" style="border-radius: 5px 0px 0px 5px; border-width: 1px;  border-style: solid; border-color: #cccccc; height: 29px;width:100px"></input><input type="submit" value="?" style="border-radius: 0px 5px 5px 0px; border-width: 1px; border-style: solid solid solid none; border-color: #cccccc; height: 32px; background: linear-gradient(#eeeeee, white)"></form>
      </div>
    </div>

<script>
function myFunction() {
    var x = document.getElementById("Demo");
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace(" w3-show", "");
    }
}
</script>
  </div>
</div>

<div style="min-height:55px"></div>




