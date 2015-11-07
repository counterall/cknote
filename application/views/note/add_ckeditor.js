$(document).ready(function(){

  $('#old-cats').on('change', function(){
    cat_name = $(':selected', this).text();
    $.get('misc_ajax.php?cat=' + cat_name + '&updateSubCats=true', function(data){
      $('#old-sub-cats').html(data);
    });
  });


  editor1 = CKEDITOR.replace('editor1', {
    height: 300,
    extraAllowedContent: 'code',
    removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,templates',
    extraPlugins: 'widget,codesnippet',
  });

  // editor2 = CKEDITOR.replace('editor2', {
  //   height: 100,
  //   removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,templates',
  // });

});


function createNote(quit, update){
  new_cat = $('#new-cat').val();
  new_sub_cat = $('#new-sub-cat').val();

  if (new_cat.length > 0) {
    category = new_cat;
  }else{
    category = $('#old-cats :selected').text();
  }

  if (new_sub_cat.length > 0) {
    sub_cat = new_sub_cat;
  }else{
    sub_cat = $('#old-sub-cats :selected').text();
  }

  //encode the content to avoid errors caused by some plain character, e.g. # ahead of color attribute
  content = encodeURIComponent(editor1.getData());
  // title = encodeURIComponent(editor2.getData());
  title = $('#title').val();
  //use ajax to save the content into database without reloading page
  if (update) {
    $.get('create.php?update=' + update + '&content=' + content, function(data) {
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
  }else{
    $.get('create.php?category=' + category + '&sub_cat=' + sub_cat + '&title=' + title + '&content=' + content, function(data) {
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
  }

  if (quit) {
    $('.create-area, .list-area').slideToggle();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
  }else{
    $('#create, #create-quit').hide();
    $('#create_more, #update, #update-quit').show();
  }

}

function createMore(){
  $('#new-sub-cat, #new-cat, #title').val('');
  editor1.setData('');
  $('#create, #create-quit').show();
  $('#create_more, #update, #update-quit').hide();
}
