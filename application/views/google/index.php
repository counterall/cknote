<h2 style="float: left; margin-top: 0; color: #0066cc"><?php echo $title.':'; ?></h2>

<!-- Display the Sphinx index field -->
<form style='float: right; margin-top: 5px;' action='?' method="GET">
	<input style="" type="text" name='query' value="<?php echo $query; ?>">
	<input style="color: white; background: #0066cc; width: 100px; border-radius: 2px;" type="submit" value="Search">
</form>

<!-- display links to "./google/create" and "./google/truncate" based on if the database is empty -->
<?php if ($empty_set !== NULL) {?>
<p style='clear: both; color: red'><?php echo $empty_set; ?></p>
<p><a href="./create">Fetch new Google search results</a></p>
<?php }
else{ ?>
<p style='clear: both'><span><a href="./create">Fetch new Google search results</a></span><span style='margin-left: 20px;'><a href="./truncate">Empty the database</a></span></p>
<?php }

if (!empty($fetch_results)) {
?>
<!-- display all the items avaialable in the database -->
	<ol>
	<?php foreach ($fetch_results as $results_item): ?>
			<li>
					<h3><a style="color: #0066cc; text-decoration: none;" href="<?php echo $results_item['link']; ?>"><?php echo $results_item['title']; ?></a></h3>
					<div class="main">
									<?php echo $results_item['text'];?>
					</div>
			</li>
	<?php endforeach ?>
	</ol>
<?php
}
?>
