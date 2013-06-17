<?php
	echo "<h3>Browse stories</h3>";

	// Find all languages
	$query = "SELECT * ";
	$query .= "FROM languages ";
	$query .= "ORDER BY language_id ASC";
	$result = mysql_query($query);
	if (!$result) {
		die('Invalid query: ' . mysql_error());
	}
	$language_array = array();
	while ($row = mysql_fetch_assoc($result)) {
		$language_array[] = $row["language_name"];
	}

	foreach ($language_array as &$language_in_array) {
		echo "<p><strong>$language_in_array</strong></p>";
		
		// Create table
		echo '<table class="story-list">';
		echo '<tr>';
			echo '<th>Story</th>';
			echo '<th>Edit</th>';
			echo '<th>Translate</th>';
			echo '<th>Delete</th>';
		echo '</tr>';

		$query = "SELECT * ";
		$query .= "FROM stories ";
		$query .= "INNER JOIN spellings ON stories.spelling_id = spellings.spelling_id ";
		$query .= "INNER JOIN dialects ON spellings.dialect_id = dialects.dialect_id ";
		$query .= "INNER JOIN languages ON dialects.language_id = languages.language_id ";
		$query .= "WHERE languages.language_name = '" . $language_in_array . "'";
		$result = mysql_query($query);
		if (!$result) {
			die('Invalid query: ' . mysql_error());
		}
		while ($row = mysql_fetch_assoc($result)) {
			echo '<tr><td><a href="#" class="simple-link">' . utf8_encode($row["story_title"]) . '</a></td>';
			echo '<td><a href="#" class="simple-link">Edit</a></td>';
			echo '<td><a href="#" class="simple-link">Translate</a></td>';
			if ($row['story_creator'] == $username) {
				echo '<td><a href="#" class="simple-link">Delete</a></td>';
			} else {
				echo '<td></td>';
			}
			echo '</tr>';
		}


		// Close table
		echo '</table>';
	}
?>