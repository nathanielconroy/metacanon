<?php

function asterisk(){
if ($row["titlecorpusfreq"] >= 5001) {echo "<span class='w3-tooltip'>*<span class='w3-text w3-tag w3-pale-yellow w3-border' style='position:absolute;right:20px;bottom:25px;width:150px'>Score adjusted to correct for title consisting of an n-gram occuring with high frequency in the English language corpus.</span></span>";}
}

function cross(){
if ($row["nonuniqueauthor"] >= 5001) {echo "<span class='w3-tooltip'>&#8224;<span class='w3-text w3-tag w3-pale-yellow w3-border' style='position:absolute;right:20px;bottom:25px;width:300px'>Score adjusted to correct for an author win a non-unique name (for example \"Winston Churchill\").</span></span>";}
}

function doublecross(){
if ($row["corpusfreqnyt"] >= 5001) {echo "<span class='w3-tooltip'>&#8225;<span class='w3-text w3-tag w3-pale-yellow w3-border' style='position:absolute;right:20px;bottom:25px;width:300px'>New York Times score adjusted to correct for title consisting of an n-gram occuring with high frequency in the English language corpus.</span></span>";}
}

?>