const LOADER = '<img src="http://www.owlhatworld.com/wp-content/uploads/2015/12/38.gif" width="100%" />';
const SPAN_1ST_1ST_CHILD = 'span:first:first-child';
const SPAN_1ST_CHILD = 'span:first-child';
const ICON_MINUS = 'glyphicon-minus';
const ICON_PLUS = 'glyphicon-plus';

$('.btn-toggle').on(
		'click',
		function() {
			$(this).parent().next('.flux-content').slideToggle(
					700,
					function() {
						$(this).next('.flux-summary').slideToggle(300);
						if ($(this).parent().find(SPAN_1ST_1ST_CHILD)
								.hasClass(ICON_MINUS)) {
							$(this).parent().find(SPAN_1ST_1ST_CHILD)
									.css('transform', 'rotate(180deg)');
							$(this).parent().find(SPAN_1ST_1ST_CHILD)
									.removeClass(ICON_MINUS);
							$(this).parent().find(SPAN_1ST_1ST_CHILD)
									.addClass(ICON_PLUS);
						} else if ($(this).parent().find(SPAN_1ST_CHILD)
								.hasClass(ICON_PLUS)) {
							$(this).parent().find(SPAN_1ST_1ST_CHILD)
									.css('transform', 'rotate(-180deg)');
							$(this).parent().find(SPAN_1ST_1ST_CHILD)
									.removeClass(ICON_PLUS);
							$(this).parent().find(SPAN_1ST_1ST_CHILD)
									.addClass(ICON_MINUS);
						}
					});
		});

function newPage() {
	var nbPages = $(this).data('nbpages');
	var page = $(this).data('page');

	var pod = $(this).data('id');
	$('#podSum' + pod).html(LOADER);
	$('#podCont' + pod).html(LOADER);

	getData(pod, page);

	$('#fluxPag' + pod + ' .noPage').html(page);

	if (page >= nbPages) {
		$('#fluxPag' + pod + ' a.next').css('visibility', 'hidden');
	} else {
		$('#fluxPag' + pod + ' a.next').css('visibility', 'visible');
	}
	if (page > 1) {
		$('#fluxPag' + pod + ' a.prev').css('visibility', 'visible');
	} else {
		$('#fluxPag' + pod + ' a.prev').css('visibility', 'hidden');
	}

	$('#fluxPag' + pod + ' a.next').data('page', page + 1);
	$('#fluxPag' + pod + ' a.prev').data('page', page - 1);
}