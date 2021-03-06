const YOUTUBE = 'youtube.com';
const YOUTUBE_EMBED_PREFIX = '//www.youtube.com/embed/';
const YOUTUBE_EMBED_SUFFIX = '?autoplay=1&iv_load_policy=3';
const DAILYMOTION = 'dailymotion.com';
const DAILYMOTION_EMBED = 'http://www.dailymotion.com/embed/video/';

$('.lienvideo')
		.magnificPopup(
				{
					type : 'iframe',
					iframe : {
						patterns : {
							dailymotion : {
								index : DAILYMOTION,
								id : function(url) {
									var m = url
											.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
									if (m !== null) {
										if (m[4] !== undefined) {
											return m[4];
										}
										return m[2];
									}
									return null;
								},
								src : DAILYMOTION_EMBED + '%id%'
							},
							youtube : {
								index : YOUTUBE,
								id : 'v=',
								src : YOUTUBE_EMBED_PREFIX + '%id%' + YOUTUBE_EMBED_SUFFIX,
							}
						}
					}
				});

$('.lienvideo').tooltip();