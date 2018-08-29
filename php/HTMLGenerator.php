<?php
class HTMLGenerator {
	static public function getDynamicCheckedMenu($user_level, $sqlResults, $checked_names)
	{
		$output = '';
		if (is_string($sqlResults)) {print($sqlResults);}
		while ($row = mysqli_fetch_array($sqlResults))
		{
			if ($user_level < $row['access_level']) {continue;}
			$output .= "<div>";
			$checked = in_array($row['name'],$checked_names) ? 'checked' : '';
			$name = $row['name'];
			$human_readable_name = $row['human_readable_name'];
			$output .= "<input class='w3-check' type='checkbox' name='$name' value='true' $checked>";
			$output .= "<label class='w3-validate'>$human_readable_name</label>";
			$output .= "</div>";
		}
		mysqli_data_seek($sqlResults, 0);
		return $output;
	}
	
	static public function generatePresetUrl($parameters_post, $preset_parameters)
	{
		$url = 'index.php?';
		for ($i = 0; $i < count($preset_parameters); $i++)
		{
			$param = $preset_parameters[$i];
			if (isset($parameters_post[$param]))
			{
				$url .= "$param=$parameters_post[$param]";
				if ($i < count($preset_parameters) - 1) 
				{	
					$url .= '&';
				}
			}
		}
		return $url;
	}
	
	static public function generateBookTable($start_number, $is_mobile, $show_author, $logged_in, $works_results, $genre_names, $weights)
	{
		$output = '';
		$row_number = $start_number + 1;
	
		// Display the text of each novel in a table
		while ( $row = mysqli_fetch_array($works_results) ) {
	
			$row_color = '';
			if ($row_number % 2 == 1) { $row_color = '#eeeeee'; }
			else { $row_color = '#ffffff'; }
	
			$genre_string = '';
			$genres = explode(',',$row['genre']);
			$genre_index = 0;
			foreach ($genres as $genre)
			{
				if ($genre_index > 0) { $genre_string .= ', '; }
				$genre_string .= $genre_names[$genre];
				$genre_index++;
			} 
			
			$output .= HTMLGenerator::generateBookTableRow($row, $row_color, $row_number, $logged_in, $is_mobile, $show_author);
			$output .= HTMLGenerator::generateScoreInfoBox($row, $weights, $row_number, $is_mobile, $genre_string);
			
			$row_number++;
		}
		
		mysqli_data_seek( $works_results, 0 );
		return $output;
	}
	
	const UNREAD_ICON = '&#9711;';
	const READ_ICON = '&#x1f453;';
	const WISHLIST_ICON = '~';
	const PARTLY_READ_ICON = '%';
	
	static public function getToolTip($text)
	{
		return "<span class=\"w3-text w3-tag w3-pale-yellow w3-border\" style=\"position:absolute;left:8;bottom:25px;width:150px\">$text</span>";
	}
	
	static public function generateBookTableRow($row, $bg_color, $row_number, $logged_in, $mobile, $show_author)
	{
		$work_id = $row['work_id'];
		$work_read = isset($row['status']) && $row['status'] == 'read';
		$on_click = '';
		if (!$logged_in)
		{
			$on_click = 'onclick="document.getElementById(\'modal1\').style.display=\'block\'"';
		}
		else 
		{
			$next_status = '';
			if (!isset($row['status']) || $row['status'] == 'unread') { $next_status = 'want_to_read'; }
			if ($row['status'] == 'want_to_read') { $next_status = 'partly_read'; }
			if ($row['status'] == 'partly_read') { $next_status = 'read'; }
			if ($row['status'] == 'read') { $next_status = 'unread'; } 
			$on_click = "onclick='setWorkStatus($work_id, \"$next_status\")'";
		}
		
		$read_icon = '';
		if (!isset($row['status']) || $row['status'] == 'unread') {$read_icon = HTMLGenerator::UNREAD_ICON;}
		else if ($row['status'] == 'read') {$read_icon = HTMLGenerator::READ_ICON;}
		else if ($row['status'] == 'want_to_read') {$read_icon = HTMLGenerator::WISHLIST_ICON;}
		else if ($row['status'] == 'partly_read') {$read_icon = HTMLGenerator::PARTLY_READ_ICON;}
		else { throw new Exception("Unknown user work status!"); }
		
		$read_tooltip = '';
		if (!isset($row['status']) || $row['status'] == 'unread') {$read_tooltip = HTMLGenerator::getToolTip("Add to wishlist.");}
		else if ($row['status'] == 'want_to_read') {$read_tooltip = HTMLGenerator::getToolTip("Mark as partly read.");}
		else if ($row['status'] == 'partly_read') {$read_tooltip = HTMLGenerator::getToolTip("Mark as read.");}
		else if ($row['status'] == 'read') {$read_tooltip = HTMLGenerator::getToolTip("Mark as unread.");}
		else { throw new Exception("Unknown user work status!"); }
		
		$title = '';
		$raw_title = $row['title'];
		if ($row["genre"] == 'article'){$title = "\"$raw_title\"";}
		else {$title = "<i>$raw_title</i>";}
		
		$output = "<tr style='background-color: $bg_color;'><td>$row_number</td>";
		$markid = $mobile ? "mmark$work_id" : "mark$work_id";
		$output .= "<td><span href='#' id='$markid' $on_click style='color:#aaaaaa;cursor:pointer;' class='w3-tooltip'>$read_icon$read_tooltip";
		$output .= "</span></td>";
		
		$author_link = '<a href="author.php?yearstart=1700&yearend=2016&authorpage=' .$row["fullname"]. '">' .$row["author_first"]. ' ' .$row["author_last"]. '</a>';
		$year_link = '<a href="index.php?totalbooks=500&numtitles=100&order=score&yearstart=' .$row["year"]. '&yearend=' .$row["year"]. '&gsdata=1&alhdata=1&aldata=1&pdata=1&nbadata=1&nytdata=0.0&startnumber=0&order=score">' .$row["year"]. '</a>';
		
		if ($mobile)
		{
			$row_id = "row$row_number" . "m";
			$on_click = "\$(\"#hidden$row_id\").toggle()";
			$output .= "<td style='cursor:pointer' id='$row_id' onclick='$on_click'><b>$title</b> ($year_link)";
			if ($show_author) 
			{ 
				$output .= "<br>$author_link";
			}
			$output .= "</td>";
		}
		else 
		{
			$row_id = "row$row_number";
			$on_click = "\$(\"#hidden$row_id\").toggle()";
			$output .= "<td style='cursor:pointer' id='$row_id' onclick='$on_click'>$title</td>";
			if ($show_author)
			{
				$output .= "<td>$author_link</td>";
			}
			$output .= "<td>$year_link</td>";
		}
		
		$output .= '<td>' .number_format($row["score"],2). '</td></tr>';
		
		// TODO: add asterisk logic.
		
		return $output;
	}
	
	static private function generateScoreBreakdown($text, $citations, $score, $weight, &$count)
	{
		$output = '';
		if ($weight != 0)
		{		
			if ($count > 0)
			{
				$output .= ' &#124; '; // Add vertical bar. 
			}
			$output .= "<b>$text</b>";
			if (isset($citations))
			{
				$output .= ": $citations";
			}
			$points_string = '';
			if ($score == 1)
			{
				$points_string = 'point';
			}
			else
			{
				$points_string = 'points';
			}
			$score = number_format($score, 2);
			$output .= " ($score $points_string)";
			// TODO: add in asterisk logic here.	
		}
		$count++;
		return $output;
	}
	
	static public function generateScoreInfoBox($row, $weights, $row_id_index, $is_mobile, $genre)
	{
		$count = 0;
		
		$row_id = "hiddenrow$row_id_index";
		if ($is_mobile) { $row_id .= 'm'; }
		
		$second_column_span = $is_mobile ? 2 : 4;
		
		$output = "<tr id='$row_id' hidden><td colspan='2'></td><td colspan='$second_column_span'><b>Genre</b>: $genre<br><br>";
		
		if ($row["pulitzer"] == 1){
			$output .= HTMLGenerator::generateScoreBreakdown("Pulitzer Prize Winner", null, $weights['pulitzer'], $weights['pulitzer'], $count);
		}
		if ($row["pulitzer"] == .5){
			$output .= HTMLGenerator::generateScoreBreakdown("Pulitzer Prize Finalist", null, $weights['pulitzer']/2, $weights['pulitzer'], $count);
		}
		if ($row["nba"] == 1){
			$output .= HTMLGenerator::generateScoreBreakdown("National Book Award Winner", null, $weights['nba'], $weights['nba'], $count);
		}
		if ($row["nba"] == .5){
			$output .= HTMLGenerator::generateScoreBreakdown("National Book Award Finalist", null, $weights['nba']/2, $weights['nba'], $count);
		}
			
		$output .= HTMLGenerator::generateScoreBreakdown("Google Scholar", $row["google_scholar"], $row['gs_score'], $weights['google_scholar'], $count);
		$output .= HTMLGenerator::generateScoreBreakdown("JSTOR", $row['jstor'], $row['jstor_score'], $weights['jstor'], $count);
		$output .= HTMLGenerator::generateScoreBreakdown("JSTOR (Language and Literature)", $row['jstor_lang_lit'], $row['lang_lit_score'], $weights['jstor_lang_lit'], $count);
		$output .= HTMLGenerator::generateScoreBreakdown("<i>American Literature</i>", $row["american_literature"], $row['american_literature_score'], $weights['american_literature'], $count);
		$output .= HTMLGenerator::generateScoreBreakdown("<i>American Literary History</i>", $row['alh'], $row['alh_score'], $weights['alh'], $count); 
		$output .= HTMLGenerator::generateScoreBreakdown('<i>New York Times</i>', $row['nyt'], $row['nyt_score'], $weights['nyt'], $count);
		
		$output .= '<br>';
		
		if ($row["title_corpus_freq"] >= 5001 AND ($jstorWeight != 0 OR $alhWeight != 0 OR $alhWeight != 0)) {
			$output .= '<br><a href="#" onclick="document.getElementById(\'modal3\').style.display=\'block\'"><b>N-gram Frequency Correction:</b> ' . number_format($row["corpuscorrection"]-100,2) . '%</a>';
		}
		if ($row["nonunique_author"] >= 5001 AND ($jstorWeight != 0 OR $alhWeight != 0 OR $alhWeight != 0 OR $nytWeight !=0)) {
			$output .= '<br><a href="#" onclick="document.getElementById(\'modal4\').style.display=\'block\'"><b>Non-unique Author Name Correction:</b> ' . number_format($row["nonuniqueauthorcalc"]-100,2) . '%</a>';
		}
		if ($row["corpus_freq_nyt"] >= 5001 AND $nytWeight !=0) {
			$output .= '<br><a href="#" onclick="document.getElementById(\'modal3\').style.display=\'block\'"><b><i>New York Times</i> N-gram Frequency Correction:</b> ' . number_format($row["nytcorpuscorrectcalc"]-100,2) . '%</a>';
		}
		$output .= '</td></tr>';
		return $output;
	}
}
?>
