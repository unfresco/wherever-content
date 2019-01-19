/* jshint undef: true, unused: false, multistr:true */
/* global inlineEditL10n, ajaxurl, typenow, console */

(function( $ ) {
	'use strict';
	
	var carbonfieldsApi = null;
	
	// Places
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
		complexFieldKey = 'wherever_places',
		isWhereverPlacesField = fieldName.startsWith(complexFieldKey),
		isPlaceField = fieldName.endsWith(':_/place'),
		
		regexBrackets = /\[(.*?)\]/g,
		indexBrackets = fieldName.match(regexBrackets)[0], // match the index "[n]"
		
		hiddenFieldName = '_' + complexFieldKey + indexBrackets + '[value]';
		
		// Check if field is part of the wherever_place complex fields
		if ( ! isWhereverPlacesField || ! isPlaceField ) {
			return;
		} 
		
		setTimeout( function(){
			var 
			$hiddenField = $('input[name="' + hiddenFieldName + '"]'),
			$htmlContainer = $hiddenField.parent().find('.fields-container .place-content-info .field-holder div');

			if ( $htmlContainer.length ) {
				$htmlContainer.html( getPlaceDescription( fieldValue ) );
			}
		}, 100 );
	
	}

	function getPlaceDescription( key ) {
		var string = '';

		_.each( wherever_admin_js['wherever_places'], function( value ){
			if ( key === value.slug ) {
				string = '<p><span class="dashicons dashicons-location"></span> ' + value.description + '</p>';
			}
		});
		
		return string;
		
	}
	
	// Rules
	function setRulesInfoInit() {
		var complexFieldValues = carbonfieldsApi.getFieldValue('wherever_rules');
		
		if ( ! complexFieldValues.length ) {
			return;
		}
		
		_.each( complexFieldValues, function( value, index ){
			var 
			locationTypeFieldName = 'wherever_rules[' +  index + ']:_/location_type',
			locationConditionFieldName = 'wherever_rules[' + index + ']:_/location_condition';
			
			setRulesInfo( locationTypeFieldName, locationConditionFieldName );
		});
		
	}
	
	function setRulesInfo( fieldName ) {
		var
		fieldValue = carbonfieldsApi.getFieldValue(fieldName),
		complexFieldKey = 'wherever_rules',
		locationTypeKey = ':_/location_type',
		locationConditionKey = ':_/location_condition',
		isWhereverRulesField = fieldName.startsWith(complexFieldKey),
		isLocationTypeField = fieldName.endsWith(locationTypeKey),
		isLocationConditionField = fieldName.endsWith(locationConditionKey),
		
		regexBrackets = /\[(.*?)\]/g,
		indexBrackets = fieldName.match(regexBrackets)[0], // match the index "[n]"
		
		locationTypeFieldValue = carbonfieldsApi.getFieldValue( complexFieldKey + indexBrackets + locationTypeKey ),
		locationConditionFieldValue = carbonfieldsApi.getFieldValue( complexFieldKey + indexBrackets + locationConditionKey ),

		hiddenFieldName = '_' + complexFieldKey + indexBrackets + '[value]';
		
		// Check if field is part of the wherever_rules complex fields
		if ( ! isWhereverRulesField ) {
			return;
		}
		
		setTimeout( function(){
			var 
			$hiddenField = $('input[name="' + hiddenFieldName + '"]'),
			$htmlContainer = $hiddenField.parent().find('.fields-container .rule-content-info .field-holder div');

			if ( $htmlContainer.length ) {
				$htmlContainer.html( getRuleDescription( locationTypeFieldValue, locationConditionFieldValue ) );
			}
		}, 100 );

	}
	
	function getRuleDescription( locationTypeFieldValue, locationConditionFieldValue ) {
		var string = '';

		_.each( wherever_admin_js['wherever_rules'], function( value ){
			if ( locationTypeFieldValue === value.location_type && locationConditionFieldValue === value.condition ) {
				string = '<p><span class="dashicons dashicons-move"></span> ' + value.description + '</p>';
			}
		});
		
		return string;
		
	}
	
	// Carbon events
	$(document).on('carbonFields.apiLoaded', function(e, api) {
		
		carbonfieldsApi = api;

		setPlacesInfoInit();
		setRulesInfoInit();
		
		$(document).on('carbonFields.fieldUpdated', function(e, fieldName) {
			
			if ( 'wherever_places' === fieldName ) {
				setPlacesInfoInit();
			}
			
			if ( 'wherever_rules' === fieldName ) {
				setRulesInfoInit();
			}
			
			setPlacesInfo( fieldName );
			setRulesInfo( fieldName );
		
		});

	});

	$( document ).ready( function(){
		
	});

})( jQuery );
