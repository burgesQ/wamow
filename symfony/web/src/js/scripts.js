/*include /libs/jquery.core.js*/
/*include /libs/jquery.validval.js*/
/*include /libs/jquery.scrollbars.js*/
/*include /libs/jquery.autocomplete.js*/
/*include /libs/sweetalert2.js*/
/*include /libs/modernizr.js*/

// --------------------- MASTER PART -------------------------- //
// ------------------------------------------------------------ //

function sortSelectOptions(selector, skip_first) {

    var options = (skip_first) ? $(selector + ' option:not(:first)') : $(selector + ' option');

    var arr = options.map(function(_, o) {
        return { t: $(o).text(), v: o.value, s: $(o).prop('selected') };
    }).get();

    arr.sort(function(o1, o2) {
        var t1 = o1.t.toLowerCase(), t2 = o2.t.toLowerCase();
        return t1 > t2 ? 1 : t1 < t2 ? -1 : 0;
    });

    options.each(function(i, o) {
        o.value = arr[i].v;
        $(o).text(arr[i].t);
        if (arr[i].s) {
            $(o).attr('selected', 'selected').prop('selected', true);
        } else {
            $(o).removeAttr('selected');
            $(o).prop('selected', false);
        }
    });
}

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
                var $field = $form.find('[name="' + name + '"]');

                console.log($field, name);

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

        $('.wmw-header-profile-button .wmw-button').on('click', function(e){
            e.preventDefault();
            $(this).parent().toggleClass('wmw-header-profile-button--active');
        });

        $('.wmw-header-languages-button .wmw-button').on('click', function(e){
            e.preventDefault();
            $(this).parent().toggleClass('wmw-header-languages-button--active');
        });

        $('body').on('click', function(){
             $('.wmw-header-profile-button').removeClass('wmw-header-profile-button--active');
            $('.wmw-header-languages-button').removeClass('wmw-header-languages-button--active');
        });

        $('.wmw-header-profile-button a, .wmw-header-profile-button .wmw-button, .wmw-header-languages-button a, .wmw-header-languages-button .wmw-button').on('click', function(e){
            e.stopPropagation();
        });

    	$('form').validVal({
            form : {
                onInvalid : function(){

                    swal({
                      title: '',
                      text: translation,
                      type: 'error',
                      confirmButtonText: 'OK',
                      confirmButtonColor: "#362a7e"
                    });
                }
            }
        });

        $('.wmw-button-later').on('click', function(){
            $('form').trigger("destroy.vv");
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
            var id = $(this).attr('href');
            var $field = $(this).parent().find( id );
            $field.prop('checked', false);
            $(this).remove();
        });

        $('.wmw-bgfield input[type=file]').on('change', function(){
            $(this).parent().find('input[type=text]').val( $(this).val() );
        });

        $('.wmw-autocomplete').each( function(){
            Master.init_autocomplete( $(this), Auditors );
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

        $('.wmw-mission-close').on('click', function(){
            $('.wmw-wrapper').animate({"opacity":"0"}, 300);
        });

        Master.init_pitch_finder();
        Master.init_mission_overlay();
        Master.init_mission_notes();
        Master.init_mission_shortlist_buttons();
        Master.init_dashboard_mission_slider();
        Master.init_copyfields();
        Master.resize_mail_content_chat();

        $('.wmw-overlay-inner').perfectScrollbar({ suppressScrollX:true });

        var $chat    = $('.mail-content-chat');
        if($chat.get().length>0){
            $chat.perfectScrollbar({ suppressScrollX:true });
            $chat.scrollTop( $chat[0].scrollHeight );
        }

        $('.wmw-mission-element .element-content').perfectScrollbar({ suppressScrollX:true });
        //$('.wmw-mission-element .element-notes').perfectScrollbar({ suppressScrollX:true });
        $('.wmw-mission-sidebar .sidebar-wrapper').perfectScrollbar({ suppressScrollX:true });
        $('.wmw-mission-content').perfectScrollbar({ suppressScrollY:true });
        $('.main-slider-element-wrapper').perfectScrollbar({ suppressScrollX:true });
        $('.mail-content-sidebar').not( $('.mail-content-sidebar--noscroll') ).perfectScrollbar({ suppressScrollX:true });

        Master.init_slider('.main-slider', '.wmw-slider-inner', '.wmw-slider-navigation', false, '.wmw-slider-element', false, false);

        Master.init_candidates_scrolling();
    },

    onload : function(){

    },

    onresize : function(){

        Master.resize_mail_content_chat();
    },

    onscroll : function(){

    },

    resize_mail_content_chat : function(){

        var $chat = $('.mail-content-chat');
        if($chat.get().length>0){

            var $header = $('.wmw-header'),
                $mission_header = $('.wmw-mission-header'),
                $answer = $('.mail-content-answer'),
                $footer = $('.wmw-footer'),
                new_height =    parseInt($(window).height()) -
                                parseInt($header.outerHeight()) -
                                parseInt($mission_header.outerHeight()) -
                                parseInt($answer.outerHeight()) -
                                parseInt($footer.outerHeight()) -
                                200;

            $chat.height( new_height );
        }
    },

    init_autocomplete : function( field, lookup ){

      $(field).autocomplete({
          lookup: lookup,
          preserveInput: true,
          minLength: 0,
          formatResult: function(suggestion, currentValue) {

              return suggestion.value;
          },
          onSelect: function (suggestion) {
              $(field).val( suggestion.value );
          }
      });
    },

    init_copyfields: function(){

        function add_copyfield( $copyfield ){

            var $gparent        = $copyfield.parent(),
                $field          = $copyfield.find('input, select'),
                field_nb        = parseInt($field.attr('data-copy-nb'))+1,
                field_name      = $field.attr('data-copy-name');

            $copyfield.after( $copyfield.clone() );

            var $next           = $copyfield.next(),
                $next_field     = $next.find('input, select'),
                $next_label     = $next.find('label');

            $next_field.attr('data-copy-nb', field_nb)
                       .attr('id', field_name+'-'+field_nb)
                       .val('');

            $next_label.attr('for', field_name+'-'+field_nb);

            $('form').validVal();
            if($copyfield.hasClass('wmw-copyfield--autocomplete'))
                Master.init_autocomplete( $next_field, Auditors );

            if($gparent.find('.wmw-copyfield').get().length > 1)
                $gparent.addClass('wmw-copyfields--multiple');
            else
                $gparent.removeClass('wmw-copyfields--multiple');
        }

        function remove_copyfield( $copyfield ){

            var $gparent = $copyfield.parent();

            $copyfield.remove();

            if($gparent.find('.wmw-copyfield').get().length > 1)
                $gparent.addClass('wmw-copyfields--multiple');
            else
                $gparent.removeClass('wmw-copyfields--multiple');
        }

        $('body').on('click', '.wmw-copyfield .wmw-button-more', function(e){
            e.preventDefault();

            var $copyfield = $(this).parent();
            add_copyfield( $copyfield );
        });

        $('body').on('click', '.wmw-copyfield .wmw-button-less', function(e){
            e.preventDefault();

            var $copyfield = $(this).parent();
            remove_copyfield( $copyfield );
        });
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

    init_candidates_scrolling : function(){

        /*var timer = false;

        function start_scrolling( direction ){

            if(!timer)
                timer = setInterval( function(){

                    var sleft = parseInt($('.wmw-mission-body').scrollLeft());
                    sleft = (sleft > 0) ? sleft-1:0; 
                    if( direction == 'right')
                        sleft = (sleft > ) ? sleft+1:$('.wmw-mission-body').width(); 
                
                    console.log(sleft);

                    $('.wmw-mission-body').scrollLeft(sleft);
                }, 50);
        }

        function stop_scrolling(){

            clearInterval( timer );
            timer = false;
        }

        if($('.wmw-mission-body').get().length>0){

            var $obj = $('.wmw-mission-body');
            var left = {};
            var offset = $obj.offset();
            var width = $obj.width();

            $('.wmw-mission-body').mousemove(function(e){

                left = {
                    px: (e.pageX - offset.left),
                    percent: (e.pageX - offset.left) / width
                };

                if( left.percent < 0.25){
                    start_scrolling('left');
                }else if( left.percent > 0.75){
                    start_scrolling('right');
                }else{
                    stop_scrolling();
                }
            });
        }*/
    },

    init_mission_notes : function(){

        function record_notes( $el ){

            var val = $el.val();
            var id = $el.attr('data-id');
            var path = $el.attr('data-path');
            var data = { "value" : val, "id" : id };

            $.ajax( path, {
                method : 'post',
                data : data
            }).done( function( response ){

                $el.val(response);
                $el.parent().find(".element-notes-status").stop(true, true).fadeIn(500).delay(5000).fadeOut(500);
            });
        }

        $('.element-notes-textarea').on('blur', function(){
            record_notes( $(this) );
        }).on('keyup keypress keydown', function(){

            if($(this).val() == '')
                $(this).parent().addClass('element-notes--empty');
            else
                $(this).parent().removeClass('element-notes--empty');
        });
    },

    init_mission_shortlist_buttons : function(){

        function shortlist( $field ){

            var data = { "id" : $field.val() };

            $.ajax( '../ajax/set-shortlist.php', {
                method : 'post',
                data : data,
                dataType : 'json'
            }).done( function( response ){

                if(response.status == "ok"){;

                    var $el = $('.wmw-mission-summary .summary-el--shortlist .number');
                    var num = parseInt($el.text());

                    $field.prop('checked', true);
                    $field.parent().find('.wmw-button').removeClass('wmw-button--border');

                    $el.text( num+1 );
                }
            });
        }

        $('.wmw-mission-element .wmw-checkbutton .wmw-button').on('click', function(e){
             e.preventDefault();
            var $field = $(this).parent().find('input[type=checkbox]');

            if($field.is(':checked')){
                alert("Advisor was successfully alerted, this action can't be cancelled");
            }else{

                if($('.wmw-mission-element .wmw-checkbutton input[type=checkbox]:checked').get().length < 3){

                    shortlist( $field );

                }else{

                    alert("You already choosed your 3 advisors");
                }
            }
        });
    },

    init_pitch_finder: function () {

        function get_price() {

            // var data = $("#wmw-pitchprice-form").serialize();
            //
            // $.ajax( '../ajax/get-price.php', {
            //     method : 'post',
            //     data : data,
            //     dataType : 'json'
            // }).done( function( response ){
            //
            //     var $range = $(".wmw-onboard-price-inner input[type=range]");
            //     var $input = $(".wmw-onboard-price-inner input[type=text]");
            //     $range.attr('min', response.price_min);
            //     $range.attr('max', response.price_max);
            //     $range.val( response.price_max );
            //     $input.val( response.price_max );
            // });
        }

        function get_people() {

            // var data = { "price" : $("#wmw-pitchprice-form input[type=range]").val() };
            //
            // $.ajax( '../ajax/get-people.php', {
            //     method : 'post',
            //     data : data,
            //     dataType : 'json'
            // }).done( function( response ){
            //
            //     var $people = $(".wmw-onboard-price-number span");
            //     $people.text( response.nb_people );
            // });
        }

        $('.wmw-pitch-finder').on('change', 'input, select', function () {
            // get_price();
        });

        $('.wmw-onboard-price input[type=range]').on('change', function () {
            // get_people();
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

            var $el = $(this).parent().parent().parent();
            var val = $el.find('input[type=hidden]').val();
            var $overlay = $('#wmw-overlay-ob-4');

            $overlay.deserialize( val );

            Master.open_mission_overlay( $el );
        });

        $('#wmw-overlay-ob-4').on('submit', function(){

            if($(this).find('.invalid').get().length == 0){

                var val = $(this).serialize();
                var num = $(this).attr('data-num');

                $('#app_user_profile_userWorkExpSerialized_'+num).val( val );
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
