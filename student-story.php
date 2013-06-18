<?php
  // Begin page_wrapper div for swipe functionality
  echo '<div id="page_wrapper">';

  // Collect image
  $query = "SELECT pictures.picture_file AS image ";
  $query .= "FROM pictures ";
  $query .= "INNER JOIN pages ON pages.picture_id = pictures.picture_id ";
  $query .= "WHERE pages.page_id = '" . $page_id . "'";
  $result = mysql_query($query);

  // Display Image
  while ($row = mysql_fetch_assoc($result)) {
    echo '<img src="data:image/jpeg;base64,' . base64_encode( $row['image'] ) . '" /><br />';
  }

  // If this is the first page, show the authors and illustrators
  if ($page_number == 1) {
    // Collect Authors
    $query = "SELECT story_authors, story_illustrators ";
    $query .= "FROM stories ";
    $query .= "WHERE stories.story_id = '$story_id'";
    $result = mysql_query($query);

    // Display Authors
    while ($row = mysql_fetch_assoc($result)) {
      echo '<p>Authors: ' . $row['story_authors'];
      echo "<br />";
      echo 'Illustrators: ' . $row['story_illustrators'];
      echo "</p>";
    }
  }

  // If this isn't the first page, show the story text.

  if ($page_number != 1) {
    // Collect Text
    $query = "SELECT page_text.text AS page_text ";
    $query .= "FROM pages ";
    $query .= "INNER JOIN page_text ON page_text.page_id = pages.page_id ";
    $query .= "WHERE pages.page_id = '" . $page_id . "'";
    $query .= "ORDER BY page_text.page_text_order ASC";
    $result = mysql_query($query);

    // Display Text
    while ($row = mysql_fetch_assoc($result)) {
      echo "<p>" . utf8_encode($row['page_text']) . "</p>";
    }
  }

  echo "Page " . $page_number . " | ";
  $query = "SELECT COUNT(*) AS numpages FROM pages WHERE pages.story_id = '" . $story_id . "'";
  $result = mysql_query($query);
  $final_page = 0;
  while($row = mysql_fetch_assoc($result)){
      $final_page = $row['numpages'];
  }
  if ($page_number < $final_page) {
    echo "<a href=\"index.php?story_id=" . $story_id . "&page_number=" . (int) ($page_number + 1) . "\">Next Page</a>";
  } else {
    echo '<a href="index.php" class="simple-link">Close Story</a>';
  }

  // Close page_wrapper div for swipe functionality
  echo "</div>";
?>