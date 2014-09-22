// init toastr
toastr.options = {
	positionClass: 'toast-bottom-right',
	showMethod: 'slideDown',
	closeButton: true
};

$(document).ajaxError(function (event, xhr, ajaxOptions, thrownError) {
	toastr.error(xhr.responseText, 'Error!');
});

$(document).on('shown.bs.modal', '.modal', function() {
	$(this).find('.modal-body').find(':input:enabled:visible:first').focus().select();
});