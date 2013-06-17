<?php

session_start();

include 'dbconnect.php';

$language = (!empty($_GET['language']) ? $_GET['language'] : "English");
$dialect = "Standard English";
$spelling = "Standard Spelling";
$story_id = (!empty($_GET['story_id']) ? $_GET['story_id'] : null);
$debug = (!empty($_GET['debug']) ? $_GET['debug'] : "false");
$page_number = (!empty($_GET['page_number']) ? $_GET['page_number'] : null);
$role = (!empty($_SESSION['role']) ? $_SESSION['role'] : null);
$username = (!empty($_SESSION['username']) ? $_SESSION['username'] : null);
//$role = (!empty($_GET['role']) ? $_GET['role'] : "Student");
$task = (!empty($_GET['task']) ? $_GET['task'] : "none");
$pagecount = (!empty($_GET['page-count']) ? $_GET['page-count'] : 8);
$delete_page = (!empty($_GET['delete_page']) ? $_GET['delete_page'] : null);
$page_id = 0;

// Variables from story submission
$title = (!empty($_GET['title']) ? $_GET['title'] : null);
$title1 = (!empty($_GET['title1']) ? $_GET['title1'] : null);
$title2 = (!empty($_GET['title2']) ? $_GET['title2'] : null);
$authors = (!empty($_GET['authors']) ? $_GET['authors'] : null);
$illustrators = (!empty($_GET['illustrators']) ? $_GET['illustrators'] : null);

// Populate text arrays
$language1_text = array();
$language2_text = array();
for ($textcount = 0; $textcount < $pagecount + 1; $textcount++) {
  $tempL1 = (!empty($_GET['English_' . $textcount]) ? $_GET['English_' . $textcount] : "");
  $language1_text[] = $tempL1;
  $tempL2 = (!empty($_GET['Chuukese_' . $textcount]) ? $_GET['Chuukese_' . $textcount] : "");
  $language2_text[] = $tempL2;
}

// If user deleted page, go ahead and take that out now
if ($delete_page != null) {
  // Splice element out of arrays
  array_splice($language1_text,($delete_page-1),1);
  array_splice($language2_text,($delete_page-1),1);

  // Reduce page count
  $pagecount--;
}

// Create page number

$query = "SELECT pages.page_id AS page_id FROM pages WHERE pages.story_id = '$story_id' AND pages.page_number = '$page_number'";
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {
  $page_id = $row['page_id'];
  //echo "--page id: " . $page_id . "--<br />";
}

echo "Username: $username <br/> Role: $role <br/>";
echo "Language: " . $language . ", " . $dialect . ", " . $spelling . "<br />";
echo "Story ID: $story_id";

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Story | Truk Tales</title>
    <link href="css/m-styles.min.css" rel="stylesheet">
    <link href="css/main-styles.css" rel="stylesheet">
  </head>
  <body>
    <div id="outer-wrapper">
      <p>
        <a href="index.php" name="home"><img src="img/truktaleslogo.png" alt="Truk Tales logo" /></a>
      </p>

      <!-- Debug Text -->
      <?php
        if ($debug == "true") {
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
        }
      ?>

      <!-- Login Screen -->
      <?php
        if ($role != null) {
          include 'login.php';
        }
      ?>

      <!-- Teacher Main Screen -->
      <?php
        if ($role == "Teacher" && $task == "none") {
            echo '<a href="index.php?role=Teacher&task=new-story" class="m-btn big blue">New Story</a><br />';
            echo '<a href="index.php" class="m-btn big blue">Translate Story</a><br />';
            echo '<a href="index.php" class="m-btn big blue">Browse Stories</a><br />';
            echo '<a href="index.php" class="m-btn big blue">Assign Student Stories</a><br />';
        }
      ?>

      <!-- Teacher Add New Story -->
      <?php
        if ($role == "Teacher" && $task == "new-story") {
          echo '<div id="outer-story-wrapper">';
          //echo 'Delete Page: ' . $delete_page;
          echo '<form id="new-story" name="new-story" action="index.php" method="get">';
          // Store role and task
          echo '<input type="hidden" name="role" value="Teacher" />';
          echo '<input type="hidden" name="task" value="new-story" />';
          echo '<div id="story-name-wrapper">';
            echo '<div><div class="title-input-width">Story Title (English): </div><input class="title-textbox" type="text" name="title1" value="' . $title1 .'"></div>';
            echo '<div><div class="title-input-width">Story Title (Chuukese): </div><input class="title-textbox" type="text" name="title2" value="' . $title2 .'"></div>';
          echo '</div>';
          echo '<div id="authors-illustrators-wrapper">';
            echo '<div><div class="author-illustrator-width">Authors: </div><input class="auth-illustrator-textbox" type="text" name="authors" value="' . $authors .'"></div>';
            echo '<div style="clear:both;"><div class="author-illustrator-width">Illustrators: </div><input type="text" class="auth-illustrator-textbox" name="illustrators" value="' . $illustrators .'"></div>';
          echo '</div>';
            $i = 0;
            for ($i = 0; $i < $pagecount + 1; $i++) {
              $j = $i + 1;
              echo '<div class="inner-story-wrapper' . ($i % 2) . '" style="height:280px">';
                echo '<h2>Page ' . $j . '</h2>';
                echo '<div class="delete-page-link"><a href="#" name="' . $j . '">Delete Page</a></div>';
                echo '<div class="inner-story-image-wrapper" style="float:left;">';
                  echo '<img src="img/polaroid-browse.jpg" /><br />';
                  echo '<input type="file" name="image_' . $i . '" />';
                echo '</div>';
                echo '<div class="inner-story-languages-wrapper" style="float:left;">';
                  echo '<div class="inner-story-language-wrapper" style="text-align:left">';
                    echo '<p>';
                      echo '<b>English</b><br />';
                      echo '<textarea rows="4" cols="50" name="English_' . $i . '">' . $language1_text[$i] . '</textarea>';
                    echo '</p>';
                  echo '</div>';
                  echo '<div class="inner-story-language-wrapper" style="text-align:left;">';
                    echo '<p>';
                      echo '<b>Chuukese</b><br />';
                      echo '<textarea rows="4" cols="50" name="Chuukese_' . $i . '">' . $language2_text[$i] . '</textarea>';
                    echo '</p>';
                  echo '</div>';
                echo '</div>';
              echo '</div>';
              echo '<div style="clear:both"></div>';
            }
            $i = $i - 1;
            echo '<div id="add-another-page"><a href="#" name="add-page" class="simple-link">Add Another Page +</a></div>';
            //echo "number: $j";
            echo '<script>window.totalpages="' . $j . '";</script>';
            echo '<input type="hidden" id="page-count" name="page-count" value="' . $i . '" />';
            echo '<div id="create-story-button-wrapper"><a href="#" name="create-story" class="m-btn big green">Click to Create Story</a></div>';
            echo '</form>';
          echo '</div>';
        }
      ?>

      <!-- Student Main Screen -->
      <?php
        if ($story_id == null && $role == "Student") {
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
        }
        else if ($story_id != null && $page_number != null) {
          // Ready to tell a story
          //echo "--on page " . $page_id . "--";
          
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
            $query = "SELECT authors.author_name as author ";
            $query .= "FROM stories ";
            $query .= "INNER JOIN stories_authors ON stories_authors.story_id = stories.story_id ";
            $query .= "INNER JOIN authors ON authors.author_id = stories_authors.author_id ";
            $query .= "WHERE stories.story_id = '$story_id'";
            $result = mysql_query($query);
            echo '<p>Authors: ';

            // Display Authors
            while ($row = mysql_fetch_assoc($result)) {
              echo $row['author'] . " ";
            }
            echo "<br />";

            // Collect Illustrators
            $query = "SELECT illustrators.illustrator_name as illustrator ";
            $query .= "FROM stories ";
            $query .= "INNER JOIN stories_illustrators ON stories_illustrators.story_id = stories.story_id ";
            $query .= "INNER JOIN illustrators ON illustrators.illustrator_id = stories_illustrators.illustrator_id ";
            $query .= "WHERE stories.story_id = '$story_id'";
            $result = mysql_query($query);
            echo 'Illustrators: ';

            while ($row = mysql_fetch_assoc($result)) {
              echo $row['illustrator'] . " ";
            }

            echo "</p>";
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
              echo "<p>" . $row['page_text'] . "</p>";
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

        }

      ?>



      
      <!--<a href="#" class="m-btn big red">I Can See</a><br />
      <a href="#" class="m-btn big purple">Little Black Ant</a><br />
      <a href="#" class="m-btn big green">Maru</a><br />-->
      <div id="footer-text">
        <a href="#" class="simple-link">Download Stories</a> | <a href="#" class="simple-link">Settings</a> | <a href="logout.php" class="simple-link">Logout</a> 
      </div>
      <script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
      <script src="js/m-dropdown.min.js"></script>
      <script src="js/m-radio.min.js"></script>
    </div>
    <script>
      $(document).ready(function() {
        $("a").click(function(e) {
          //prevent the 'following' behaviour of hyperlinks by preventing the default action
          e.preventDefault();

          // insert page to delete as hidden input
          //alert("Name: " + this.name);
          if (this.name == "add-page") {
            // Increment total number of pages
            window.totalpages++;
            bgcolor = (window.totalpages+1)%2;

            // Scroll to the bottom of the page
            $('html, body').animate({scrollTop: $(document).height()}, 'slow');

            // Set hidden page-count value
            newpagecount = parseInt($('input[name=page-count]').val()) + 1;
            $('input[name=page-count]').val(newpagecount);

            // Create new set of divs, append to page
            $('<div class="inner-story-wrapper' + bgcolor + '" style="height:280px"><h2>Page ' + window.totalpages + '</h2><div class="delete-page-link"><a href="#" name="' + window.totalpages + '">Delete Page</a></div><div class="inner-story-image-wrapper" style="float:left;"><img src="img/polaroid-browse.jpg" /><br /><input type="file" name="image_' + (parseInt(window.totalpages) - 1) + '" /></div><div class="inner-story-languages-wrapper" style="float:left;"><div class="inner-story-language-wrapper" style="text-align:left"><p><b>English</b><br /><textarea rows="4" cols="50" name="English_' + (parseInt(window.totalpages) - 1) + '"></textarea></p></div><div class="inner-story-language-wrapper" style="text-align:left;"><p><b>Chuukese</b><br /><textarea rows="4" cols="50" name="Chuukese_' + (parseInt(window.totalpages) - 1) + '"></textarea></p></div></div></div><div style="clear:both"></div>').hide().insertBefore("#add-another-page").fadeIn("slow");
          } else if (this.name == "create-story") {
            // For now, enter debug mode
            $(this).after('<input type="hidden" name="debug" value="true" />');

            // Submit form
            $('#new-story').submit();

          } else if (this.name =="home") {
            url = "index.php";
            // IE8 and lower fix
            if (navigator.userAgent.match(/MSIE\s(?!9.0)/)) {
                var referLink = document.createElement('a');
                referLink.href = url;
                document.body.appendChild(referLink);
                referLink.click();
            } 

            // All other browsers
            else {
              window.location.href = url;
            }
          } else if (this.name != "") {
            // Deleting a page 
            $(this).parent().append('<input type="hidden" name="delete_page" value="' + this.name + '" />');

            // submit form
            $('#new-story').submit();
          } else {
            window.location.replace(this.href);
          }
        });
      });
    </script>
  </body>
</html>

<?php mysql_close($li); ?>