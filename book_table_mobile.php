<?php
	$rowcolor = 0;
	$relativerank = $startnumber + 1;
	$id = 1;
	$hiddenrow = 1;
	
	// Display the text of each novel in a table
	while ( $row = mysqli_fetch_array($fictionResults) ) {
	echo('<tr');
	  
	switch ($row["genre"]) {
	case "novel": $genre = "Novel";
	break;
	case "collection": $genre = "Collection of Short Stories";
	break;
	case "novella": $genre = "Novella";
	break;
	case "other": $genre = "Other";
	break;
	case "novel,collection": $genre = "Novel, Collection of Short Stories";
	break;
	case "novel,novella": $genre = "Novel, Novella";
	break;
	case "novel,other": $genre = "Novel, Other";
	break;
	case "collection,novella": $genre = "Collection of Short Stories, Novella";
	break;
	case "collection,other": $genre = "Collection of Short Stories, Other";
	break;
	case "novella,other": $genre = "Novella, Other";
	break;
	default: $genre = "Mixed Genre";
	} 
	
	if ($rowcolor % 2 == 0) {print ' style="background-color: #eeeeee;"';}
	echo '><td>'.$relativerank. '</td><td>'; 
	if ( strpos($booksread,"," .$row['ID']. ",") !== false )  {print '<span id="mmark' .$row['ID']. '" onclick="markasunread(' .$row['ID']. ')" class="w3-tooltip" style="cursor:pointer">&#x1f453;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as unread.</span></span></td><td style="cursor:pointer"';}
	else {echo '<span href="#" id="mmark' .$row['ID']. '" ';
	  if (!$id) {echo 'onclick="document.getElementById(\'modal1\').style.display=\'block\'"';}
	  else {echo 'onclick="markasread(' .$row['ID']. ')"';}
	  echo ' style="color:#dddddd;cursor:pointer" class="w3-tooltip">&#9661;<span class="w3-text w3-tag w3-pale-yellow w3-border" style="position:absolute;left:8;bottom:25px;width:150px">Mark as read.</span></span></td><td style="cursor:pointer"';}
	echo ' id="row' . $id . 'm"><strong>';    
	if ($row["genre"] == 'article'){echo '"';}
	else {echo '<i>';}
	echo $row["Title"];
	if ($row["genre"] == 'article'){echo '"';}
	else {echo '</i>';}
	echo '</strong> (<a href="index.php?totalbooks=500&numtitles=100&order=newscore&yearstart=' .$row["Year"]. '&yearend=' .$row["Year"]. '&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=newscore">' .$row["Year"]. '</a>)<br><a href="author.php?yearstart=1700&yearend=2016&authorpage=' .$row["fullname"]. '">' .$row["Author_First_Name"]. ' ' .$row["Author"]. '</a></td><td>' .number_format($row["newscore"],2);
	asterisk();
	cross();
	doublecross();
	echo '</td></tr><tr id="hiddenrow' . $hiddenrow. 'm">';
	echo '<td colspan="2"></td><td colspan="2"><b>Genre</b>: ' .$genre. '<br><br>';
	include 'scoreinfobox.php';
	
	$relativerank = $relativerank + 1;    
	$rowcolor = $rowcolor + 1;
	$id = $id +1;
	$hiddenrow = $hiddenrow + 1;
	}
?>
