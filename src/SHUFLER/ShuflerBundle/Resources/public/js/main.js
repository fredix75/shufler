		$('.lienvideo').magnificPopup({
		  type: 'iframe',
		  iframe: {
		    patterns: {
		      dailymotion: {
		        index: 'dailymotion.com',
		        id: function(url) {        
		            var m = url.match(/^.+dailymotion.com\/(video|hub)\/([^_]+)[^#]*(#video=([^_&]+))?/);
		            if (m !== null) {
		                if(m[4] !== undefined) {
		                    return m[4];
		                }
		                return m[2];
		            }
		            return null;
		        },
		        src: 'http://www.dailymotion.com/embed/video/%id%'		        
		      },
		     youtube: {
			      index: 'youtube.com/', // String that detects type of video (in this case YouTube). Simply via url.indexOf(index).
			      id: 'v=', // String that splits URL in a two parts, second part should be %id%
			      // Or null - full URL will be returned
			      // Or a function that should return %id%, for example:
			      // id: function(url) { return 'parsed id'; } 	
			      src: '//www.youtube.com/embed/%id%?autoplay=1&iv_load_policy=3' // URL that will be set as a source for iframe. 
			  }
		    }
		  }
		});

		$('.lienvideo').tooltip();