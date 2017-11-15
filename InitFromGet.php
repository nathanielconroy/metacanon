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

//genre filtering
$genres = [];
if (isset($_GET["novels"]) && $_GET["novels"] == "true")
{$genres[] = "novel";}

if (isset($_GET["collections"]) && $_GET["collections"] == "true")
{$genres[] = "collection";}

if (isset($_GET["novellas"]) && $_GET["novellas"] == "true")
{$genres[] = "novella";}

if (isset($_GET["other"]) && $_GET["other"] == "true")
{$genres[] = "other";}

if (sizeof($genres) == 0)
{
	$genres = ["novel","novella","collection","other"];
}
//end genre filtering

if (empty($_GET['order'])) {$order = 'newscore';}
else {$order = htmlspecialchars($_GET["order"]);}

//get the tag variable
if (empty($_GET["tags"]))
{
$tags = "none";
}
else{
$tags = $_GET["tags"] ;
}

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

//get weight for Language and Literature scores
if (empty ($_GET["langlitdata"]))
{$langLitWeight = "1" ;}
else
{$langLitWeight = $_GET["langlitdata"];}

//get weight for gsscores
if (empty($_GET["gsdata"]))
{$gsWeight = "1" ;}
else
{$gsWeight = $_GET["gsdata"];}

//get weight for jstorscores
if (empty($_GET["jstordata"]))
{$jstorWeight = "0" ;}
else
{$jstorWeight = $_GET["jstordata"];}

//get weight for alhscores
if (empty($_GET["alhdata"]))
{$alhWeight = "1" ;}
else
{$alhWeight = $_GET["alhdata"];}

//get weight for alscores
if (empty($_GET["aldata"]))
{$alWeight = "1" ;}
else
{$alWeight = $_GET["aldata"];}

//get weight for nytscores
if (empty($_GET["nytdata"]))
{$nytWeight = "0" ;}
else
{$nytWeight = $_GET["nytdata"];}

//get weight for pscores
if (empty($_GET["pdata"]))
{$pulitzerWeight = "1" ;}
else
{$pulitzerWeight = $_GET["pdata"];}

//get weight for nbascores
if (empty($_GET["nbadata"]))
{$nbaWeight = "1" ;}
else
{$nbaWeight = $_GET["nbadata"];}

//set default number of titles
if (empty($_GET["numtitles"])) { $numtitles = 100; }
else { $numtitles = $_GET['numtitles']; }

?>