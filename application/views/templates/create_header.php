<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Dynamic Input Field</title>
    <?php
    if (isset($js_to_load)) {
    ?>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript" src='<?php echo $asset_url.$js_to_load;?>'></script>
    <?php
    }
    if (isset($css_to_load)) {
    ?>
    <link rel="stylesheet" href="<?php echo $asset_url.$css_to_load;?>" media="screen" title="no title" charset="utf-8">
    <?php
    } ?>
  </head>

  <body>
