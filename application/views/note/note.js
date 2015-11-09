
$(document).ready(function(){

  // hide all menu items after loading the page
  $('.category ul').hide();
  // collapse or expand the note categories
  $('.category').on('click', function(){
    $(this).find('ul').slideDown(200);
    $(this).siblings().find('ul').slideUp(200);
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


});
