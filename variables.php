<?php
  $language = (!empty($_GET['language']) ? $_GET['language'] : "English");
  $dialect = "Standard English";
  $spelling = "Standard Spelling";
  $story_id = (!empty($_GET['story_id']) ? $_GET['story_id'] : null);
  $debug = (!empty($_GET['debug']) ? $_GET['debug'] : "false");
  $page_number = (!empty($_GET['page_number']) ? $_GET['page_number'] : null);
  $role = (isset($_SESSION['role']) ? $_SESSION['role'] : "");
  $username = (!empty($_SESSION['username']) ? $_SESSION['username'] : null);
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

  //echo "Language: " . $language . ", " . $dialect . ", " . $spelling . "<br />";
  //echo "Story ID: $story_id";
?>