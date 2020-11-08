$(function(){
    // Check if Confirm Pass Value Equal to Pass Value
    $('#conf-pass').on('blur', function() {
        if ($(this).val() !== $('#pass-check').val()){
            $(this).css('border','1px solid #F00');
        } else {
            $(this).css('border','1px solid #ccc');
        }
    });
    // Submit Form By ENTER Key
    $('.enter-send').keypress(function(e){
      if(e.which == 13 && !event.shiftKey){
           // submit via ajax or
           $('.send-btn').trigger('click');
       }
    });
    // Hide Placeholder In Focus
	$('[placeholder]').focus(function(){
		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');
	}).blur(function(){
		$(this).attr('placeholder', $(this).data('text'));
	});
  // Auto scroll to bottom when open chat page
  function scrollToBottom(){
    $(document).load(function() {
      $(document).scrollTop($(document).height());
    })
  }
  // Hide and show hidden options
  function slideToggler(selector) {
    $(selector).click(function(){
      if(!$($(this).data('target')).hasClass('opened')){
        $($(this).data('target')).slideToggle(300).addClass('opened');
      } else {
        $($(this).data('target')).slideToggle(300).removeClass('opened');
      }
    });
  }
  slideToggler(".options-icon");
  slideToggler(".search-icon");
});
