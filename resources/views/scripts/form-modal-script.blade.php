<script type="text/javascript">

	var modal = $('#confirmForm');

	modal.on('show.bs.modal', function (e) {
		var modalClass = $(e.relatedTarget).attr('data-modalClass') || '';
		var submitText = $(e.relatedTarget).attr('data-submit');
		var message = $(e.relatedTarget).attr('data-message');
		var title = $(e.relatedTarget).attr('data-title');
		var form = $(e.relatedTarget).closest('form');
		var self = $(this);

		self.removeClass('modal-*').addClass(modalClass)
		self.find('.modal-body p').text(message);
		self.find('.modal-title').text(title);

		self.find('.modal-footer #confirm')
			.text(submitText)
			.data('form', form);

	});
	modal.find('.modal-footer #confirm').on('click', function(){
	  	$(this).data('form').submit();
	});

</script>