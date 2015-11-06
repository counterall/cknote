<?php
include_once "./functions.php";

$sql = "SELECT title, content FROM ckeditor WHERE id = 1";
connectDB();
$result = querySql($sql, true);
closeDB();
$result = $result->fetch_array(MYSQLI_ASSOC);
$title = $result['title'];
$content = $result["content"];

?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="robots" content="noindex, nofollow">
	<title>Creating and destroying CKEditor on the fly</title>
	<script src="../ckeditor/ckeditor.js"></script>
	<script src="../ckeditor/jquery.min.js"></script>
	<script src="../ckeditor/plugins/codesnippet/lib/highlight/highlight.pack.js"></script>
	<!-- initiate all <pre><code></code></pre> tags for further highlighting -->
	<script>hljs.initHighlightingOnLoad();</script>
	<!-- for inline ckeditor, the syntax highlight style needs to be manually added here -->
	<link href="../ckeditor/plugins/codesnippet/lib/highlight/styles/tomorrow-night-eighties.css" rel="stylesheet">
	<link rel="stylesheet" href="./master.css" media="screen" charset="utf-8">
</head>

<body>
	<div id='wrap'>

		<form name='note' style='width: 80%; margin-left: auto; margin-right:auto' method='get'>

			<h2 style='color: #379ddf'>Title</h2>
			<textarea cols='50' rows='1' style='display: none' name="editor2" id='editor2'></textarea>
			<div id='editor2_content'><?php echo $title;?></div>
			<h2 style='color: #379ddf'>Content</h2>
			<textarea style='width: 100%; display: none' name="editor1" id='editor1'></textarea>
			<div id='editor1_content'><?php echo $content;?></div>
			<p>
				<input onclick="createEditor();" type="button" value="Edit" id="edit">
				<input onclick="saveContent(update=false);" type="button" value="Save" id="save" style="display:none">
				<input onclick="saveAndQuit(update=false);" type="button" value="Save and Quit" id="save_quit" style="display:none">
				<input onclick="saveContent(update=true);" type="button" value="Update" id="update" style="display:none">
				<input onclick="saveAndQuit(update=true);" type="button" value="Update and Quit" id="update_quit" style="display:none">
				<input onclick="cancelEdit();" type="button" value="Cancel" id="cancel" style="display:none">
			</p>
		</form>

	</div>

	<script src='./add_ckeditor.js'></script>

</body>

</html>
