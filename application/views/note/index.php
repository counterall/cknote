<?php

include_once "functions.php";

connectDB();

$menuHierarchy = getCatAndSubCats();
$recentlyUpdated = getRecentUpdateAndMostSearch();
$mostSearched = getRecentUpdateAndMostSearch(FALSE);

closeDB();
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Private Learning Notes</title>
    <script type="text/javascript" src='../../../ckeditor/jquery.min.js'></script>
    <script src="../../../ckeditor/ckeditor.js"></script>
    <script type="text/javascript" src='note.js'></script>
    <script type="text/javascript" src='add_ckeditor.js'></script>
    <link rel="stylesheet" href="note.css" media="screen" charset="utf-8">
  </head>
  <body>

    <div class="wrapper">
      <div class="left-side">

        <div class="logo-area">
          <div class="logo">
              <a href="#"><img src="../../../assets/img/logo.png" alt="CK Note" /></a>
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
              foreach ($subcat as $subcat => $titles) {
                $menu .= "<li><h4>$subcat</h4><ul>";
                foreach ($titles as $title) {
                  $menu .= "<li>$title</li>";
                }
                $menu .= '</ul></li>';
              }
              $menu .='</ul></div>';
            }
            echo $menu;
          ?>
        </div>
      </div>

      <div class="right-side">
        <div id="popup"></div>
        <div class="search-area">
          <form>
            <input type="text" name="search" id='sphinx' placeholder="Search" value="">
          </form>
        </div>
        <div class="list-area">
          <div class="list-block recent-update">
            <h3 class='list-topic'>Recently Updated</h3>
            <div class='list-content'>
              <?php echo $recentlyUpdated; ?>
            </div>
          </div>
          <div class="list-block most-searched">
            <h3 class='list-topic'>Most Searched</h3>
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
        				<button type="button" id="cancel">Cancel</button>
        			</div>
            </div>
          </form>
        </div>
      </div>
    </div>

  </body>
</html>
