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
var confirmBox = function(message, url) {
	$('.modal').fadeOut(function(){
		$(this).remove()
	});

    $('body').prepend('' +
        '<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle" aria-hidden="false">' +
            '<div class="modal-dialog" role="document">' +
                '<div class="modal-content">' +
                    '<div class="modal-header">' +
                        '<h5 class="modal-title" id="deleteModalTitle">Delete</h5>' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                    '</div>' +
                    '<div class="modal-body">' +
                        '<p>'+ message +'</p>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                        '<a href="#" class="btn btn-secondary" data-dismiss="modal">Close</a>' +
                        '<a href="'+ url +'" class="btn btn-danger">Delete</a>' +
                    '</div>' +
                '</div>' +
            '</div>' +
        '</div>');

    $('#deleteModal').modal('show')
}

var confirmDeleteList = function(id) {
    confirmBox('Are you sure you want to delete this list?',''+BASEURL+'/list/delete/'+id+'');
}
var confirmDeleteComment = function(id) {
    confirmBox('Are you sure you want to delete this comment?',''+BASEURL+'/comment/delete/'+id+'');
}


/* ADD RESPONSIVE CLASSES IF CONTENT CONTAINS iframe, embed, OR object */
$(document).ready(function(){
    $('.card.list .card-block:has(iframe)', function() {
        $('.card.list .card-block iframe').addClass('embed-responsive-item').wrap('<div class="embed-responsive embed-responsive-4by3"></div>');
    });
    $('.card.list .card-block:has(object)', function() {
        $('.card.list .card-block object').addClass('embed-responsive-item').wrap('<div class="embed-responsive embed-responsive-4by3"></div>');
    });
    $('.card.list .card-block:has(embed)', function() {
        $('.card.list .card-block embed').addClass('embed-responsive-item').wrap('<div class="embed-responsive embed-responsive-4by3"></div>');
    });
});