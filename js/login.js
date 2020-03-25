/*
 *	Resize Image
*/
function resizeImage(id) {
	jQuery('#' + id).css({
		'position': 'absolute',
		'top': '0px',
		'left': '0px',
		'width': '100%',
		'height': '100%',
		'z-index': -1,
		'overflow': 'hidden'
	});
	var w = jQuery(window).width(),
		h = jQuery(window).height(),
		o = jQuery('#' + id).children('img'),
		iW = o.width(),
		iH = o.height();
	o.css({
		'display': 'block',
		'opacity': 0
	});
	if (w > h) {
		if (iW > iH) {
			o.css({
				'width': w
			});
			o.css({
				'height': Math.round((iH / iW) * w)
			});
			var newIh = Math.round((iH / iW) * w);
			if (newIh < h) {
				o.css({
					'height': h
				});
				o.css({
					'width': Math.round((iW / iH) * h)
				})
			}
		} else {
			o.css({
				'height': h
			});
			o.css({
				'width': Math.round((iW / iH) * h)
			})
		}
	} else {
		o.css({
			'height': h
		});
		o.css({
			'width': Math.round((iW / iH) * h)
		})
	}
	var newIW = o.width(),
		newIH = o.height();
	if (newIW > w) {
		var l = (newIW - w) / 2;
		o.css('margin-left', -l)
	} else {
		o.css('margin-left', 0)
	}
	if (newIH > h) {
		var t = (newIH - h) / 2;
		o.css('margin-top', -t)
	} else {
		o.css('margin-top', 0)
	}
	o.css({
		'opacity': '1'
	})
}