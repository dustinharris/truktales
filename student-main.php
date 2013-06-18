<?php
  $query = "SELECT spellings.spelling_ID AS spelling_id, languages.language_ID, dialects.language_ID, dialects.dialect_ID, spellings.dialect_ID, languages.language_name ";
  $query .= "FROM languages ";
  $query .= "INNER JOIN dialects ON dialects.language_id = languages.language_id ";
  $query .= "INNER JOIN spellings ON spellings.dialect_id = dialects.dialect_id ";
  $query .= "WHERE languages.language_name = '" . $language . "'";
  $result = mysql_query($query);
  if (!$result) {
      die('Invalid query: ' . mysql_error());
  }
  $store_spelling_id = "";
  while ($row = mysql_fetch_assoc($result)) {
    $store_spelling_id = $row["spelling_id"];
    //echo "Spellings IDs for this language: " . $row["spelling_id"] . "<br />";
  }

  $query = "SELECT languages.language_name AS language FROM languages";
  $result = mysql_query($query);

  echo "<p>";
  while ($row = mysql_fetch_assoc($result)) {
    echo "<a href=\"index.php?language=" . $row['language'] . "\" class=\"simple-link\">" . $row['language'] . "</a> | ";
  }
  echo "</p>";

  //echo "Spellings IDs for this language: " . $store_spelling_id . "<br />";

  //echo "Get stories based on spelling ID: <br />";

  $query = "SELECT story_title AS title, story_id AS id FROM stories WHERE spelling_id='" . $store_spelling_id . "'";
  $result = mysql_query($query);

  while ($row = mysql_fetch_assoc($result)) {
    // Set button color. If English, blue. Else, red.
    $btn_color = "blue";
    if ($language != "English") {
      $btn_color = "red";
    }
    echo '<a href="index.php?story_id=' . $row['id'] . '&page_number=1" class="m-btn big ' . $btn_color . '">' . $row['title'] . '</a><br />';
  }
?>