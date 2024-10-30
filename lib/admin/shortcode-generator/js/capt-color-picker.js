jQuery(document).ready(function($){
    $('.capt-color-picker').wpColorPicker({
		change: function(event, ui){
			setTimeout( function() {	
				regenerate_slider_shortcodes();
			}, 100);
		},
	});
});