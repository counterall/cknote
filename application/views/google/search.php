<h2 style="float: left; margin-top: 0; color: #0066cc"><?php echo $title.':'; ?></h2>

<!-- Display the Sphinx index field -->
<form style='float: right; margin-top: 5px;' action='?' method="GET">
	<input style="padding: 3px;" type="text" name='query' value="<?php echo $query; ?>">
	<input style="color: white; background: #0066cc; width: 100px; border-radius: 2px;" type="submit" value="Search">
</form>

<p style="clear: left"><a href="./">Back to list of Google Search results</a></p>

<!-- Display the query results -->
<?php if (isset($content_result)): ?>
	<ol>
		<?php foreach ($content_result->result_array() as $content_item): ?>
		<li>
        <h3><a style="color: #0066cc; text-decoration: none;" href="<?php echo $content_item['link'];?>"><?php echo highlight($content_item['title'], $query); ?></a></h3>
        <div class="main">
                <?php echo highlight($content_item['text'], $query); ?>
        </div>
		</li>
		<?php endforeach ?>
	</ol>

	<!-- Display the meta info of Sphinx index -->
	<pre><?php echo $search_meta; die(); ?></pre>
<?php endif ?>

<!-- Display error message if any error occurs when doing the query -->
<p><?php echo $search_error; ?></p>

<!-- Define the function used to highlight keywords showed in the Sphinx search result -->
<?php
function highlight($text, $words) {
// $highlighted = str_ireplace(' '.$words.' ', "<span style='background: red; color: white;'> $words </span>", $text);
$highlighted = preg_filter('/'.$words.'/iu', "<span style='background: #FF4747; color: white;'>$0</span>", $text);
if (!empty($highlighted)) {
		$text = $highlighted;
}
return $text;
}
?>
