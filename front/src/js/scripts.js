/*include /libs/jquery.core.js*/
/*include /libs/jquery.validval.js*/
/*include /libs/scrollbars.js*/

// --------------------- MASTER PART -------------------------- //
// ------------------------------------------------------------ //

(function($) {

$.fn.deserialize = function (serializedString) 
{
    var $form = $(this);
    $form[0].reset();
    serializedString = serializedString.replace(/\+/g, '%20');
    var formFieldArray = serializedString.split("&");

    $.each(formFieldArray, function(i, pair){
        var nameValue = pair.split("=");
        if(nameValue[0] !== null){
            var name = decodeURIComponent(nameValue[0]);
            var value = decodeURIComponent(nameValue[1]);
            // Find one or more fields
            var $field = $form.find('[name=' + name + ']');    
            
            if ($field[0].type == "radio" 
                || $field[0].type == "checkbox") 
            {
                var $fieldWithValue = $field.filter('[value="' + value + '"]');            
                var isFound = ($fieldWithValue.length > 0);
                if (!isFound && value == "on") {
                    $field.first().prop("checked", true);
                } else {
                    $fieldWithValue.prop("checked", isFound);
                } 
            } else {
                $field.val(value);
            }
        }
    });
}

var Master = {

    onready : function(){

    	$('form').validVal({
    	});

    	$('.wmw-uploadfield button').on('click', function(){
    		$(this).parent().find('input[type=file]').trigger( 'click' );
    	});

    	$('.wmw-uploadfield input[type=file]').on('change', function(){
    		var val = $(this).val();
    		$(this).parent().find('.wmw-uploadfield-val').text( val );
    	});

        $('.wmw-rangefield input[type=range]').on('change input', function(){
            var $val = $(this).parent().find('.wmw-rangefield-value span');
            $val.text( $(this).val() );
        });

        $('.wmw-onboard-price input[type=range]').on('change input', function(){
            var $val = $(this).parent().parent().find('input[type=text]');
            $val.val( $(this).val() );
        });

    	$('.wmw-checklinks a').on('click', function(e){
    		e.preventDefault();
    		
    		var $field = $(this).next('input');
    		$field.trigger( 'click' );

    		if($field.is(':checked'))
    			$(this).addClass('active');
    		else
    			$(this).removeClass('active');
    	}); 

        $('.wmw-cdashboard-sidebar .sidebar-button').on('click', function(e){
            e.preventDefault();
            $('.wmw-cdashboard-sidebar').toggleClass('wmw-cdashboard-sidebar--active');
        });

        Master.init_mission_overlay();
    },

    onload : function(){

    },

    onresize : function(){

    },

    onscroll : function(){

    },

    open_overlay: function( id ){
        $( id ).addClass('wmw-overlay--active');
    },

    close_overlay: function( id ){
        $( id ).removeClass('wmw-overlay--active');
    },

    init_mission_overlay: function(){

        $('#wmw-overlay-ob-4 .wmw-overlay-close').on('click', function(){

            var num = $(this).parent().parent().attr('data-num');
            var val =  $('.wmw-onboard-switches-el[data-num='+num+'] input[type=hidden]').val();

            if(val == '')
                $('.wmw-onboard-switches-el[data-num='+num+'] input[type=checkbox]').trigger('click');

            Master.close_mission_overlay();
        });

        $('#wmw-form-ob-4 input[type=checkbox]').on('change', function(){

            if($(this).is(':checked')){
                var $el = $(this).parent().parent();
                Master.open_mission_overlay( $el );
            }else{
                $(this).parent().find('input[type=hidden]').val('');
            }
        });

        $('#wmw-form-ob-4 label a').on('click', function(e){
            e.preventDefault();

            var $el = $(this).parent().parent().parent().parent();
            var val = $el.find('input[type=hidden]').val();
            var $overlay = $('#wmw-overlay-ob-4');

            $overlay.deserialize( val );

            Master.open_mission_overlay( $el );
        });

        $('#wmw-overlay-ob-4').on('submit', function(){

            if($(this).find('.invalid').get().length == 0){

                var val = $(this).serialize();
                var num = $(this).attr('data-num');

                $('#wmw-ob-mission-'+num+'-val').val( val );
                Master.close_mission_overlay();
            }

            return false;
        });
    },

    open_mission_overlay: function( $el ){

        var title = $el.find('label:first-child').text();
        var num = $el.attr('data-num');
        var $overlay = $('#wmw-overlay-ob-4');

        $overlay.find('.wmw-overlay-title').text( title );
        $overlay.attr('data-num', num);
        $overlay.find('input[type=range]').trigger('change');

        Master.open_overlay('#wmw-overlay-ob-4');
    },

    close_mission_overlay: function(){

        var $overlay = $('#wmw-overlay-ob-4');

        $overlay.attr('data-num', '');
        $overlay[0].reset();
        Master.close_overlay( $overlay );
    }
};

$(document).ready( function(){
    Master.onready();
});

$(window).on( 'load', function(){
    Master.onload();
});

$(window).on( 'resize', function(){
    Master.onresize();
});

$(window).on( 'scroll', function(){
    Master.onscroll();
});

})( jQuery );