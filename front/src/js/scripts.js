/*include /libs/jquery.core.js*/
/*include /libs/jquery.validval.js*/
/*include /libs/jquery.scrollbars.js*/

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

    	$('.wmw-iconfield input').on('change', function(e){
    		console.log('yo');
    	}); 

        $('.wmw-checklistfield button').on('click', function(){
            $(this).parent().find('.wmw-checklistfield-wrapper').toggleClass('wmw-checklistfield-wrapper--active');
        });

        $('body').on('click', function(){
            $('.wmw-checklistfield-wrapper').removeClass('wmw-checklistfield-wrapper--active');
        });

        $('.wmw-checklistfield').on('click', function(e){
            e.stopPropagation();
        });

        $('.wmw-checklistfield-wrapper input').on('change', function(){
            var $wrap = $(this).parent().parent();
            var id = $(this).attr('id');
            var val = $(this).val();
            if($(this).is(':checked')){
                $wrap.append('<a href="#'+id+'" class="wmw-tag">'+val+'</a>')
            }else{
                $wrap.find('a.wmw-tag[href="#'+id+'"]').remove();
            }
        });

        $('.wmw-checklistfield').on('click', 'a.wmw-tag', function(e){
            e.preventDefault();
            console.log('allo');
            var id = $(this).attr('href');
            var $field = $(this).parent().find( id );
            console.log($field, id);
            $field.prop('checked', false);
            $(this).remove();
        });

        $('body').on('click', '.wmw-copyfield .wmw-button-more', function(e){
            e.preventDefault();
            var $field = $(this).parent().find('input, select');
            
            var nb = $field.attr('data-copy-nb');
            var name = $field.attr('data-copy-name');

            $(this).parent().after($(this).parent().clone());
            $(this).remove();

            var $next = $(this).parent().next();
            var $next_field = $next.find('input, select');
            var $next_label = $next.find('label');

            $next_field.attr('id', name+'-'+nb);
            $next_label.attr('for', name+'-'+nb);
        });

        $('#wmw-pitch-onsite-wrapper input[type=checkbox]').on('change', function(){
            if($(this).is(':checked'))
                $('#wmw-pitch-onsite-wrapper select').show();
            else
                $('#wmw-pitch-onsite-wrapper select').hide()
        });

        $('.wmw-mission-sidebar .sidebar-button').on('click', function(e){
            e.preventDefault();
            $('.wmw-mission-sidebar').toggleClass('wmw-mission-sidebar--active');
        });

        $('.wmw-mission-summarybtn .wmw-button').on('click', function(e){
            e.preventDefault();
            $('.wmw-mission-summary').toggleClass('wmw-mission-summary--active');
            $(this).parent().toggleClass('wmw-mission-summarybtn--active');
        });

        Master.init_pitch_finder();
        Master.init_mission_overlay();
        Master.init_mission_notes();
        Master.init_mission_pitch();
        Master.init_dashboard_mission_slider();

        $('.wmw-overlay-inner').perfectScrollbar({ suppressScrollX:true });  
        
        var $chat    = $('.mail-content-chat');
        if($chat.get().length>0){
            $chat.perfectScrollbar({ suppressScrollX:true });
            $chat.scrollTop( $chat[0].scrollHeight );
        }

        $('.wmw-mission-element .element-content').perfectScrollbar({ suppressScrollX:true }); 
        $('.wmw-mission-element .element-notes').perfectScrollbar({ suppressScrollX:true }); 
        $('.wmw-mission-sidebar .sidebar-wrapper').perfectScrollbar({ suppressScrollX:true }); 
        $('.wmw-mission-content').perfectScrollbar({ suppressScrollY:true }); 
        $('.main-slider-element-wrapper').perfectScrollbar({ suppressScrollX:true });
        $('.mail-content-sidebar').perfectScrollbar({ suppressScrollX:true });

        Master.init_slider('.main-slider', '.wmw-slider-inner', '.wmw-slider-navigation', false, '.wmw-slider-element', false, false);
    },

    onload : function(){

    },

    onresize : function(){

    },

    onscroll : function(){

    },

    init_dashboard_mission_slider: function(){

        function i_am_interested( id ){

            var data = { "id" : id };

            $.ajax( '../ajax/interested.php', {
                method : 'post',
                data : data,
                dataType : 'json'
            }).done( function( response ){

                if(response.status = 'ok'){
                    $('.main-slider-interested[data-mission-id='+id+']')
                        .parent().find('.main-slider-hover')
                        .addClass('main-slider-hover--interested');
                }
            });
        }

        $('.main-slider-interested').on('click', function(){
            var mission_id = $(this).attr('data-mission-id');
            i_am_interested( mission_id );
        });
    },

    init_mission_notes : function(){

        function record_notes( notes, id ){

            var data = { "notes" : notes, "id" : id };

            $.ajax( '../ajax/set-notes.php', {
                method : 'post',
                data : data,
                dataType : 'json'
            }).done( function( response ){

                var $status = $('#wmw-mission-notes-status');
                $status.text(response.status).stop(true, true).fadeIn(500).delay(5000).fadeOut(500); 
            });
        }

        $('#wmw-mission-notes').on('blur', function(){
            var val = $(this).val();
            var id = $("#wmw-mission-id").val();
            record_notes(val, id);
        });
    },

    init_mission_pitch : function(){

        $('.mail-content-pitch form').on('submit', function(){

            if( !$('.mail-content-pitch-payment').hasClass('mail-content-pitch-payment--active') &&
                $('.mail-content-pitch form .invalid').length == 0){

                $('.mail-content-pitch-payment').addClass('mail-content-pitch-payment--active');

                return false;
            }
        });
    },

    init_pitch_finder : function(){

        function get_price(){

            var data = $("#wmw-pitchprice-form").serialize();

            $.ajax( '../ajax/get-price.php', {
                method : 'post',
                data : data,
                dataType : 'json'
            }).done( function( response ){

                var $range = $(".wmw-onboard-price-inner input[type=range]");
                var $input = $(".wmw-onboard-price-inner input[type=text]");
                $range.attr('min', response.price_min);
                $range.attr('max', response.price_max);
                $range.val( response.price_max );
                $input.val( response.price_max );
            });
        }

        function get_people(){

            var data = { "price" : $("#wmw-pitchprice-form input[type=range]").val() };

            $.ajax( '../ajax/get-people.php', {
                method : 'post',
                data : data,
                dataType : 'json'
            }).done( function( response ){

                var $people = $(".wmw-onboard-price-number span");
                $people.text( response.nb_people );
            });
        }

        $('.wmw-pitch-finder').on('change', 'input, select', function(){
            get_price();
        });

        $('.wmw-onboard-price input[type=range]').on('change', function(){
            get_people();
        });
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
                $('.wmw-onboard-switches-el[data-num='+num+'] input[type=checkbox]').trigger('click').removeClass('invalid');

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
    },

    // Fonction d'initialisation de slider
    // slider (str) le sélecteur du slider
    // list (str) le sélecteur du wrapper
    // listController (str) le sélecteur du wrapper de navigation
    // listPagination (str) le sélecteur du wrapper de pagination
    // listElement (str) le sélecteur des slides
    // callback (function) la fonction de callback
    // touch (boolean) touch actif (nécessite jquery swipe events) || (function) callback
    // touchPrev (function) callback

    init_slider : function(slider, list, listController, listPagination, listElement, touch, touchPrev) {

        if(typeof(callback)=='undefined')
            callback = function(){};

        if(typeof(touch)=='undefined')
            touch = true;

        if(typeof(touchPrev)=='undefined')
            touchPrev = false;

        var prev = slider+' '+listController+'--prev';
        var next = slider+' '+listController+'--next';

        var pagination = slider+' '+listPagination;
        var paginationItem = pagination + ' a';

        var element = slider+' '+listElement;

        var activeClass = 'wmw-slider-element--active';
        var activeElement = element + '.' + activeClass;

        if(!$(paginationItem).length){

            $(element).each(function() {
                $(pagination).append('<a href="#"></a>');
            });
        }

        $(element + ':first-child').addClass(activeClass);
        $(paginationItem + ':first-child').addClass(activeClass);

        $(prev).click(function(e) {

            e.preventDefault();

            if($(element + ':first-child').hasClass(activeClass)) {

                $(activeElement).removeClass(activeClass);
                $(element + ':last-child').addClass(activeClass);
            }

            else {

                $(activeElement).removeClass(activeClass).prev().addClass(activeClass);
            }

            coordinatePagination();
        });

        $(next).click(function(e) {

            e.preventDefault();

            if($(element + ':last-child').hasClass(activeClass)) {

              $(activeElement)
                .removeClass(activeClass);

              $(element + ':first-child')
                  .addClass(activeClass);
            }
            else{

              $(activeElement)
                .removeClass(activeClass)
                .next()
                  .addClass(activeClass);
            }

            coordinatePagination();
        });

        if(touch === true && typeof(touch) != 'function'){

            $(slider).on('swipeleft',function(){
              if($(next).get().length>0)
                $(next).trigger('click');

              else{

                var $btn = false;
                $btn = $(paginationItem+'.active').next('a:visible');

                if($btn === false){
                  $btn = $(paginationItem+':first-child');
                }

                $btn.trigger('click'); 
              }
            });
            $(slider).on('swiperight',function(){
              if($(prev).get().length>0)
                $(prev).trigger('click');

              else{
                
                var $btn = false;
                $btn = $(paginationItem+'.active').next('a:visible');

                if($btn === false){
                  $btn = $last;
                }

                $btn.trigger('click'); 
              }
            }); 

        }else{

            if(typeof(touch) === 'function'){
                $(slider).on('swipeleft',function(){
                    touch();
                });
            }

            if(typeof(touchPrev) === 'function'){
                $(slider).on('swiperight',function(){
                    touchPrev();
                }); 
            }
        }

        $(paginationItem).click(function(e) {

            e.preventDefault();

            $(paginationItem)
              .removeClass(activeClass);
            $(this)
              .addClass(activeClass);

            $(activeElement)
              .removeClass(activeClass);
            $(element + ':nth-child(' + ($(paginationItem + '.' + activeClass).index() + 1) + ')')
              .addClass(activeClass);
        });

        function coordinatePagination() {

            $(paginationItem)
              .removeClass(activeClass);
            $(paginationItem + ':nth-child(' + ($(activeElement).index() + 1) + ')')
              .addClass(activeClass);

            callback($(activeElement));
        }
    },
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