<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1; text/html; charset=UTF-8" http-equiv="Content-Type">

<?php 

//Get name of author.
$authorpageshort = $_GET["authorpage"];
$author = str_replace("'","''",$authorpageshort);
?>

<body>

<?php include 'header.php'; 
$results = DatabaseAccessor::GetStandardResults($queryBuilder);
$row = mysqli_fetch_array($results)
?>

<div class="w3-col l12 s12" style="padding-right:8px;padding-left:8px">
  <div class="w3-container w3-border w3-card-2" style="margin-top:8px;margin-bottom:16px;min-height:350px">
    <div class="w3-row">
      <div class="w3-container w3-col l1 s12"></div>
      <div class="w3-container w3-col l10 s12" style="text-align: center;"><h1><?php echo ($row["Author_First_Name"]. ' ' .$row["Author"]); ?></h1></div>
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
$rowcolor = 0;
$relativerank = $startnumber + 1;
$id = 1;
$hiddenrow = 1;

// Display the text of each novel in a table
mysqli_data_seek( $results, 0 );
while ( $row = mysqli_fetch_array($results) ) {

echo('<tr');

if ($rowcolor % 2 == 0) {print ' style="background-color: #eeeeee;"';}
echo '><td>'.$relativerank. '</td><td>'; 
if ( /*strpos($booksread,"," .$row['ID']. ",") !== */ false )  {print '<span id="mark' .$row['ID']. '" onclick="markasunread(' .$row['ID']. ')" class="w3-tooltip" style="cursor:pointer">&#x1f453;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as unread.</span></span></td><td style="cursor:pointer"';}
else {echo '<span href="#" id="mark' .$row['ID']. '" ';
  if (/*!$_SESSION['id']*/ true) {echo 'onclick="document.getElementById(\'modal1\').style.display=\'block\'"';}
  else {echo 'onclick="markasread(' .$row['ID']. ')"';}
  echo ' style="color:#dddddd;cursor:pointer" class="w3-tooltip">&#9661;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as read.</span></span></td><td style="cursor:pointer"';}
echo ' id="row' . $id . '">';    
if ($row["genre"] == 'article'){echo '"';}
else {echo '<i>';}
echo $row["Title"];
if ($row["genre"] == 'article'){echo '"';}
else {echo '</i>';}
echo '</td><td><a href="index.php?totalbooks=500&numtitles=100&order=score&yearstart=' .$row["Year"]. '&yearend=' .$row["Year"]. '&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=score">' .$row["Year"]. '</a></td><td>' .number_format($row["newscore"],2);

asterisk();
cross();
doublecross();

echo '</td></tr>';
echo '<tr id="hiddenrow' . $hiddenrow. '">';
echo '<td colspan="2"></td><td colspan="4"><b>Genre</b>: ' .$row["genre"]. '<br><br>';

include 'scoreinfobox.php';

$relativerank = $relativerank + 1;    
$rowcolor = $rowcolor + 1;
$id = $id +1;
$hiddenrow = $hiddenrow + 1;
}

if ($id != 1){
	mysqli_data_seek( $results, 0 );
}
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
$rowcolor = 0;
$relativerank = $startnumber + 1;
$id = 1;
$hiddenrow = 1;

// Display the text of each novel in a table
while ( $row = mysqli_fetch_array($results) ) {
echo('<tr');
  
if ($rowcolor % 2 == 0) {print ' style="background-color: #eeeeee;"';}
echo '><td>'.$relativerank. '</td><td>'; 
if ( strpos($booksread,"," .$row['ID']. ",") !== false )  {print '<span id="mmark' .$row['ID']. '" onclick="markasunread(' .$row['ID']. ')" class="w3-tooltip" style="cursor:pointer">&#x1f453;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as unread.</span></span></td><td style="cursor:pointer"';}
else {echo '<span href="#" id="mmark' .$row['ID']. '" ';
  if (!$_SESSION['id']) {echo 'onclick="document.getElementById(\'modal1\').style.display=\'block\'"';}
  else {echo 'onclick="markasread(' .$row['ID']. ')"';}
  echo ' style="color:#dddddd;cursor:pointer" class="w3-tooltip">&#9661;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as read.</span></span></td><td style="cursor:pointer"';}
echo ' id="row' . $id . 'm"><strong>';    
if ($row["genre"] == 'article'){echo '"';}
else {echo '<i>';}
echo $row["Title"];
if ($row["genre"] == 'article'){echo '"';}
else {echo '</i>';}
echo '</strong> (<a href="index.php?totalbooks=500&numtitles=100&order=score&yearstart=' .$row["Year"]. '&yearend=' .$row["Year"]. '&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=score">' .$row["Year"]. '</a>)<br><a href="author.php?authorpage=' .$row["fullname"]. '">' .$row["Author_First_Name"]. ' ' .$row["Author"]. '</a></td><td>' .number_format($row["newscore"],2);
asterisk();
cross();
doublecross();
echo '</td></tr><tr id="hiddenrow' . $hiddenrow. 'm">';
echo '<td colspan="2"></td><td colspan="2"><b>Genre</b>: ' .$row["genre"]. '<br><br>';
include 'scoreinfobox.php';

$relativerank = $relativerank + 1;    
$rowcolor = $rowcolor + 1;
$id = $id +1;
$hiddenrow = $hiddenrow + 1;
}
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
