<?php

//set $totalbooks to the total number of books in the canon
if (empty($_GET["totalbooks"])) {$totalbooks = 500;}
else {$totalbooks = htmlspecialchars($_GET["totalbooks"]);}

//get number of titles to display per page
if (empty($_GET["numtitles"])) { $numtitles = "100"; }
else { $numtitles = htmlspecialchars($_GET["numtitles"]); }

//get number to start the list with
if (empty($_GET["startnumber"])) { $startnumber = "0"; }
else { $startnumber = htmlspecialchars($_GET["startnumber"]); }

//determine whether or not to show only one work per author
if (empty($_GET["oneperauth"])) { $oneBookPerAuthor = false; }
else if ($_GET["oneperauth"] == true) { $oneBookPerAuthor = true; }
else { $oneBookPerAuthor = false;}

//determine whether or not to show only books by women
if (empty($_GET["womenonly"]))  
{ 
	$authorgender =""; 
}
else if ($_GET["womenonly"])
{ 
	$authorgender = "female"; 
}
else
{
	$authorgender = "";
}

$included_genres = [];
$genres = DatabaseAccessor::getGenres();
$genre_names = array();

while ($genre_row = mysqli_fetch_array($genres))
{
	if (isset($_GET[$genre_row['name']]) && $_GET[$genre_row['name']] == 'true')
	{
		$included_genres[] = $genre_row['name'];
	}
	
	$genre_names[$genre_row['name']] = $genre_row['human_readable_name'];
}

// Reset genres object back to first row.
mysqli_data_seek($genres, 0);

if (sizeof($included_genres) == 0)
{
	$included_genres = ["novel","novella","collection","other"];
}


$included_regions = [];
$regions = DatabaseAccessor::getRegions();

while ($regions_row = mysqli_fetch_array($regions))
{
	if (isset($_GET[$regions_row['name']]) && $_GET[$regions_row['name']] == 'true')
	{
		$included_regions[] = $regions_row['name'];
	}
}

// Reset regions object back to first row.
mysqli_data_seek($regions, 0);

if (sizeof($included_regions) == 0)
{
	$included_regions = ["unitedstates"];
}


// Get included tags.
$included_tags = [];
$tags = DatabaseAccessor::getTags();

while ($row = mysqli_fetch_array($tags))
{
	if (isset($_GET[$row['name']]) && $_GET[$row['name']]  == 'true')
	{
		$included_tags[] = $row['name'];
	}
}
mysqli_data_seek($tags, 0);



if (empty($_GET['order'])) {$order = 'score';}
else {$order = htmlspecialchars($_GET["order"]);}


if (empty($_GET["yearstart"]))
{$yearstart = "1900";}
else{
$yearstart = $_GET["yearstart"];
}

if (empty($_GET["yearend"]))
{$yearend = "1999";}
else{
$yearend = $_GET["yearend"];
}

if (isset($_GET["faulkner"]) && $_GET["faulkner"] == "false") 
{
	$faulkner = false;
}
else 
{
	$faulkner = true;
}

$weights = array(
	'nba' => isset($_GET['nbadata']) ? $_GET['nbadata'] : 1,
	'pulitzer' => isset($_GET['pdata']) ? $_GET['pdata'] : 1,
	'jstor_lang_lit' => isset($_GET['langlitdata']) ? $_GET['langlitdata'] : 1,
	'google_scholar' => isset($_GET['gsdata']) ? $_GET['gsdata'] : 1,
	'jstor' => isset($_GET['jstordata']) ? $_GET['jstordata'] : 0,
	'alh' => isset($_GET['alhdata']) ? $_GET['alhdata'] : 1,
	'american_literature' => isset($_GET['aldata']) ? $_GET['aldata'] : 1,
	'nyt' => isset($_GET['nytdata']) ? $_GET['nytdata'] : 0,
);

//set default number of titles
if (empty($_GET["numtitles"])) { $numtitles = 100; }
else { $numtitles = $_GET['numtitles']; }

?>
