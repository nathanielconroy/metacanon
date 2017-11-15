<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1; text/html; charset=UTF-8" http-equiv="Content-Type">
<link rel="stylesheet" href="css/style.css">

<?php
include 'vendor/autoload.php';
include 'php/DatabaseConfig.php';
include 'php/DatabaseAccessor.php';

//get search terms
$searchTerm = $_GET["search"];

$result = DatabaseAccessor::getSearchResults($searchTerm);
$countResults = DatabaseAccessor::getSearchResultsCount($searchTerm);
$count = mysqli_fetch_assoc($countResults);
?>

<body>
<?php include 'header.php'; ?>

<div style="padding:8px">
  <div class="w3-container w3-border w3-card-2" style="min-height:344px;margin-bottom:8px">
	<?php

	if ($count['total'] == 1)
	{
		echo "<h3>1 result found:</h3>";
	}
	else
	{	
		echo "<h3>" .$count['total']. " results found:</h3>";
	}

	echo "<p>";
	while ( $row = mysqli_fetch_array($result) )
	{
		echo '<a href="author.php?yearstart=1700&yearend=2016&authorpage=' .$row['fullname']. '">' 
		.($row['Author_First_Name']. ' ' .$row['Author']). '</a><br>' ;
	}
	echo "</p>";
	?>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
