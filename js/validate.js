function validateForm(){
  var blankCount=0;
  $("input").each(function(){
    if($(this).val()=="")
      blankCount++;
  });
  $("select").each(function() {
    if($(this).val()=="")
      blankCount++;
  });
  $("textarea").each(function() {
    if($(this).val()=="")
      blankCount++;
  });
  if(blankCount > 0){
    alert("You must fill out all form fields.");
    return false;
  }
  return true;
}