<?php
  // Outer wrapper for all pages
  echo '<div id="book">';

    // Count total number of pages
    $query = "SELECT COUNT(*) AS numpages FROM pages WHERE pages.story_id = '" . $story_id . "'";
    $result = mysql_query($query);
    $final_page = 0;
    while($row = mysql_fetch_assoc($result)){
        $final_page = $row['numpages'];
    }

    // For loop to create divs for each page
    for ($k = 0; $k < $final_page; $k++) {

      // Get page id
      $query = "SELECT pages.page_id AS page_id ";
      $query .= "FROM pages ";
      $query .= "WHERE pages.page_number = '" . ($k + 1) . "' AND pages.story_id = '" . $story_id . "'";
      $result = mysql_query($query);
      while ($row = mysql_fetch_assoc($result)) {
        $page_id = $row['page_id'];
      }

      // Get story name
      $query = "SELECT stories.story_title AS story_title ";
      $query .= "FROM stories ";
      $query .= "WHERE stories.story_id = '" . $story_id . "'";
      $result = mysql_query($query);
      while ($row = mysql_fetch_assoc($result)) {
        $story_title = utf8_encode($row['story_title']);
      }

      if ($k == 0) {
        // Begin page_wrapper div for swipe functionality
        echo '<div class="page" id="titlepage">';

          // Special case: on title page
          echo '<div class="title" style="font-size:50px;margin:10px 0;">';
            echo $story_title;
          echo '</div>';

      } else {
        // Not title page. Div name based on page number
        echo '<div class="page" id="page' . $k . '">';
      }

      // Collect image
      $query = "SELECT pictures.picture_file AS image ";
      $query .= "FROM pictures ";
      $query .= "INNER JOIN pages ON pages.picture_id = pictures.picture_id ";
      $query .= "WHERE pages.page_id = '" . $page_id . "'";
      $result = mysql_query($query);

      // Display Image
      while ($row = mysql_fetch_assoc($result)) {
        echo '<div class="image" style="margin-bottom:10px;"><img src="data:image/jpeg;base64,' . base64_encode( $row['image'] ) . '" /></div>';
      }

      // If this is the first page, show the authors and illustrators
      if ($k == 0) {
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
      } else {
        // If this isn't the first page, show the story text.

        // Collect Text
        $query = "SELECT page_text.text AS page_text ";
        $query .= "FROM pages ";
        $query .= "INNER JOIN page_text ON page_text.page_id = pages.page_id ";
        $query .= "WHERE pages.page_id = '" . $page_id . "'";
        $query .= "ORDER BY page_text.page_text_order ASC";
        $result = mysql_query($query);

        // Display Text
        while ($row = mysql_fetch_assoc($result)) {
          echo '<div style="font-size:42px;width:500px;margin:0 auto;">' . utf8_encode($row['page_text']) . "</div>";
        }
      }

      /*echo "Page " . $page_number . " | ";
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
      }*/

      // Close page_wrapper div for swipe functionality
      echo "</div>";
    }

  // Close outer book wrapper
  echo '</div>';
  echo 
    "<script type=\"text/javascript\">
      $(window).ready(function() {
        $('#book').turn({
          display: 'single',
          height: 700
        });
      });
    </script>"
?>