/* jshint undef: true, unused: false, multistr:true */
/* global inlineEditL10n, ajaxurl, typenow, console */

(function( $ ) {
	'use strict';
	
	var carbonfieldsApi = null;
	
	function getPlaceDescription( key ) {
		var string = '';

		_.each( wherever_admin_js['wherever_places'], function( value ){
			if ( key === value.slug ) {
				string = '<p><span class="dashicons dashicons-location"></span> ' + value.description + '</p>';
			}
		});
		
		return string;
		
	}
	
	function setPlacesInfoInit() {
		var complexFieldValues = carbonfieldsApi.getFieldValue('wherever_places');
		
		if ( ! complexFieldValues.length ) {
			return;
		}
		
		_.each( complexFieldValues, function( value, index ){
			var fieldName = 'wherever_places[' +  index + ']:_/place';
			setPlacesInfo(fieldName);
		});
		
	}
	
	function setPlacesInfo( fieldName ) {
		var 
		fieldValue = carbonfieldsApi.getFieldValue(fieldName),
		isWhereverPlacesField = fieldName.startsWith('wherever_places'),
		isPlaceField = fieldName.endsWith(':_/place'),
		regexBrackets = /\[(.*?)\]/g,
		indexBrackets = fieldName.match(regexBrackets)[0], // match the index "[n]"
		selectFieldName = '_wherever_places' + indexBrackets + '[_place]';
		
		// Check if it is the right field
		if ( ! isWhereverPlacesField || ! isPlaceField ) {
			return;
		} 
		
		setTimeout( function(){
			var 
			$selectField = $('select[name="' + selectFieldName + '"]'),
			$htmlContainer = $selectField.closest('.fields-container').find('.place-content-info .field-holder div');

			if ( $htmlContainer.length ) {
				$htmlContainer.html( getPlaceDescription( fieldValue ) );
			}
		}, 100 );
	
	}
	
	$(document).on('carbonFields.apiLoaded', function(e, api) {
		
		carbonfieldsApi = api;

		setPlacesInfoInit();
		
		$(document).on('carbonFields.fieldUpdated', function(e, fieldName) {
			
			if ( 'wherever_places' === fieldName ) {
				setPlacesInfoInit();
			} else {
				setPlacesInfo( fieldName );
			}
			
		});

	});

	
	$( document ).ready( function(){ 
		
	});
	
	

})( jQuery );
