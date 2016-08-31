/* jshint undef: true, unused: false, multistr:true */
/* global inlineEditL10n, ajaxurl, typenow, console */

(function( $ ) {
	'use strict';

	var inlineEditCatalog = {
	
		init : function(){
			var t = this;
	
			$('#doaction, #doaction2').click(function(e){
				var n = $(this).attr('id').substr(2);
				if ( 'edit' === $( 'select[name="' + n + '"]' ).val() ) {
					e.preventDefault();
					t.edit();
				}
			});	
		},

		edit : function() {
			var tax;
			
			// Add catalog suggestions
			if ( 'wherever' === typenow ) {
				tax = 'wherever_place';
				window.setTimeout(function() {
					$('tr.inline-editor textarea[name="tax_input['+tax+']"]').suggest( ajaxurl + '?action=ajax-tag-search&tax=' + tax, { delay: 500, minchars: 2, multiple: true, multipleSep: inlineEditL10n.comma } );
				}, 200);
			}
		
			return false;
		}
	
	};
	
	/*
	// Comment out because taxonomy’s UI (show_ui) is disabled 
	var disableEditDefaultPlaces = {
		init: function(){

			$('.edit-tags-php.taxonomy-wherever_place #the-list > tr').each(function( index ){
				var dom_row_name_wrapper = $(this).find('.column-name strong'),
					dom_row_name_link = $(this).find('.column-name a.row-title'),
					dom_row_name = dom_row_name_link.text(),
					dom_row_action = $(this).find('.row-actions');
				
				if( dom_row_action.css('display') === 'none' ){
					dom_row_name_wrapper.addClass('row-title');
					dom_row_name_link.remove();
					dom_row_name_wrapper.text(dom_row_name);
				}
			});
		}
	};
	*/
	
	$( document ).ready( function(){ 
		inlineEditCatalog.init(); 
		//disableEditDefaultPlaces.init();
	});
	
	

})( jQuery );