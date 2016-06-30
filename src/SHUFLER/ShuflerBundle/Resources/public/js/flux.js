		$('.btn-toggle').on('click',function(){
			$(this).parent().next('.flux-content').slideToggle(700,function(){
				$(this).next('.flux-summary').slideToggle(300);
				if($(this).parent().find('span:first:first-child').hasClass('glyphicon-minus')){
					$(this).parent().find('span:first:first-child').css('transform','rotate(180deg)');
					$(this).parent().find('span:first:first-child').removeClass('glyphicon-minus');
					$(this).parent().find('span:first:first-child').addClass('glyphicon-plus');
				}else if($(this).parent().find('span:first-child').hasClass('glyphicon-plus')){
					$(this).parent().find('span:first:first-child').css('transform','rotate(-180deg)');
					$(this).parent().find('span:first:first-child').removeClass('glyphicon-plus');
					$(this).parent().find('span:first:first-child').addClass('glyphicon-minus');
				}
			});
		});