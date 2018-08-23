<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style.css">

<body>

<?php 
include 'header.php';

if (isset($_POST['presetname']))
{
    $userid = $_SESSION['id'];
    $metausername = $_SESSION['usr'];
    $presetname = addslashes($_POST['presetname']);
    
    if (!isset($_POST['oneperauth']))
    {
        $_POST['oneperauth'] = true;
    }
    
    if (!isset($_POST['womenonly']))
    {
        $_POST['womenonly'] = true;
    }
	
	
	
	$preset_parameters = ['totalbooks','numtitles', 'order', 'yearstart', 'yearend', 'gsdata', 'jstordata', 'alhdata', 'aldata', 'pdata', 'nbadata', 'nytdata', 'langlitdata', 'startnumber', 'faulkner'];
	$preset_parameters = array_merge($preset_parameters, DatabaseAccessor::getGenresList());
	$preset_parameters = array_merge($preset_parameters, DatabaseAccessor::getRegionsList());
	$preset_parameters = array_merge($preset_parameters, DatabaseAccessor::getTagsList());
	
	
    $preseturl = HTMLGenerator::generatePresetURL($_POST, $preset_parameters);
    
    UserHandling::AddUserPreset($userid,$metausername,$presetname,$preseturl);
}

if (isset($_POST['deleteid']))
{
    $deleteid = $_POST['deleteid'];
    UserHandling::DeleteUserPreset($deleteid);
}

?>

<div style="padding-bottom:16px">

<?php if(!isset($_SESSION['id'])): ?>
  <div class="w3-col l6 s12" style="padding:8px;margin-bottom:8px">
    <div class="w3-container w3-border w3-card-2" style="height:427px">
    
		<!-- Login Form -->
		<form class="clearfix" action="userlogin.php" method="post">
		<h1>Login</h1>
		  <?php
			if(isset($_SESSION['msg']) && isset($_SESSION['msg']['login-err']))
			{echo '<div class="err">'.$_SESSION['msg']['login-err'].'</div>';
			unset($_SESSION['msg']['login-err']);}
		  ?>
		  
			<label class="w3-label" for="username">Email or Username:</label>      
			<input class="w3-input" type="text" name="username" id="username" value="" size="23"/><br>
			<label class="w3-label" for="password">Password:</label>
			<input class="w3-input" type="password" name="password" id="password" size="23"/><br>
			<input class="w3-btn" type="submit" name="user_submit" value="Login" style="width:100%"/>
			<br>
			<input class="w3-check" name="rememberMe" id="rememberMe" type="checkbox" checked="checked" value="1"/>
			<label class="w3-validate">Remember me.</label>  
		</form><br>
    </div>
  </div>  
<div class="w3-col l6 s12" style="padding:8px;margin-bottom:8px">

<div class="w3-container w3-border w3-card-2" style="height:427px">
  <!-- Register Form -->
  <form action="" method="post">
  <h1>Register</h1>      
    <?php
	  if(isset($_SESSION['msg']['reg-err']))
	  {
		  echo '<div class="err">'.$_SESSION['msg']['reg-err'].'</div><br>';
          unset($_SESSION['msg']['reg-err']);
      }
          
      if(isset($_SESSION['msg']['reg-success']))
      {
		  echo '<div class="success">'.$_SESSION['msg']['reg-success'].'</div><br>';
          unset($_SESSION['msg']['reg-success']);
      }
	?>
        <label class="w3-label" for="username">Username:</label>
        <input class="w3-input" type="text" name="username" id="username" value="" size="23"/><br>
        <label class="w3-label" for="password">Password:</label>
        <input class="w3-input" type="password" name="password" id="password" value="" size="23"/><br>
        <label class="w3-label" for="email">Email:</label>
        <input class="w3-input" type="text" name="email" id="email" size="23" /><br>
        <input type="submit" name="user_submit" value="Register" class="w3-btn" style="width:100%"/>
    </form><br>
  </div>
</div>

<?php else: ?>
<div class="w3-col l6 s12" style="padding:8px;margin-bottom:8px">
  <div class="w3-container w3-border w3-card-2">
  <h3>Hello <?php echo $_SESSION['usr'] ? $_SESSION['usr'] : 'Guest';?>!</h3>
  <p>The following statstics are based on the standard metacanon list of twentieth century American fiction. User statistics for custom canons will become available with the next update (metacanon 0.8).</p>

<?php 

$booksreadcount = substr_count($booksread, ',', 1);
$booksreadarray = explode(",", $booksread);
$ids = DatabaseAccessor::getBooksForUser();
$idsarray = array();

while (($rowids = mysqli_fetch_assoc($ids)))

{
$idsarray[] = $rowids['ID'];
}

$femaleIdsArray = array();
$maleIdsArray = array();

mysqli_data_seek( $ids, 0 );

while (($rowids = mysqli_fetch_assoc($ids))){
	
	if ($rowids['authorgender'] == 'Male'){
	$maleIdsArray[] = $rowids['ID'];
	}
	if ($rowids['authorgender'] == 'Female'){
	$femaleIdsArray[] = $rowids['ID'];
	}
}
$booksRead20th = array_intersect($booksreadarray,$idsarray);

$top100 = array_slice($idsarray, 0, 100);
$top250 = array_slice($idsarray, 0, 250);
$top500 = array_slice($idsarray, 0, 500);
$top1000 = array_slice($idsarray, 0, 1000);

$booksread100 = array_intersect($booksRead20th,$top100);
$booksread250 = array_intersect($booksRead20th,$top250);
$booksread500 = array_intersect($booksRead20th,$top500);
$booksread1000 = array_intersect($booksRead20th,$top1000);

$booksReadMale = array_intersect($maleIdsArray,$booksRead20th);
$booksReadFemale = array_intersect($femaleIdsArray,$booksRead20th);
?>

<hr>
You have read <?php echo count($booksRead20th);?> books.<br><hr>

<?php
if (count($booksReadFemale) == 0){$femalePercentage = 0;}
else {$femalePercentage = 100*count($booksReadFemale)/count($booksRead20th);}
echo count($booksReadFemale). " of the books you've read were written by women. (" .number_format($femalePercentage,2). "%)<br>";
if (count($booksReadMale) == 0){$malePercentage = 0;}
else {$malePercentage = 100*count($booksReadMale)/count($booksRead20th);}
echo count($booksReadMale). " of the books you've read were written by men. (" .number_format($malePercentage,2). "%)<br><hr>";

echo "You've read " .count($booksread100). " of the top 100 books. (" .count($booksread100). "%)<br>";
echo "You've read " .count($booksread250). " of the top 250 books. (" .count($booksread250)/2.5. "%)<br>";
echo "You've read " .count($booksread500). " of the top 500 books. (" .count($booksread500)/5 ; echo "%)<br>";
echo "You've read " .count($booksread1000). " of the top 1000 books. (" .count($booksread1000)/10 ; echo "%)<br><hr>";

$booksreadcount = count($booksRead20th);

if ($booksreadcount == 0)
{$percentoutcanon = number_format(0,2);}
else
{$percentoutcanon = number_format(($booksreadcount-count($booksread1000))*100/$booksreadcount,2); }

echo $booksreadcount-count($booksread1000). " of the books you've read (" .$percentoutcanon. "%) are outside of the top 1000 books.<hr>";

?>

<form id="logOffForm" action="userlogin.php" method="post">
	<input type="hidden" name="user_submit" value="LogOff"/>
	<p><a href="#" onclick="document.getElementById('logOffForm').submit();">Log off</a></p>
</form>

</div>
</div>

<div class="w3-col l6 s12" style="padding:8px;margin-bottom:8px">
  <div class="w3-container w3-border w3-card-2">
  <h3>User Presets</h3>
  <p id="presetslist"><?php
  
  $presets = UserHandling::GetUserPresets($_SESSION['id']);
  $presetCount = 0;
  while ($ids = mysqli_fetch_assoc($presets))
  {

  echo '<a href="' .$ids['preseturl']. '">' .$ids['presetname'] . '</a> <a href="#" class="w3-tooltip" onclick="document.getElementById(\'modal5\').style.display=\'block\';defineid(' .$ids['presetid']. ')">(x)<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Delete preset.</span></a><br>';
  
  ++$presetCount;
  }
  if ($presetCount == 0) {echo "You haven't created any canon presets yet. Go to the <a href='index.php'>main page</a> to begin creating custom canons.";}

  ?></p>
  <hr>
  </div>
</div>   
<?php endif;?>
</div>

<form id="deleteid" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<input id="deleteidvalue" type="hidden" name="deleteid" value="">
</form>

<script>
function defineid(x){
	preset = x;
}
function deleteid(x){
	document.getElementById('deleteidvalue').value = x;
	document.forms['deleteid'].submit();
}
</script>

<div id="modal5" class="w3-modal">
  <div class="w3-modal-content w3-card-8 w3-animate-right">
    <header class="w3-container w3-border" style="background:#eee;height:35px">
      <span onclick="document.getElementById('modal5').style.display='none'" class="w3-closebtn">&times;</span>
    </header>
    <div class="w3-container w3-border" align="center">
      <h2>Are you sure you want to delete this preset?</h2>
	  <div class="w3-container w3-half w3-section">
      <button class="w3-btn" onclick="deleteid(preset)" style="width:100%">Yes.</button>
	  </div>
	  <div class="w3-container w3-half w3-section">
	  <button onclick="document.getElementById('modal5').style.display='none'" class="w3-btn" style="width:100%">No.</button>
      </div>
	  <hr>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
