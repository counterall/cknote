$(document).ready(function(){

  // -------##########--------
  // Insert new search columns
  // -------##########--------
  $('button.button').on('click', function(event){
    var new_url = "<tr><td><input type='text' class='keywords' name='keywords[]'></td><td><input class='pages' type='number' name='pages[]' min='1' max='3'><span>(up to 3 pages)</span></td><td style='background: white;'><button class='remove_url' onclick='remove_url(this)'>Remove</button></td></tr>";

    if ($('tr').length <= 10) {
      $('#url_list').append(new_url);
    }
    else{
      alert('You can have no more than 10 lines of searches!');
    }

  });

  // -------##########--------
  // Do the form validation to ensure the availability and uniqueness of input value
  // -------##########--------
  $('input.button').on('click',function(event){

    // remove the border effect before form validation begins
    $("input.keywords, input.pages").css('border', "");
    // check if input field is empty
    var required_warning = false;
    $('td>input').each(function(){
      var required_value = $(this).val();
      // white space will be trimmed
      if (required_value.trim().length === 0) {
        if (!event.isDefaultPrevented()) {
          event.preventDefault();
          required_warning = true;
        }
        $(this).css('border', "solid 2px #FF8080");
      }
    });

    // check if input value is unique
    var inputs = $('td > input.keywords').length;
    var unique_warning = false;

    for (var i = 0; i <= inputs-1 ; i++) {
      var unique_value1 = ($('td > input.keywords').eq(i).val()).trim();
      $('td > input.keywords').eq(i).val(unique_value1);
      if (unique_value1.length !== 0) {
        for (var j = i+1; j <= inputs-1; j++) {
          var unique_value2 = ($('td > input.keywords').eq(j).val()).trim();
          $('td > input.keywords').eq(j).val(unique_value2);
          if (unique_value2.length !== 0) {
            if (unique_value1 === unique_value2) {
              if (!event.isDefaultPrevented()) {
                event.preventDefault();
              }
              $('td > input.keywords').eq(i).css('border', 'solid 2px #82CD9B');
              $('td > input.keywords').eq(j).css('border', 'solid 2px #82CD9B');
              if (!unique_warning) {
                unique_warning = true;
              }
            }
          }
        }
      }
    }

    if (required_warning && unique_warning) {
      alert("You have empty input fields(red color) and some values inserted are not unique(green color)!");
    }
    else if(required_warning && !unique_warning){
      alert("You have empty input fields(red color)!");
    }
    else if (!required_warning && unique_warning) {
      alert("Some values inserted are not unique(green color)!");
    }
  });

});

// -------##########--------
// Remove search columns
// -------##########--------
function remove_url(element){
  $(element).parents('tr').remove();
}
