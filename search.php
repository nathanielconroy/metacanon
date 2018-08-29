<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1" http-equiv="Content-Type">

<?php

include 'header.php';

//get search terms
$searchTerm = $_GET["search"];

$result = DatabaseAccessor::getSearchResults($searchTerm);
$count = DatabaseAccessor::getSearchResultsCount($searchTerm);
?>

<body>

<div style="padding:8px">
  <div class="w3-container w3-border w3-card-2" style="min-height:344px;margin-bottom:8px">
	<?php

	if ($count == 1)
	{
		echo "<h3>1 result found:</h3>";
	}
	else
	{	
		echo "<h3>$count results found:</h3>";
	}

	echo "<p>";
	while ( $row = mysqli_fetch_array($result) )
	{
		echo '<a href="author.php?yearstart=1700&yearend=2016&authorpage=' .$row['fullname']. '">' 
		.($row['author_first']. ' ' .$row['author_last']). '</a><br>' ;
	}
	echo "</p>";
	?>
  </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
