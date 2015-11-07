
$(document).ready(function(){

  // hide all menu items after loading the page
  $('.category ul').hide();
  // collapse or expand the note categories
  $('.category').on('click', function(){
    $(this).find('ul').slideDown(200);
    $(this).siblings().find('ul').slideUp(200);
  });

  //press enter key to search notes
  $('.search-area input').focus();
  $('.search-area input').on('keyup', function(event){
    if (event.keyCode == 13) {
      alert('Searching');
    }
  });

  //press create note button to show create form
  $('#create-note, #cancel').on('click', function(){
    $('.create-area, .list-area').slideToggle();
    html_height = $('.right-side').height();
    $('.left-side').height(html_height);
    $('#new-sub-cat, #new-cat, #title').val('');
    editor1.setData('');
    $('#create, #create-quit').show();
    $('#update, #update-quit, #create_more').hide();
  });

});
