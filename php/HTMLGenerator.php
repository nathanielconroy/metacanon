<?php
class HTMLGenerator {
	
	static public function getGenreMenu($user_level,$genres,$checked_genres)
	{
		$output = '';
		while ($genre_row = mysqli_fetch_array($genres))
		{
			if ($user_level < $genre_row['access_level']) {continue;}
			$output .= "<div>";
			$check_genre = in_array($genre_row['name'],$checked_genres) ? 'checked' : '';
			$genre_name = $genre_row['name'];
			$genre_readable_name = $genre_row['human_readable_name'];
			$output .= "<input class='w3-check' type='checkbox' name='$genre_name' value='true' $check_genre>";
			$output .= "<label class='w3-validate'>$genre_readable_name</label>";
			$output .= "</div>";
		}
		mysqli_data_seek($genres, 0);
		return $output;
	}
}
?>
