<!DOCTYPE html>
<html>
<title>METACANON</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<body>

<?php include 'header.php'; ?>

<?php
$booksPerGender = DatabaseAccessor::getNumberOfBooksByGender($queryBuilder); 
$booksPerAuthor = DatabaseAccessor::getNumberOfBooksPerAuthor($queryBuilder);
$authorsPerGender = DatabaseAccessor::getNumberOfAuthorsByGender($queryBuilder);
$booksPerGenre = DatabaseAccessor::getNumberOfBooksByGenre($queryBuilder);
$pointsresult = DatabaseAccessor::getTopAuthorsByTotalPoints($queryBuilder);

$totalAuthors = DatabaseAccessor::getTotalAuthors($queryBuilder);
?>

<div>
	<div class="w3-third" style="padding:8px">
		<div class="w3-container w3-border w3-card-2">
		<p>The following statistics are drawn from the top 500 books in the standard metacanon list of twentieth century American fiction.</p>
		<p>Statistics for custom canons will become available in a future update.</p>
		</div>
		<div class="w3-container w3-border w3-card-2" style="margin-top:16px">


		<h3>Number of books by author&#8217;s gender:</h3>
		<?php
		while ( $row = mysqli_fetch_array($booksPerGender) ) 
		{
			echo ($row["author_gender"]. ': ' .$row["num"]. " (" 
			.number_format(($row["num"]/$totalbooks)*100,2). "%)<br>");
		}
		?>
		<hr>

		<h3>Number of authors by gender:</h3>
		<?php
		while ( $row = mysqli_fetch_array($authorsPerGender) ) 
		{
			echo ($row["author_gender"]. ': ' .$row["num"]. " (" 
			.number_format(($row["num"]/$totalAuthors)*100,2). "%)<br>");
		}
		?>
		<hr>

		<h3>Number of books by genre:</h3>
		<?php
		
		// TODO : this is a pretty poor way to handle books with mixed genre for statistics. Consider changing. 
		$mixedGenre = 0;
		while ( $row = mysqli_fetch_array($booksPerGenre) ) 
		{  
			switch ($row["genre"]) 
			{
				case "novel": $genre = "Novels";
				break;
				case "collection": $genre = "Collections of Short Stories";
				break;
				case "novella": $genre = "Novellas";
				break;
				case "other": $genre = "Other";
				break;
				default: $genre = "Mixed Genre";
			}

			if ($genre == "Mixed Genre")
			{
				$mixedGenre += $row["num"];
			}
			else 
			{
				echo ($genre . ': ' .$row["num"] . ' (' . number_format(($row["num"]/$totalbooks)*100,2) . '%)<br>');
			}
		}
		echo "Mixed Genre: " .($mixedGenre). ' (' . number_format(($mixedGenre/$totalbooks)*100,2) . '%)<br>';
		?>
		<hr>
		</div>
	</div>

	<div class="w3-third" style="padding:8px">
		<div class="w3-container w3-border w3-card-2">
		<h3>Top 25 authors by total number of books:</h3>
		<?php
		while ( $row = mysqli_fetch_array($booksPerAuthor) ) 
		{
			echo ("<a href='author.php?authorpage=" .$row["author_last"]. ", " .$row["author_first"]. "'>" 
			.$row["author_first"]. " " .$row["author_last"]. '</a>: ' .$row["num"]. "<br>");
		}
		?>
		<hr>
		</div>
	</div>

	<div class="w3-third" style="padding:8px">
		<div class="w3-container w3-border w3-card-2">
		<h3>Top 25 authors by total points:</h3>
		<?php
		while ( $row = mysqli_fetch_array($pointsresult) ) 
		{
			echo ("<a href='author.php?authorpage=" .$row["author_last"]. ", " .$row["author_first"]. "'>" 
			.$row["author_first"]. " " .$row["author_last"]. '</a>: ' .number_format($row["totalscore"],2). '<br>');
		}
		?>
		<hr>
		</div>
	</div>
</div>

<div class="w3-row-padding w3-section"></div>
<?php include 'footer.php'; ?>
</body>
</html>
