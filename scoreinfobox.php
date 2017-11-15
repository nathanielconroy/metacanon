<?php

$pcount = 0;

if ($row["pulitzer"] == 1 and $pulitzerWeight != 0){
	vertBar();
	echo '<b>Pulitzer Prize Winner</b> (' .number_format($pulitzerWeight,2); 
	if ($pulitzerWeight == 1) {echo ' point)';}
	else {echo ' points)';}
	$pcount += 1;
	}
if ($row["pulitzer"] == .5 and $pulitzerWeight != 0){
	vertBar(); 
	echo '<b>Pulitzer Prize Finalist</b> (' .number_format(($pulitzerWeight/2),2); 
	if ($pulitzerWeight == 2) {echo ' point)';}
	else {echo ' points)';}
	$pcount += 1;
	}
if ($row["nba"] == 1 and $nbaWeight != 0){
	vertBar(); 
	echo '<b>National Book Award Winner</b> (' .number_format($nbaWeight,2); 
	if ($nbaWeight == 1) {echo ' point)';}
	else {echo ' points)';} 
	$pcount += 1;
	}
if ($row["nba"] == .5 and $nbaWeight != 0){
	vertBar(); 
	echo '<b>National Book Award Finalist</b> (' .number_format(($nbaWeight/2),2); 
	if ($nbaWeight == 2) {echo ' point)';}
	else {echo ' points)';}
	$pcount += 1;
	}
if ($gsWeight != 0){
	vertBar();
	echo'<b>Google Scholar</b>: ' .$row["googlescholar"]. ' (' .number_format($row["gsscore"],2). ' points) '; 
	$pcount += 1;
}
if ($jstorWeight != 0){
	vertBar();
	echo '<b>JSTOR</b>: ' .$row["jstor"]. ' (' .number_format($row["jstorscore"],2). ' points)';
	asterisk();
	cross();
	$pcount += 1;
}
if ($langLitWeight != 0){
	vertBar();
	echo '<b>JSTOR (Language and Literature)</b>: ' .$row["jstorLangLit"]. ' (' .number_format($row["langlitscore"],2). ' points)';
	asterisk();
	cross();
	$pcount += 1;
}
if ($alWeight != 0){
	vertBar();
	echo '<b><i>American Literature</i></b>: ' .$row["americanliterature"]. ' (' .number_format($row["americanliteraturescore"],2). ' points)';
	asterisk();
	cross();
	$pcount += 1;
}
if ($alhWeight != 0){
	vertBar();
	echo '<b><i>American Literary History</i></b>: ' .$row["alh"]. ' (' .number_format($row["alhscore"],2). ' points)';	
	asterisk();
	cross();
	$pcount += 1;
}
if ($nytWeight != 0){
	vertBar();
	echo '<b><i>New York Times</i> Archive</b>: ' .$row["nyt"]. ' (' .number_format($row["nytscore"],2). ' points)';
	cross();
	doublecross();
	$pcount += 1;	
}
echo '<br>';
if ($row["titlecorpusfreq"] >= 5001 AND ($jstorWeight != 0 OR $alhWeight != 0 OR $alhWeight != 0)) {
echo '<br><a href="#" onclick="document.getElementById(\'modal3\').style.display=\'block\'"><b>N-gram Frequency Correction:</b> ' . number_format($row["corpuscorrection"]-100,2) . '%</a>';}
if ($row["nonuniqueauthor"] >= 5001 AND ($jstorWeight != 0 OR $alhWeight != 0 OR $alhWeight != 0 OR $nytWeight !=0)) {
echo '<br><a href="#" onclick="document.getElementById(\'modal4\').style.display=\'block\'"><b>Non-unique Author Name Correction:</b> ' . number_format($row["nonuniqueauthorcalc"]-100,2) . '%</a>';}
if ($row["corpusfreqnyt"] >= 5001 AND $nytWeight !=0) {
echo '<br><a href="#" onclick="document.getElementById(\'modal3\').style.display=\'block\'"><b><i>New York Times</i> N-gram Frequency Correction:</b> ' . number_format($row["nytcorpuscorrectcalc"]-100,2) . '%</a>';}
//echo '<br><b>mRank:</b> ' .$row["mrank"] ; 
echo '</td></tr>';
?>
