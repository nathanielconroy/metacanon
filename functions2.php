<?php

function vertBar(){
	global $pcount;
	if ($pcount > 0){
		echo ' &#124; ' ;
	}
}

function asterisk(){
	global $row;
	global $jstorWeight;
	global $alhWeight;
	global $alWeight;
if ($row["titlecorpusfreq"] >= 5001 AND ($jstorWeight != 0 OR $alhWeight != 0 OR $alhWeight != 0)) {echo "<span class='w3-tooltip'>*<span class='w3-text w3-tag w3-pale-yellow w3-border' style='position:absolute;right:20px;bottom:25px;width:150px'>Score adjusted to correct for title consisting of an n-gram occuring with high frequency in the English language corpus.</span></span>";}
}

function cross(){
	global $row;
	global $jstorWeight;
	global $alhWeight;
	global $alWeight;
	global $nytWeight;
if ($row["nonuniqueauthor"] >= 5001 AND ($jstorWeight != 0 OR $alhWeight != 0 OR $alhWeight != 0 OR $nytWeight !=0)) {echo "<span class='w3-tooltip'>&#8224;<span class='w3-text w3-tag w3-pale-yellow w3-border' style='position:absolute;right:20px;bottom:25px;width:300px'>Score adjusted to correct for an author win a non-unique name (for example \"Winston Churchill\").</span></span>";}
}

function doublecross(){
	global $row;
	global $nytWeight;
if ($row["corpusfreqnyt"] >= 5001 AND $nytWeight !=0) {echo "<span class='w3-tooltip'>&#8225;<span class='w3-text w3-tag w3-pale-yellow w3-border' style='position:absolute;right:20px;bottom:25px;width:300px'>New York Times score adjusted to correct for title consisting of an n-gram occuring with high frequency in the English language corpus.</span></span>";}
}

function SelectDataPointWeight($displayName,$dataPoint,$selection,$style){
    echo '
    <div>
        <label class="w3-label">' . $displayName . ' Weight:</label>
        <select class="w3-select" name="' . $dataPoint . '"' . $style . '>';
    
    for ($value = 2; $value >= 0; $value -= .25)
    {
        echo '<option value="' . number_format($value,2,'.','') . '"';
        if ($selection == $value)
		   	{ echo 'selected';}
		echo '>' . number_format($value,2,'.','') . '</option>';
    }
    
    echo '
		</select>
	</div>
	<br>
	';
}

function WeightSelectionModule($style, $weights){
    SelectDataPointWeight("Google Scholar","gsdata",$weights['google_scholar'],$style);
    SelectDataPointWeight("JSTOR (Language and Literature)","langlitdata",$weights['jstor_lang_lit'],$style);
    SelectDataPointWeight("American Literary History","alhdata",$weights['alh'],$style);
    SelectDataPointWeight("American Literature","aldata",$weights['american_literature'],$style);
    SelectDataPointWeight("Pulitzer Prize","pdata",$weights['pulitzer'],$style);
    SelectDataPointWeight("National Book Award","nbadata",$weights['nba'],$style);
    SelectDataPointWeight("New York Times Archive","nytdata",$weights['nyt'],$style);
    SelectDataPointWeight("JSTOR (Complete)","jstordata",$weights['jstor'],$style);
}
?>

