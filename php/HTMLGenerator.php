<?php
class HTMLGenerator {
	static public function getDynamicCheckedMenu($user_level, $sqlResults, $checked_names)
	{
		$output = '';
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
}
?>
