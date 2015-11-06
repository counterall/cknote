<h2><?php echo $title ?></h2>
<p><a href="./">Show list of items in database</a></p>

<!-- Display error message if the form validation fails -->
<div style='color: red'><?php echo validation_errors(); ?></div>

<!-- Display the input fields for users -->
<?php echo form_open('google/create') ?>

    <table id="url_list">
      <tr>
        <th>Search Keywords</th>
        <th>Pages of Search Results(10 per page)</th>
      </tr>
      <tr>
        <td><input class='keywords' type="text" name="keywords[]" value="<?php echo set_value('keywords[]')?>"></td>
        <td><input class='pages' type='number' name="pages[]" value="<?php echo set_value('pages[]')?>" min='1' max='3'><span>(up to 3 pages)</span></td>
      </tr>
    </table>

    <button class='button' type="button">Add More Keywords</button>
    <input class='button' type="submit" name="submit" value="Fetch Results and Save to DB">

</form>
