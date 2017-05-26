// NOTIFICATIONS
function notification(msg, type) {
	noty({
		layout: 'topCenter',
		theme: 'relax',
		animation: {
        open: 'animated slideInDown', // Animate.css class names
        close: 'animated slideOutUp', // Animate.css class names
        easing: 'swing', // unavailable - no need
        speed: 500 // unavailable - no need
    },
		timeout: 8000,
		text: msg,
		type: type
	});
}

//COMFIRM BOX
var confirmBox = function(message, actionButton) {
	$('.cd-popup').fadeOut(function(){
		$(this).remove()
	});

	$('body').prepend('<div class="cd-popup" role="alert"><div class="cd-popup-container"><p>'+message+'</p><ul class="cd-buttons"><li>'+actionButton+'</li><li><a onClick="closeConfirmDelete();">Cancel</a></li></ul><a onClick="closeConfirmDelete();" class="cd-popup-close img-replace"></a></div></div>');
	$('.cd-popup').fadeIn().addClass('is-visible');

	$('.cd-popup').click(closeConfirmBox);
	$('.cd-popup-close').click(closeConfirmBox);
	$('#cd-popup-actionButton').click(closeConfirmBox);

}

function closeConfirmBox(){
	$('.cd-popup-container').fadeTo('fast', 0);
	$('.cd-popup').fadeTo('fast', 0, function(){$(this).remove()});

}
var confirmDeleteList = function(id) {
    confirmBox('Are you sure you want to delete this list?','<a href="'+BASEURL+'/list/delete/'+id+'" id="cd-popup-actionButton">Delete</a>');
}
var confirmDeleteComment = function(id) {
    confirmBox('Are you sure you want to delete this comment?','<a href="'+BASEURL+'/comment/delete/'+id+'" id="cd-popup-actionButton">Delete</a>');
}


/* TOPBAR DROPDOWN */
$(document).ready(function(){
	$('header .avatar').click( function(event){
	    event.stopPropagation();
			$('.menu-item.avatar').toggleClass('active', true);
	    $('.topbar_dropdown').fadeTo('fast', 1).toggleClass('fadeOut', false).toggleClass('animated slideInDown', true).css('animation-duration', '0.2s');
	});

	$(document).click(function(e){
		if($(e.target).closest('.topbar_dropdown').length === 0) {
			$('.menu-item.avatar').toggleClass('active', false);
			$('.topbar_dropdown').toggleClass('slideInDown', false).toggleClass('fadeOut', true);
    }
	});
});


function modalBox(name, background_close) {

    $('.modalbox .container').toggleClass('visible', false).css('display', 'none').css('animation-duration', '0.6s');

    $('.modalbox').fadeTo('fast', 1).addClass('visible');
    $('.modalbox .background').css('display', 'block');

    $('.modalbox .'+ name).fadeTo('fast', 1).addClass('visible');

    if(background_close == true) {
        $('.modalbox .background').click(function() {
            closeModalBox(name);
        });
    }
}

function closeModalBox(name) {
    $('.modalbox').fadeTo('fast', 0, function() {
        $(this).removeAttr('class').attr('class', 'modalbox');
        $('.modalbox .background').css('display', 'none');
    });

    $('.modalbox .'+ name).fadeTo('fast', 0, function() {
        $(this).removeAttr('class').attr('class', 'container '+ name);
        $(this).toggleClass('visible', false).css('display', 'none');
    });
}

function markdownModalBox() { modalBox('markdown', true); }

