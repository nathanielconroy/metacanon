<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="Content-Type">

<?php 

//Get name of author.
$authorpageshort = $_GET["authorpage"];
$author = str_replace("'","''",$authorpageshort);
include 'header.php'; 
?>

<body>

<div class="w3-col l12 s12" style="padding-right:8px;padding-left:8px">
  <div class="w3-container w3-border w3-card-2" style="margin-top:8px;margin-bottom:16px;min-height:350px">
    <div class="w3-row">
      <div class="w3-container w3-col l1 s12"></div>
      <div class="w3-container w3-col l10 s12" style="text-align: center;"><h1><?php echo $authorpageshort; ?></h1></div>
    <div class="w3-container w3-border-bottom w3-row" style="text-align: center;"><p id="pagination1"></p>
  </div>

<form id="myform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="get">
<select id="dropdown" name="order" onchange="orderfunction()" hidden>
<option value="score" <?php echo $selectedrank ?>>Score</option>
<option value="year" <?php echo $selectedyear ?>>Year</option>
<option value="title" <?php echo $selectedtitle ?>>Title</option>
</select>
<input type="submit" name="formSubmit" value="Submit" onclick="orderfunction()" hidden>
<input id="option" type="hidden" name="order" value="">
<input id="authorpage" type="hidden" name="authorpage" value="<?php echo $authorpageshort ; ?>">
</form>	
	
<table class="w3-table w3-bordered desktoponly">
  <tr>
    <td style="font-weight: bold; width: 5%;">#</td>
    <td style="font-weight: bold; width: 5%;" align="right"></td>
    <td style="width: 230px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="bottle(); submitform();" id="title">Title</a></td>
    <td style="width: 75px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="year(); submitform();" id="year">Year</a></td>
    <td style="width: 75px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="score(); submitform();" id="score">Score</a></td>
  </tr>

<?php
	$mobile = False;
	$show_author = False;	
	$logged_in = isset($_SESSION['user_id']);
	echo HTMLGenerator::generateBookTable($startnumber, $mobile, $show_author, $logged_in, $fictionResults, $genre_names, $weights);
?>
</table>

<table class="w3-table w3-bordered mobileonly">
  <tr>
    <td style="font-weight: bold; width: 5%;">#</td>
    <td style="font-weight: bold; width: 5%;" align="right"></td>
    <td></td>
    <td style="width: 75px; font-weight: bold;" align="undefined" valign="undefined"><a href="#" onclick="score(); submitform();" id="score">Score</a></td>
  </tr>

<?php
	$mobile = True;
	$show_author = False;	
	$logged_in = isset($_SESSION['user_id']);
	echo HTMLGenerator::generateBookTable($startnumber, $mobile, $show_author, $logged_in, $fictionResults, $genre_names, $weights);
?>
</table>


    <div class="w3-container" style="text-align: center;"><p id="pagination2"></p>
    </div>
  </div>
</div>

</div>

<!-- User Not Logged In Popup -->
<div id="modal1" class="w3-modal">
  <div class="w3-modal-content w3-card-8 w3-animate-right">
    <header class="w3-container w3-border" style="background:#eee;height:35px">
      <span onclick="document.getElementById('modal1').style.display='none'" class="w3-closebtn">&times;</span>
    </header>
    <div class="w3-container w3-border" align="center">
      <h2>You must be logged in to do that.</h2><br>
      <a href="userlogin.php" class="w3-btn w3-large">Click here to log in or register.</a>
      <hr>
    </div>
  </div>
</div>

<!-- n-gram Frequency Correction Popup -->
<?php include 'ngramfreq.php'; ?>
<?php include 'footer.php'; ?>

</body>
</html>
