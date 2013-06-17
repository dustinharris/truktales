<?php

session_start();

include 'dbconnect.php';
include 'variables.php';

?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
        if ($role == null) {
          include 'login.php';
        }
      ?>

      <?php
        // Teacher Role
        if ($_SESSION['role'] == "Teacher") {
          if ($task == "none") {
            // Teacher Main Screen
            include 'teacher-main.php';
          } else if ($task == "new-story") {
            // Teacher New Story
            include 'teacher-new-story.php';
          } else if ($task == "browse-stories") {
            // Teacher Browse Stories
            include "teacher-browse-stories.php";
        }
      ?>

      <?php
        // Student Role
        if ($_SESSION['role'] == "Student") {
          if ($story_id == null) {
            // Student Main Screen
            include 'student-main.php';
          } else if ($story_id != null && $page_number != null) {
            // Student Story Mode
            include 'student-story.php';
          }
        }
      ?>

      <div id="footer-text">
        <a href="#" class="simple-link">Download Stories</a> | <a href="#" class="simple-link">Settings</a> | <a href="logout.php" class="simple-link">Logout</a> 
      </div>
      <?php
        echo "<p>Username: $username <br/> Role: $role <br/>";
        echo "Language: " . $language . ", " . $dialect . ", " . $spelling . "<br />";
        echo "Story ID: $story_id</p>";
      ?>
      <script type="text/javascript" src="js/jquery-1.8.0.min.js"></script>
      <script type="text/javascript" src="js/m-dropdown.min.js"></script>
      <script type="text/javascript" src="js/m-radio.min.js"></script>
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

<?php mysql_close($dbhandle); ?>