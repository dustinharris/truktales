<?php
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
?>