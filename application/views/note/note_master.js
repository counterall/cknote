//globally define two ckeditors
var editor1, editor2 = null;

$(document).ready(function(){
  // hide all menu items after loading the page
  $('.category ul').hide();
  // collapse or expand the note categories
  $('.category').on('click', function(){
    $(this).find('ul').slideDown(200);
    $(this).siblings().find('ul').slideUp(200);
  });

  //launch ckeditor for content section of new note
  editor1 = CKEDITOR.replace('editor1', {
    height: 300,
    extraAllowedContent: 'code',
    removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,templates',
    extraPlugins: 'widget,codesnippet',
    codeSnippet_theme: 'tomorrow-night-eighties'
  });

  //dynamiclly update the sub-categories when specific category is selected
  $('#old-cats').on('change', function(){
    cat_name = $(':selected', this).text();
    dataArray = {
      cat: cat_name,
      updateSubCats: 'true'
    };
    $.post('misc_ajax.php', dataArray,function(data){
      $('#old-sub-cats').html(data);
    });
  });

  //press create button to create or quit creating new note
  $('#create-note').on('click', function(){
    if ($('.show-note-area').css('display') === 'block') {
      quitViewingNote();
      $('.create-area').slideToggle();
    }else{
      $('.create-area, .list-area').slideToggle();
    }
    html_height = $('.right-side').height();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
    $('#create, #create-quit').show();
    $('#update, #update-quit, #create_more').hide();
  });

  //press cancel button to quit creating new note
  $('#cancel').on('click', function(){
    $('.create-area, .list-area').slideToggle();
    html_height = $('.right-side').height();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
    $('#create, #create-quit').show();
    $('#update, #update-quit, #create_more').hide();
  });

  //dynamically update search results
  $('.search-area input').on('focus', function(){
    $('.search-results').slideDown();
    $('.list-area, .show-note-area').slideUp();
  });
  $('.search-area form').submit(function(e){
    e.preventDefault();
  });
  $('.search-area input').on('keyup change', function(event){
    if ($(this).val()) {
      query = $(this).val();
      dataArray = {
        query: query
      };
      $.post('sphinx.php', dataArray, function(data){
        $('.search-results .list-content').html(data);
      })
    }else{
      $('.search-results .list-content').html('');
    }
  });

  //launch classic ckeditor for content section when view a note
  $('#inline-edit').on('click', function(){
    if (editor2)
      return;

    editor2 = CKEDITOR.replace('editor2', {
      height: 600,
      extraAllowedContent: 'code',
      removePlugins: 'about,find,flash,forms,iframe,image,language,newpage,removeformat,selectall,smiley,specialchar,stylescombo,templates',
      extraPlugins: 'widget,codesnippet',
      codeSnippet_theme: 'tomorrow-night-eighties'
    });

    editor2.setData($('#show-note-content').html());
    $('#update-note-title').val($('#show-note-title').text()).toggle();
    $('#show-note-title, #show-note-content').toggle();
    $('#inline-edit, #inline-update, #inline-update-quit, #inline-cancel, #inline-back').toggle();
  });

  //cancel editing the note
  $('#inline-cancel').on('click', function(){
    if (editor2){
      editor2.destroy();
      editor2 = null;
    }
    $('#show-note-title, #show-note-content, #inline-edit, #inline-back').show();
    $('#update-note-title, #inline-update, #inline-update-quit, #inline-cancel').hide();
  });

  //quit viewing the current note
  $('#inline-back').on('click', function(){
    $('#inline-edit, #inline-update, #inline-update-quit, #inline-cancel, #inline-back').show();
    $('.show-note-area').slideUp();
    if ($(this).parent().siblings('.from-search').eq(0).text()) {
      $('.search-results').slideDown();
    }else{
      $('.list-area').slideDown();
    }
  });

  //quit searching note and return to homepage
  $('#search-return').on('click', function(){
    $('.search-results .list-content').html('');
    $('.search-area input').val('');
    $('.search-results').slideUp();
    $('.list-area').slideDown();
  });

});

//quit viewing the current note
function quitViewingNote(){
  $('#inline-edit, #inline-update, #inline-update-quit, #inline-cancel, #inline-back').show();
  $('.show-note-area').slideUp();
}

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

  content = editor1.getData();
  title = $('#title').val();
  //use ajax to save the content into database without reloading page
  if (update) {
    dataArray = {
      update: 'true',
      content: content
    };
    $.post('create_update.php', dataArray, function(data) {
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
    dataArray = {
      category: category,
      sub_cat: sub_cat,
      title: title,
      content: content
    };
    $.post('create_update.php', dataArray, function(data) {
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

//update edited note after opening a old note from 'read more' or menu list
function updateNote(quit){
  title = $('#update-note-title').val();
  content = editor2.getData();
  id = $('.show-note-form .note-id').text();

  dataArray = {
    title: title,
    content: content,
    id: id,
    edit_update: 'true'
  };

  $.post('create_update.php', dataArray, function(data){
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

  $('#show-note-content').html(content);
  $('#show-note-title').html(title);
  $('#show-note-content pre code').each(function(i, block) {
    hljs.highlightBlock(block);
  });

  if (quit) {
    if (editor2){
      editor2.destroy();
      editor2 = null;
    }
    $('#update-note-title, #show-note-title, #show-note-content').toggle();
    $('#inline-edit, #inline-update, #inline-update-quit, #inline-cancel, #inline-back').toggle();
  }
}

//show note detail when click read more button from search results list
function showNote(event, element, menu, visit){
  event.preventDefault();
  if (menu) {
    id = $(element).parent().next().text();
  }else{
    id = $(element).parent().siblings(".note-id").eq(0).text();
  }
  if (visit) {
    dataArray = {
      id: id,
      visit: true
    };
    $('.from-search').text('from search');
  }else{
    dataArray = {
      id: id,
    };
  }
  $.post('misc_ajax.php', dataArray, function(data){
    note_array = data.split('[separator]');
    $('#note-meta-cat a').text(note_array[0]);
    $('#note-meta-sub-cat a').text(note_array[1]);
    $('#show-note-title').html(note_array[2]).css({'background-color': 'whitesmoke', 'padding': '10px 20px'});
    $('#show-note-content').html(note_array[3]).css({'background-color': 'whitesmoke', 'padding': '10px 20px'});
    $('.show-note-form .note-id').text(id);
    $('#show-note-content ul').css('padding-left','40px');
    $('#show-note-content ol').css('list-style-position','inside');
    $('#show-note-content *').css('margin-bottom','5px');
    $('#show-note-content pre code').each(function(i, block) {
      hljs.highlightBlock(block);
    });
    $('#inline-update, #inline-update-quit, #inline-cancel').hide();
    $('.show-note-area').slideDown();
    $('.list-area, .search-results').slideUp();
  });
}
