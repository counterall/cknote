<?php

include_once "functions.php";

connectDB('information_schema');

$catsAndTabs = getCatAndTab();

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
            foreach ($catsAndTabs as $key1 => $value1) {
              $menu .= '<div class="category">';
              $menu .= '<h2>'.$key1.'</h2><ul>';
              foreach ($value1 as $key2 => $value2) {
                $menu .= '<li>'.$value2.'</li>';
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
            <input type="text" name="search" placeholder="Search" value="">
          </form>
        </div>
        <div class="list-area">
          <div class="list-block recent-update">
            <h3 class='list-topic'>Recently Updated</h3>
            <p class='list-content'>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vel ullamcorper augue. Ut sit amet nunc ut odio iaculis aliquet nec vitae odio. In ut tincidunt felis. Etiam tincidunt turpis non est gravida aliquet non non eros. Suspendisse semper euismod justo vel rutrum. Nullam massa nunc, dignissim nec nisl ac, malesuada tincidunt nulla. Vestibulum tempus gravida finibus. Nunc ornare ac urna sed viverra. Integer enim eros, condimentum eu ante vel, faucibus suscipit diam. Integer vulputate nisi ut lacus pellentesque, non posuere quam commodo. Aenean cursus augue eget arcu cursus sodales.
            </p>
          </div>
          <div class="list-block most-searched">
            <h3 class='list-topic'>Most Searched</h3>
            <p class='list-content'>
              Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vel ullamcorper augue. Ut sit amet nunc ut odio iaculis aliquet nec vitae odio. In ut tincidunt felis. Etiam tincidunt turpis non est gravida aliquet non non eros. Suspendisse semper euismod justo vel rutrum. Nullam massa nunc, dignissim nec nisl ac, malesuada tincidunt nulla. Vestibulum tempus gravida finibus. Nunc ornare ac urna sed viverra. Integer enim eros, condimentum eu ante vel, faucibus suscipit diam. Integer vulputate nisi ut lacus pellentesque, non posuere quam commodo. Aenean cursus augue eget arcu cursus sodales.
            </p>
          </div>
        </div>
        <div class="create-area">
          <form class='create-form'>
            <div class="cat-area">
              <h2 class='list-topic'>To Where</h2>
              <p class='cat-option'>
                <span>Choose Category:</span>
                <select id="old-cats" name="old-cats">
                  <option value="git">Git</option>
                  <option value="sql">SQL</option>
                  <option value="php">PHP</option>
                </select> Or
                <input type="text" id='new-cat' name="new-cat" placeholder="Create New Category">
              </p>
              <p class='table-option'>
                <span>Choose Table:</span>
                <select id="old-tabs" name="old-tabs">
                  <option value="test">Test</option>
                  <option value="sql">SQL</option>
                  <option value="php">PHP</option>
                </select> Or
                <input type="text" id='new-tab' name="new-tab" placeholder="Create New Table">
              </p>
            </div>
            <div class="note-area">
              <h2 class='list-topic'>Title</h2>
        			<textarea name='editor2' rows='2' id='editor2'></textarea>
        			<h2 class='list-topic'>Content</h2>
        			<textarea name="editor1" id='editor1'></textarea>
        			<div class='button-area'>
        				<button onclick="createNote(0,0)" type="button" id="create">Create</button>
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
