(function($) {
	$(document).ready(function() {
        bindQuizButtons();
    });	
    
    function bindQuizButtons() {
        $('.answers').find('.button').each(function() {
            $(this).click(function(e) {
                e.preventDefault();
                selectButton($(this)); 
            });
        })
    }

    function selectButton(target){
        var selected = target;
        var siblings = target.siblings();
        if(!selected.hasClass('selected')){
            selected.addClass('selected');
        }
        $(siblings).each(function(){
            $(this).removeClass('selected');
        })
    }
})( jQuery );
