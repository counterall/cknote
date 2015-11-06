$(document).ready(function(){

  editor1 = CKEDITOR.replace('editor1', {
    height: 300,
    extraAllowedContent: 'code',
    removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,templates',
    extraPlugins: 'widget,codesnippet',
  });

  editor2 = CKEDITOR.replace('editor2', {
    height: 100,
    removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,templates',
  });

});


function createNote(quit, update){
  new_cat = $('#new-cat').val();
  new_tab = $('#new-tab').val();

  if (new_cat.length > 0) {
    category = new_cat;
  }else{
    category = $('#old-cats :selected').text();
  }

  if (new_tab.length > 0) {
    table = new_tab;
  }else{
    table = $('#old-tabs :selected').text();
  }

  //encode the content to avoid errors caused by some plain character, e.g. # ahead of color attribute
  content = encodeURIComponent(editor1.getData());
  title = encodeURIComponent(editor2.getData());

  //use ajax to save the content into database without reloading page
  $.get('create.php?update=' + update + '&category=' + category + '&table=' + table + '&title=' + title + '&content=' + content, function(data) {
    if (data) {
      alert(data);
    } else {
      if (update) {
        $('#popup').text('Note Updated!').slideDown().delay(2000).slideUp();
      }else{
        $('#popup').text('Note Created!').slideDown().delay(2000).slideUp();
      }
    }
  });

  if (quit) {
    $('.create-area, .list-area').slideToggle();
    $('.left-side').height(html_height);
    $('#new-tab, #new-cat').val('');
    editor1.setData('');
    editor2.setData('');
  }else{
    $('#create, #create-quit').hide();
    $('#update, #update-quit').show();
  }

}
