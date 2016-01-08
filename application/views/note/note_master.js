//globally define two ckeditors
var editor1, editor2 = null;

$(document).ready(function(){
  // hide all menu items after loading the page
  $('.menu-sub-cat-block, .create-area, .search-results, .show-note-area, .edit-area, .new_note_icon').hide();
  // collapse or expand the note categories in menu
  $('.first_cat').on('click', function(){
    $(this).siblings().find('ul').hide();
    $(this).siblings().filter('ul').slideToggle(200);
    $(this).parent().siblings().children('ul').slideUp(200);
  });
  $('.menu-sub-cat').on('click', function(){
    $(this).siblings().filter('ul').slideToggle(200);
    $(this).parent().siblings().children('ul').slideUp(200);
  });

  //launch ckeditor for content section of new note
  editor1 = CKEDITOR.replace('editor1', {
    height: 600,
    extraAllowedContent: 'code',
    removePlugins: 'about,find,flash,forms,iframe,language,newpage,removeformat,selectall,smiley,specialchar,templates',
    extraPlugins: 'widget,codesnippet',
    codeSnippet_theme: 'tomorrow-night-eighties',
    stylesSet: 'my_styles:/cknote/application/views/note/ckeditor_style.js'
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
    if ($('.create-area').is(':visible')) {
      $('.create-area').slideUp();
      if ($('#back_to_search').is(':visible')){
        $('.search-results').slideDown();
      }else if (('#back_to_last_post').is(':visible')) {
        $('.show-note-area').slideDown;
      }else{
        $('.list-area').slideUp();
      }
    }else{
      $('#create, #create-quit').show();
      $('#update, #update-quit, #create_more, #back_to_last_post, #back_to_search').hide();
      if ($('.show-note-area').is(':visible')) {
        $('.show-note-area').slideUp();
        $('.create-area').slideDown();
        $('#back_to_last_post').show();
      }else if ($('.search-results').is(':visible')) {
        $('.search-results').slideUp();
        $('.create-area').slideDown();
        $('#back_to_search').show();
      }else{
        $('.create-area').slideDown();
        $('.list-area').slideUp();
      }
      html_height = $('.right-side').height();
      $('.left-side').height(html_height);
      $('#new-sub-cat, #new-cat, #title').val('');
      editor1.setData('');
    }
  });

  //press cancel button to quit creating new note
  $('#cancel').on('click', function(){
    $('.create-area').slideUp();
    refreshFrontpage();
    $('.list-area').slideDown();
    html_height = $('.right-side').height();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
    if ($('.search-results .list-content').html()) {
      $('.search-results .list-content').html('');
      $('.search-area input').val('');
    }
  });

  $('#back_to_search').on('click', function(){
    $('.create-area').slideUp();
    $('.search-results').slideDown();
    html_height = $('.right-side').height();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
  });

  $('#back_to_last_post').on('click', function(){
    $('.create-area').slideUp();
    $('.show-note-area').slideDown();
    html_height = $('.right-side').height();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
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

  //launch classic ckeditor for content section when edit a note
  $('#inline-edit').on('click', function(){
    if (editor2){
      return;
    }
    //create creator instance and initiate it with value
    editor2 = CKEDITOR.replace('editor2', {
      height: 600,
      extraAllowedContent: 'code',
      removePlugins: 'about,find,flash,forms,iframe,language,newpage,removeformat,selectall,smiley,specialchar,templates',
      extraPlugins: 'widget,codesnippet',
      codeSnippet_theme: 'tomorrow-night-eighties',
      stylesSet: 'my_styles:/cknote/application/views/note/ckeditor_style.js'
    });
    editor2.setData($('#show-note-content').html());
    //replicate the note id to the edit form
    $('.edit-note-form .note-id').text($('.show-note-form .note-id').text());
    //prepare meta data of a note to be edited
    $('#update-note-cat').val($('#note-meta-cat').text());
    $('#update-note-sub-cat').val($('#note-meta-sub-cat').text());
    $('#update-note-meta').show();
    $('#update-note-title').val($('#show-note-title').text()).show();
    //hide 'show' page
    $('.show-note-area').hide();
    //show edit page
    $('.edit-area').show();
  });

  //cancel editing the note
  $('#inline-cancel-update').on('click', function(){
    //recover the note content from edit form
    category = $('#update-note-cat').val();
    sub_cat = $('#update-note-sub-cat').val();
    title = $('#update-note-title').val();
    content = editor2.getData();
    $('#show-note-content').html(content);
    $('#show-note-title').text(title);
    $('#note-meta-cat').text(category);
    $('#note-meta-sub-cat').text(sub_cat);
    // in case new note is opened during editing, we need to recover the note id
    $('.show-note-form .note-id').text($('.edit-note-form .note-id').text());
    $('#show-note-content ul').css('padding-left','40px');
    $('#show-note-content ol').css('list-style-position','inside');
    $('#show-note-content *').css('margin-bottom','5px');
    $('#show-note-content pre code').each(function(i, block) {
      hljs.highlightBlock(block);
    });

    //destroy the instance of ckeditor which used to edit main content of a post
    if (editor2){
      editor2.destroy();
      editor2 = null;
    }
    //show note page
    //show and hide related buttons
    if ($('.search-results .list-content').html()) {
      $('#inline-back-search').show();
    }else{
      $('#inline-back-search').hide();
    }
    $('#inline-back-edit').hide();
    $('#inline-edit, #inline-back-home').show();
    $('.show-note-area').show();
    //hide editing area
    $('.edit-area').hide();
  });

  //quit viewing the current note and back to search list
  $('#inline-back-search').on('click', function(){
    $('.show-note-area').slideUp();
    if ($('.search-results .list-content').html()) {
      $('.search-results').slideDown();
    }else{
      refreshFrontpage();
      $('.list-area').slideDown();
    }
  });
  //quit viewing the current note and back to homepage
  $('#inline-back-home').on('click', function(){
    $('.search-results .list-content').html('');
    $('.search-area input').val('');
    $('.show-note-area').slideUp();
    refreshFrontpage();
    $('.list-area').slideDown();
  });
  //quit viewing the current note and back to edit page
  $('#inline-back-edit').on('click', function(){
    $('.edit-area').slideDown();
    $('.show-note-area').slideUp();
  });
  //quit viewing the current note and back to create note
  $('#inline-back-create').on('click', function(){
    $('.create-area').slideDown();
    $('.show-note-area').slideUp();
  });

  //quit searching note and return to homepage
  $('#search-return').on('click', function(){
    $('.search-results .list-content').html('');
    $('.search-area input').val('');
    $('.search-results').slideUp();
    refreshFrontpage();
    $('.list-area').slideDown();
  });

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

  content = editor1.getData();
  title = $('#title').val();
  //use ajax to save the content into database without reloading page
  if (update) {
    dataArray = {
      update: 'true',
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
  category = $('#update-note-cat').val();
  sub_cat = $('#update-note-sub-cat').val();
  title = $('#update-note-title').val();
  content = editor2.getData();
  id = $('.edit-note-form .note-id').text();

  dataArray = {
    category: category,
    sub_cat: sub_cat,
    title: title,
    content: content,
    id: id,
    update: 'true'
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
  $('#show-note-title').text(title);
  $('#note-meta-cat').text(category);
  $('#note-meta-sub-cat').text(sub_cat);
  // in case new note is opened during editing, we need to recover the note id
  $('.show-note-form .note-id').text($('.edit-note-form .note-id').text());
  $('#show-note-content ul').css('padding-left','40px');
  $('#show-note-content ol').css('list-style-position','inside');
  $('#show-note-content *').css('margin-bottom','5px');
  $('#show-note-content pre code').each(function(i, block) {
    hljs.highlightBlock(block);
  });

  if (quit) {
    if (editor2){
      editor2.destroy();
      editor2 = null;
    }
    if ($('.search-results .list-content').html()) {
      $('#inline-back-search').show();
    }else{
      $('#inline-back-search').hide();
    }
    $('.edit-area').hide();
    $('#inline-back-edit').hide();
    $('#inline-edit, #inline-back-home').show();
    $('.show-note-area').show();
  }
}

//show note detail when click read more button or menu items
function showNote(event, element, menu, visit){
  event.preventDefault();
  $('#show-note-button-area > button').hide();

  if ($('.edit-area').is(':visible')) {
    $('.edit-area').slideUp();
    $('#inline-back-edit').show();
  }else if ($('.create-area').is(':visible')) {
    $('.create-area').slideUp();
    $('#inline-back-create').show();
  }else if ($('.search-results .list-content').html()) {
      $('.search-results').slideUp();
      $('#inline-back-search, #inline-edit, #inline-back-home').show();
  }else{
    $('.list-area').slideUp();
    $('#inline-edit, #inline-back-home').show();
  }

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
  });

  $('.show-note-area').slideDown();
}

function refreshFrontpage(){
  var dataArray = {
    recentlyUpdated: 'refresh',
  };
  $.post('misc_ajax.php', dataArray, function(data) {
    var returnArray = data.split('ckseparator');
    $('#recentlyUpdated').html(returnArray[0]);
    $('#mostSearched').html(returnArray[1]);
  });
}
