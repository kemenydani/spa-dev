function initPhotos(container)
{
	var images = [];
	var lastTemplate = "";
	var step = 0;
	
	$.each( container.find('i'), function() { images.push( $(this).data('src') ); });

	var beautify = function()
	{
	
	};
	
	var getDimensions = function( url )
	{
		
		var def = $.Deferred();
		var img = new Image();
		$(img).on("load error",function()
		{
			var formFactor = this.height > this.width ? 'p' : 'l';
			def.resolve(formFactor);
		});
		img.src = url;
		
		return def;
	};
	
	var determineSizes = function( urls, cb )
	{
		var Promises = [];
		
		for( var u = 0; u < urls.length; u++) Promises.push( getDimensions(urls[u]) );
		
		$.when.apply(null, Promises ).then(function( s1, s2, s3 )
		{
			var values = [];
			
			if( typeof s1 !== 'undefined' ) values.push(s1);
			if( typeof s2 !== 'undefined' ) values.push(s2);
			if( typeof s3 !== 'undefined' ) values.push(s3);
			
			cb(values)
		});
	};
	
	var getTemplate = function( template, urls, pattern )
	{
		switch( template )
		{
			case 'l'   :
			case 'll'  :
			case 'lll' :
			case 'p'   :
			case 'pp'  :
			case 'ppp' :
				
				var $tr = $('<tr>');
				
				for( var i = 0; i < template.length; i++ )
				{
					$tr.append(
						$('<td>')
							.addClass( template[0] )
							.css('background-image', "url('" + urls[i] + "')")
					)
				}
				
				return [ $tr ];
			
			case 'pll' :
				
				var p = null, l1 = null, l2 = null;
				
				for( var i = 0; i < pattern.length; i++)
				{
					if( pattern[i] === 'p') { p = urls[i];  continue; }
					if( pattern[i] === 'l' && l1 === null ) { l1 = urls[i]; continue; }
					if( pattern[i] === 'l' ) { l2 = urls[i] }
				}
				return [
					$('<tr>').append(
						$('<td class="p">').css('background-image', "url('" + p + "')").attr('rowspan', 2),
						$('<td class="l">').css('background-image', "url('" + l1 + "')")
					),
					$('<tr>').append( $('<td class="l">').css('background-image', "url('" + l2 + "')") )
				];
			case 'lpp' :
				
				var p1 = null, p2 = null, l = null;
				
				for( var i = 0; i < pattern.length; i++)
				{
					if( pattern[i] === 'l') { l = urls[i]; continue; }
					if( pattern[i] === 'p' && p1 === null ) { p1 = urls[i]; continue; }
					if( pattern[i] === 'p' ) p2 = urls[i];
				}
				return [
					$('<td class="p">').css('background-image', "url('" + p1 + "')"),
					$('<td class="l">').css('background-image', "url('" + l + "')"),
					$('<td class="p">').css('background-image', "url('" + p2 + "')")
				];
			case  'lll2' :
				return [
					$('<tr>').append(
						$('<td class="l">').css('background-image', "url('" + urls[0] + "')").attr('rowspan', 2),
						$('<td class="l">').css('background-image', "url('" + urls[1]+ "')")
					),
					$('<tr>').append( $('<td class="l">').css('background-image', "url('" + urls[2] + "')") )
				];
		}
		return [];
	};
	
	var renderTemplate = function ( template, urls, pattern )
	{
		var $wrapper = $('<table>').addClass('temp');
		
		// render lll templates which are following lll as lll2
		if( lastTemplate === 'lll' && template === 'lll' ) template = 'lll2';
		// render lll templates which are following ppp as lll2
		if( lastTemplate === 'ppp' && template === 'lll' ) template = 'lll2';
		
		$wrapper.append( getTemplate( template, urls, pattern ) );
		$wrapper.addClass( 'temp-' + template );
		
		if( (template === 'pll' || template === 'ppl') && step % 2 === 0)
		{
			$wrapper.addClass('temp-rtl');
		}
		
		$wrapper.appendTo( container );
		
		lastTemplate = template;
		step++;
	};
	
	var render = function( urls, sizes )
	{
		var pattern = sizes.join(''); // 'llp'
		
		switch (pattern)
		{
			case 'p'   : return 0;
			case 'l'   : return 0; //renderTemplate('l',   urls, pattern ); return 1;
			case 'll'  : renderTemplate('ll',  urls, pattern ); return 2;
			case 'lll' : {
				if( step % 3 === 0 ) { renderTemplate('ll', urls, pattern); return 2 }
				renderTemplate('lll', urls, pattern ); return 3;
			}
			case 'pp'  : renderTemplate('pp',  urls, pattern ); return 2;
			case 'ppp' : renderTemplate('ppp', urls, pattern ); return 3;
			case 'lp'  :
			case 'pl'  : renderTemplate('pl',  urls, pattern ); return 2;
			case 'lpp' :
			case 'plp' :
			case 'ppl' : renderTemplate('lpp', urls, pattern ); return 3;
			case 'llp' :
			case 'lpl' :
			case 'pll' : renderTemplate('pll', urls, pattern ); return 3;
		}
		return 0;
	};
	
	var check = function ( images )
	{
		var firstThree = images.slice( 0, 3 );
		
		determineSizes ( firstThree, function( sizes )
		{
			var count = render( firstThree, sizes );
			
			images.splice( 0, count );
			
			if ( count > 0 )
			{
				check( images );
			}
			else
			{
				//beautify();
			}
		});
	};
	// start
	check( images );
}