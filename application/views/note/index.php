<?php

include_once 'functions.php';

connectDB();

$menuHierarchy = getCatAndSubCats();
$recentlyUpdated = getRecentUpdateAndMostSearch();
$mostSearched = getRecentUpdateAndMostSearch(false);

closeDB();
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Private Learning Notes</title>
    <script type="text/javascript" src='../../../ckeditor/jquery.min.js'></script>
    <script src="../../../ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src='note_master.js'></script>
    <link rel="stylesheet" href="note.css" media="screen" charset="utf-8">
    <script src="../../../ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
  	<!-- <script>hljs.initHighlightingOnLoad();</script> -->
  	<link href="../../../ckeditor/plugins/codesnippet/lib/highlight/styles/tomorrow-night-eighties.css" rel="stylesheet">
  </head>
  <body>

    <div class="wrapper">
      <div class='left-side-outer'>
        <div class='left-side-inner'>
          <div class="left-side">
            <div class="logo-area">
              <div class="logo">
                  <a href=""><img src="../../../assets/img/logo.png" alt="CK Note" /></a>
              </div>
            </div>

            <div class="create-note">
              <button type="button" name="create-note" id='create-note'>Create Note</button>
            </div>

            <div class="menu">
              <?php
                $menu = '';
                foreach ($menuHierarchy as $cat => $subcat) {
                    $menu .= '<div class="category">';
                    $menu .= '<h2>'.$cat.'</h2><ul>';
                    foreach ($subcat as $subcat => $notes) {
                        $menu .= "<li><h4>$subcat</h4><ul>";
                        foreach ($notes as $key => $value) {
                            $menu .= "<li><a onclick='showNote(event, this, 1, 0)' href=''>$value</a></li><span>$key</span>";
                        }
                        $menu .= '</ul></li>';
                    }
                    $menu .= '</ul></div>';
                }
                echo $menu;
              ?>
            </div>
          </div>
        </div>
      </div>

      <div class="right-side">
        <div id="popup"></div>
        <div class="search-area">
          <form>
            <input type="text" name="search" placeholder="Search Note" value="">
          </form>
        </div>
        <div class="list-area">
          <div class="list-block recent-update">
            <h2 class='list-topic'>Recently Updated</h2>
            <div class='list-content'>
              <?php echo $recentlyUpdated; ?>
            </div>
          </div>
          <div class="list-block most-searched">
            <h2 class='list-topic'>Most Searched</h2>
            <div class='list-content'>
              <?php echo $mostSearched; ?>
            </div>
          </div>
        </div>
        <div class="create-area">
          <form class='create-form'>
            <div class="cat-area">
              <h2 class='list-topic'>To Where</h2>
              <p class='cat-option'>
                <span>Category:</span>
                <select id="old-cats" name="old-cats">
                  <?php
                    $catOptions = '';
                    foreach ($menuHierarchy as $cat => $subcats) {
                        $catOptions .= '<option value="'.strtolower($cat).'">'.$cat.'</option>';
                    }
                    echo $catOptions;
                  ?>
                </select> Or
                <input type="text" id='new-cat' name="new-cat" placeholder="Create New Category">
              </p>
              <p class='table-option'>
                <span>Sub-Cat:</span>
                <select id="old-sub-cats" name="old-sub-cats">
                  <?php
                    $subCatOptions = '';
                    $tmpArray = array_values($menuHierarchy);
                    foreach ($tmpArray[0] as $subcat => $titles) {
                        $subCatOptions .= '<option value="'.strtolower($subcat).'">'.$subcat.'</option>';
                    }
                    echo $subCatOptions;
                  ?>
                </select> OR
                <input type="text" id='new-sub-cat' name="new-sub-cat" placeholder="Create New Sub-Category">
              </p>
            </div>
            <div class="note-area">
              <h2 class='list-topic'>Title</h2>
        			<!-- <textarea name='editor2' rows='2' id='editor2'></textarea> -->
              <input type="text" name="title" id='title' value="">
        			<h2 class='list-topic'>Content</h2>
        			<textarea name="editor1" id='editor1'></textarea>
        			<div class='button-area'>
        				<button onclick="createNote(0,0)" type="button" id="create">Create</button>
                <button onclick="createMore()" type="button" id="create_more">Create Another?</button>
        				<button onclick="createNote(1,0)"type="button" id="create-quit">Create and Quit</button>
                <button onclick="createNote(0,1)" type="button" id="update">Update</button>
        				<button onclick="createNote(1,1)"type="button" id="update-quit">Update and Quit</button>
        				<button type="button" id="cancel">Back to Homepage</button>
                <button type="button" id="back_to_search">Back to Search</button>
                <button type="button" id="back_to_last_post">Back to Last Post</button>
        			</div>
            </div>
          </form>
        </div>
        <div class="show-note-area">
          <form class='show-note-form'>
            <h2 class="list-topic note-meta">
              <span id='note-meta-cat'><a href="#"></a></span><span> » </span>
              <span id='note-meta-sub-cat'><a href="#"></a></span>
            </h2>
            <div id="update-note-meta">
              <h2 class='list-topic'>Category</h2>
        			<input type="text" name="update-note-cat" id='update-note-cat' value="">
              <h2 class='list-topic'>Sub-Cat</h2>
        			<input type="text" name="update-note-sub-cat" id='update-note-sub-cat' value="">
            </div>
      			<h2 class='list-topic'>Title</h2>
      			<input type="text" name="update-note-title" id='update-note-title' value="">
      			<h3 id='show-note-title'></h3>
      			<h2 class='list-topic'>Content</h2>
      			<textarea name="editor2" id='editor2'></textarea>
      			<div id='show-note-content'></div>
            <div class="note-id"></div>
      			<div id='show-note-button-area'>
      				<button type="button" id="inline-edit">Edit</button>
              <button onclick="updateNote(0);" type="button" id="inline-update">Update</button>
              <button onclick="updateNote(1);" type="button" id="inline-update-quit">Update and Quit</button>
              <button type="button" id="inline-back">Back</button>
              <button type="button" id="inline-cancel">Cancel Update</button>
      			</div>
      		</form>
        </div>
        <div class="search-results">
          <div class="list-block">
            <h2 class='list-topic'>Search Results</h2>
            <div class="list-content"></div>
            <div id='search-results-button-area'>
              <button type="button" id="search-return">Quit Searching</button>
      			</div>
          </div>
        </div>
      </div>
    </div>

  </body>
</html>
