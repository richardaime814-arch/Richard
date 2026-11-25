/* KRAAK — champs galerie dans l'administration. */
(function ($) {
	'use strict';

	function sync($list) {
		var ids = $list.children('li').map(function () { return $(this).data('id'); }).get();
		$list.prevAll('.kraak-gallery-input').first().val(ids.join(','));
	}

	$('.kraak-gallery-preview').sortable({ update: function () { sync($(this)); } });

	$(document).on('click', '.kraak-gallery-pick', function (e) {
		e.preventDefault();
		var target = $(this).data('target');
		var $input = $('#' + target);
		var $list = $input.nextAll('.kraak-gallery-preview').first();
		var frame = wp.media({
			title: $(this).text(),
			multiple: 'add',
			library: { type: 'image' },
			button: { text: 'OK' }
		});
		frame.on('open', function () {
			var sel = frame.state().get('selection');
			($input.val() || '').split(',').filter(Boolean).forEach(function (id) {
				var a = wp.media.attachment(id); a.fetch(); sel.add(a);
			});
		});
		frame.on('select', function () {
			$list.empty();
			frame.state().get('selection').each(function (a) {
				var s = a.get('sizes');
				var url = s && s.thumbnail ? s.thumbnail.url : a.get('url');
				$list.append($('<li>').attr('data-id', a.id).append($('<img>').attr('src', url)));
			});
			sync($list);
		});
		frame.open();
	});

	$(document).on('click', '.kraak-gallery-clear', function (e) {
		e.preventDefault();
		var $input = $('#' + $(this).data('target'));
		$input.val('');
		$input.nextAll('.kraak-gallery-preview').first().empty();
	});
})(jQuery);
