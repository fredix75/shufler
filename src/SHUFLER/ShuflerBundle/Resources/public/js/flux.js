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
		
		function newPage() {
			   var nbPages=$(this).data('nbpages');
			   var page=$(this).data('page');
			   
			   var pod=$(this).data('id');
			   var imgLoad='<img src="http://www.owlhatworld.com/wp-content/uploads/2015/12/38.gif" width="100%" />';
			   $('#podSum'+pod).html(imgLoad);
			   $('#podCont'+pod).html(imgLoad);

			   getData(pod, page);
		   
				$('#fluxPag'+pod+' .noPage').html(page);
				
				if(page>=nbPages){
					$('#fluxPag'+pod+' a.next').css('visibility','hidden');
				}else{
					$('#fluxPag'+pod+' a.next').css('visibility','visible');
				}
				if(page>1){
					$('#fluxPag'+pod+' a.prev').css('visibility','visible');
				}else{
					$('#fluxPag'+pod+' a.prev').css('visibility','hidden');
				}

				 $('#fluxPag'+pod+' a.next').data('page',page+1);
				 $('#fluxPag'+pod+' a.prev').data('page',page-1);
		}