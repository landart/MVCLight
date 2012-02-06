/**
 * MVCLight
 *
 * An open source application development framework for PHP
 *
 * @package		MVCLight
 * @author		Jorge Albaladejo Pomares
 * @copyright	Copyright (c) 2011, Jorge Albaladejo Pomares
 * @license		http://jorgealbaladejo.com/portfolio/MVCLight/license.txt
 * @link		http://jorgealbaladejo.com/MVCLight
 * @since		Version 0.1a
 * @filesource
 */

/**
 * application/webroot/js/main.js
 * 
 * Demo application JS behavior. Needs jQuery.
 * 
 */

$(document).ready( function(){
	$('a').each(function(){
		addClickListener($(this));
	})
});

/**
 * Manages the click event on the selected link
 * 
 * @param anchor target
 */
function addClickListener(target)
{
	$(target).click(function(e){
		if ($(this).parent().parent().attr('id') != 'index')
		{
			e.preventDefault();
			$('#ajaxPanel').load($(this).attr('href'),function(){
				if($(target).attr('class') == 'session' || $(target).attr('class') == 'language' )
				{
					$('#ajaxPanel a').each(function(){
						addClickListener($(this));
					})	
				};
			});			
		}						 
	})
}
