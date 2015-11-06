CKEDITOR.disableAutoInline = true;

$('#editor1').html($('#editor1_content').html());
$('#editor2').html($('#editor2_content').html());

var editor1, editor2, editor1_html1, editor1_html2, editor2_html1, editor2_html2 = '';

function createEditor() {
  if (editor1 || editor2)
    return;

  $('#edit, #editor1_content, #editor2_content').css('display', 'none');

  editor1 = CKEDITOR.inline('editor1', {
    extraAllowedContent: 'code',
    removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,stylescombo,templates',
    extraPlugins: 'widget,codesnippet',
  });

  editor2 = CKEDITOR.inline('editor2', {
    removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,stylescombo,templates',
  });

  editor1_html1 = editor1.getData();
  editor2_html1 = editor2.getData();

  // Update button states.
  if (!editor1_html1 && !editor2_html1) {
    $('#save, #save_quit, #cancel').css('display', '');
  } else {
    $('#update, #update_quit, #cancel').css('display', '');
  }
  hljs.initHighlightingOnLoad();
}

function saveContent(update) {
  if (!editor1 || !editor2)
    return;

  title = editor2.getData();
  content = editor1.getData();

  if (title == editor2_html1 && content == editor1_html1) {
    alert('Please make some changes before saving or updating');
  } else {
    editor2_html1 = title;
    editor1_html1 = content;
    $('#editor1_content').html(content);
    $('#editor2_content').html(title);
    //encode the content to avoid errors caused by some plain character, e.g. # ahead of color attribute
    content = encodeURIComponent(content);
    title = encodeURIComponent(title);
    //use ajax to save the content into database without reloading page
    if (update) {
      $.get('save.php?title=' + title + '&content=' + content + '&id=1', function(data) {
        if (data) {
          alert(data);
        } else {
          alert('Post has been updated successfully');
        }
      });
    } else {
      $.get('save.php?title=' + title + '&content=' + content, function(data) {
        if (data) {
          alert(data);
        } else {
          alert('Post has been created successfully');
        }
      });
    }
  }
}

function saveAndQuit(update) {
  if (!editor1 || !editor2)
    return;

  title = editor2.getData();
  content = editor1.getData();
  if (title == editor2_html1 && content == editor1_html1) {
    alert('Please make some changes before saving or updating');
  } else {
    $('#editor1_content').html(content);
    $('#editor2_content').html(title);
    $('#editor1_content, #editor2_content, #edit').css('display', '');
    $('#save, #save_quit, #update, #update_quit, #cancel').css('display', 'none');

    content = encodeURIComponent(content);
    title = encodeURIComponent(title);
    //use ajax to save the content into database without reloading page
    if (update) {
      $.get('save.php?title=' + title + '&content=' + content + '&id=1', function(data) {
        if (data) {
          alert(data);
        } else {
          alert('Post has been updated successfully');
        }
      });
    } else {
      $.get('save.php?title=' + title + '&content=' + content, function(data) {
        if (data) {
          alert(data);
        } else {
          alert('Post has been created successfully');
        }
      });
    }
    // Destroy the editors.
    editor1.destroy();
    editor1 = null;
    editor2.destroy();
    editor2 = null;

    $('#editor1, #editor2').css('display', 'none');
  }

}

function cancelEdit() {
  if (!editor1 || !editor2)
    return;

  editor1.setData(editor1_html1);
  editor2.setData(editor2_html1);

  $('#editor1_content, #editor2_content, #edit').css('display', '');
  $('#save, #save_quit, #update, #update_quit, #cancel').css('display', 'none');

  // Destroy the editor.
  editor1.destroy();
  editor1 = null;
  editor2.destroy();
  editor2 = null;

  $('#editor1, #editor2').css('display', 'none');
}
