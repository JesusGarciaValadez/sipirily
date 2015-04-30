/**
 *
 *  @function
 *  @description:   Anonimous function autoexecutable
 *  @params jQuery $.- An jQuery object instance
 *  @params window window.- A Window object Instance
 *  @author: @_Chucho_
 *
 */
(function($, window, undefined) {

    var _sipirily    = window._sipirily,
    sipirily,
    // Use the correct document accordingly with window argument(sandbox)
    document    = window.document,
    location    = window.location,
    navigator   = window.navigator;

    // Map over sipirily in case of overwrite
    _sipirily  = window.sipirily;

    // Define a local copy of sipirily
    sipirily = function() {
        if(!(this instanceof sipirily)) {

            // The sipirily object is actually just the init constructor 'enhanced'
            return new sipirily.fn.init();
        }
        return sipirily.fn.init();
    };

    //  Object prototyping
    sipirily.fn = sipirily.prototype = {
        /**
         *
         *  @function:  !constructor
         *  @description:   Constructor method
         *  @author: @_Chucho_
         *
         */
        //  Método constructor
        constructor:    sipirily,
        /**
         *
         *  @function:  !init
         *  @description:   Inicializer method
         *  @author: @_Chucho_
         *
         */
        //  !Método inicializador
        init:   function() {
            var _path = location.pathname;
            if ( _path === '/home' || _path === '/' ) {
                $('nav ul li').eq(0).addClass( 'active' );
            }
        },
        /**
         *
         *  @function:  !closeAlert
         *  @description:   Validación del formulario de encuesta de Marzo 2014.
         *  @params:    Object _rules Rules for each filed of the form
         *  @params:    Object _messages Messages for each filed of the form
         *  @params:    Object _submitFunction Function to handle succes in petition
         *  @params:    Object _invalidFunction Function to handle a invalid petition
         *  @see:   http://bassistance.de/jquery-plugins/jquery-plugin-validation/ ||
         *          http://docs.jquery.com/Plugins/Validation
         *  @author: @_Chucho_
         *
         */
        validateForm:   function(_jSelector, _dataPass, _errorFunction, _successFunction) {
            var action  = $(_jSelector).attr('action');
            $.ajax(action, {
                beforeSend: function() {
                    $('.error').remove();
                    if($('textarea').val() === "") {
                        $('textarea').val('Ninguno');
                    }
                },
                cache: false,
                complete: function() {},
                contentType: "application/x-www-form-urlencoded",
                converters: {
                    "* text":       window.String,
                    "text html":    true,
                    "text json":    $.parseJSON,
                    "text xml":     $.parseXML
                },
                data: _dataPass,
                error:  _errorFunction,
                success: _successFunction,
                type: "POST"
            });
        },
        _validateMail:          function(mail) {
            return(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i.test(mail)) ? true : false;
        },
        _validateNumber:        function(numberToCheck) {
            return /^\d+[^a-zA-Z]+$/.test(parseInt(numberToCheck));
        },
        _validateRange:         function(rangeTo, rangeFrom, valueToCheck) {
            return(rangeTo >= valueToCheck && rangeFrom <= valueToCheck) ? true : false;
        },
        _validateMinLength:     function(minLength, valueToCheck) {
            return(minLength < valueToCheck) ? true : false;
        },
        _validateMaxLength:     function(maxLength, valueToCheck) {
            return(valueToCheck <= maxLength) ? true : false;
        },
        _validateDate:          function(dateToCheck) {
            return(!/Invalid|NaN/.test(new Date(dateToCheck).toUTCString())) ? true : false;
        },
        showAlert:          function(_class, _text) {
            $('.alert').addClass(_class);
            $('.alert p').text(_text);
            $('.alert').fadeIn(300);
        },
        hideAlert:          function(_class) {
            $('.alert').fadeOut(300, function() {
                $('.alert').removeClass(_class)
                             .removeAttr('style');
                $('.alert p').empty();
                $('input[text],input[text],textarea').val();
            });
        },
        anchorMenu: function( selectorToApply, offsetTop, classToFix ) {
            var _tool,_selector,_offsetTop,_classToFix;
            _tool           =(window.pageYOffset !== undefined) ? window.pageYOffset :(document.documentElement || document.body.parentNode || document.body).scrollTop;
            _selector       =(typeof(selectorToApply) === "undefined") ? "*" : selectorToApply;
            _selector       =(typeof(_selector) === "object") ? _selector : $(_selector);
            _offsetTop      =(offsetTop === "") ? 0 : offsetTop;
            _offsetTop      =(typeof(_offsetTop) === "string") ? parseInt(_offsetTop) :(typeof(_offsetTop) === "number") ? _offsetTop : parseInt(_offsetTop);
            _classToFix     =(typeof(classToFix) === "string") ? classToFix : '';
            if(_tool >= _offsetTop) {
                _selector.addClass(_classToFix);
            } else {
                _selector.removeClass(_classToFix);
            }
        },
        /**
         *
         *  @function:  !managerTimelineFill
         *  @description:   Carrousel inicializer
         *  @params jQuery slider.- A jQuery Selector
         *  @params String progressBar.- A class to add to target
         *  @params Object ui.- An object with css properties to apply to the jQuery selector
         *  @params Number leftOffset.- A number to indicate the duration of the animation
         *  @params Number rightOffset.- A number to indicate the duration of the animation
         *  @see:   http://jquerytools.org
         *  @author: @_Chucho_
         *
         */
        //  !Inicializador de un carrusel jQuery Tools
        inicializeCarrousel:    function ( selector, optionsScrollable, optionsNavigator, optionsAutoscroll ) {

            _selector       = ( typeof( selector )  === "undefined" ) ? "*" : selector;
            _selector       = ( typeof( _selector ) === "object" )    ? _selector : $( _selector );

            if( !optionsScrollable || optionsScrollable === {} ) {
                optionsScrollable = {};
            }
            if( !optionsNavigator || optionsNavigator === {} ) {
                optionsNavigator = {};
            }
            if( !optionsAutoscroll || optionsAutoscroll === {} ) {
                optionsAutoscroll = {};
            }

            return _selector.scrollable( optionsScrollable ).navigator( optionsNavigator ).autoscroll( optionsAutoscroll );
        },
    };

    // Give the init function the sipirily prototype for later instantiation
    sipirily.fn.init.prototype = sipirily.fn;

    sipirily = sipirily.fn;

    // Expose sipirily to the global object

    window.sipirily  = sipirily;

})(jQuery, window);