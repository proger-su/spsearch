jQuery(document).ready(function ($) {

	var spsearch = {
		$container: false,
		init: function () {
			var _this = this;
			$('.sp-ajax-search > input').on('input', function () {
				_this.runSearch($(this));
			});
		},
		runSearch: function (self) {
			var _this = this;
			var word = self.val();
			if (word.length >= 4) {
				$.ajax({
					type: 'GET',
					url: '/wp-admin/admin-ajax.php?action=ajax_search',
					data: self.serialize(),
					timeout: 4000
				}).done(function (result) {
					console.log(_this.$container);
					if (!_this.$container) {
						_this.$container = $('<ul></ul>').appendTo(self.parent());
					}
					if (result) {
						_this.$container.html(result);
						return;
					}
					_this.$container.html('<li>Nothing found</li>');
				}).fail(function (jqXHR, textStatus) {
					if (textStatus === 'timeout')
					{
						alert('Failed');
					}
				});
			} else {
				console.log(_this.$container);
				if (_this.$container) {
					_this.$container.remove();
					_this.$container = false;
				}
			}
		}

	}

	spsearch.init();
});
