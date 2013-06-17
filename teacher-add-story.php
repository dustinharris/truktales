<?php
  echo '<p style="color:red">-- In debug mode --</p>';
  echo "<p>Title: $title</p>";
  echo "<p>Authors: $authors</p>";
  echo "<p>Illustrators: $illustrators</p>";
  for ($textcount = 0; $textcount < $pagecount + 1; $textcount++) {
    echo "<p>English text: $language1_text[$textcount]</p>";
    echo "<p>Chuukese text: $language2_text[$textcount]</p>";
  }

  // Put into database

  // Create main story entry
  $spelling_ids[0] = 1;
  $spelling_ids[1] = 2;

  $titles[0] = $title1;
  $titles[1] = $title2;

  // Get new story family id
  $query = "SELECT MAX(story_family_id) AS greatest_story_family_id FROM stories";
  $results = mysql_query($query);
  $highest_id = 0;
  while ($row = mysql_fetch_assoc($results)) {
    $highest_id = $row['greatest_story_family_id'];
  }
  $new_story_family_id = $highest_id + 1; // One id higher than existing highest

  for ($si = 0; $si < 2; $si++) { // Two records, one for each language
    $query = "INSERT INTO stories ";
    $query .= "(story_title, spelling_id, story_family_id) ";
    $query .= "VALUES ('" . $titles[$si] . "', " . $spelling_ids[$si] . ", " . $new_story_family_id . ")";
    echo $query;
    $results = mysql_query($query);
  }
?>