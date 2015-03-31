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

    var _yourPhoto    = window._yourPhoto,
    yourPhoto,
    // Use the correct document accordingly with window argument(sandbox)
    document    = window.document,
    location    = window.location,
    navigator   = window.navigator;

    // Map over yourPhoto in case of overwrite
    _yourPhoto  = window.yourPhoto;

    // Define a local copy of yourPhoto
    yourPhoto = function() {
        if(!(this instanceof yourPhoto)) {

            // The yourPhoto object is actually just the init constructor 'enhanced'
            return new yourPhoto.fn.init();
        }
        return yourPhoto.fn.init();
    };

    //  Object prototyping
    yourPhoto.fn = yourPhoto.prototype = {
        /**
         *
         *  @function:  !constructor
         *  @description:   Constructor method
         *  @author: @_Chucho_
         *
         */
        //  Método constructor
        constructor:    yourPhoto,
        /**
         *
         *  @function:  !init
         *  @description:   Inicializer method
         *  @author: @_Chucho_
         *
         */
        //  !Método inicializador
        init:   function() {
            if(!$('.jspScrollable').exists()) {
                //  Crea las instancias de jScrollPane para los elementos que simulan
                //  ser select tags
                yourPhoto.makeScrollBar($('.select_municipality .mask'));
            }
            if('header .select') {
                //  Trigger para emular el comportamiento de combo box
                $('.select').on('click', 'button', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    $('nav.mask').toggleClass('active');
                });
                $('body').on('click', function(e) {
                    e.stopPropagation();
                    if($('nav.mask').hasClass('active')) {
                        $('nav.mask').removeClass('active');
                    }
                });
            }
        },
        /**
         *
         *  @function:  !makesUniform
         *  @description:   Makes the uniform effect to radius and checkbox
         *  @params jQuery selector.- A jQuery Selector
         *  @see:   http://uniformjs.com/
         *  @author: @_Chucho_
         *
         */
        //  !Crea un efecto para poder dar estilos a los elementos checkbox,
        //  radio, file y select
        makesUniform:   function(selector) {
            selector.uniform();
        },
        /**
         *
         *  @function:  makeScrollBar
         *  @description:  Make jScrollPane where is needed
         *  @params jQuery selector.- A jQuery Selector
         *  @params Object options.- A JSON object with the options to make a
         *                           target element a jScrollPane Element
         *  @author: @_Chucho_
         *  @see:   http://www.jscrollpane.kelvinluck.com/
         *
         */
        //  !Crea un elemento jScrollPane.
        makeScrollBar:  function(selector, options) {

            var _options    =(options === undefined) ? {} : options;

            // the element we want to apply the jScrollPane
            selector.jScrollPane(_options);
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
        resizePaginator:    function() {
            var _childrensLength, _childrensWidth,
                _childrensMargin, _childrensMarginTotal,
                _paginatorWidthPX, _paginatorWidthVW,
                _childrens;
            _childrens              = $('.pageList li');
            _childrensLength        = _childrens.length;
            _childrensWidth         = _childrens.width();

            //  Obtain a sample of the margin used for the items in paginator
            //  And parse like an integer to strip px measurements
            _childrensMargin        = Math.ceil(parseFloat(_childrens.eq(0).css('margin-right')));
            //  Calculate the total of the margin used for all the items in paginator
            _childrensMarginTotal   = _childrensMargin *((_childrensLength * 2) - 2);

            //  Calculate the width in px
            _paginatorWidthPX       =(_childrensLength * _childrensWidth) +(_childrensMarginTotal);

            //  Make the calcule to translate in viewport width
            _paginatorWidthVW       = _paginatorWidthPX /(window.innerWidth * 0.01);
            $('.paging, .pageList').width(_paginatorWidthVW + 'vw');
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
        anchorMenu: function(selectorToApply, offsetTop, classToFix) {
            var _tool,_selector,_offsetTop,_classToFix;
            _tool           =(window.pageYOffset !== undefined) ? window.pageYOffset :(document.documentElement || document.body.parentNode || document.body).scrollTop;
            _selector       =(typeof(selectorToApply) == "undefined") ? "*" : selectorToApply;
            _selector       =(typeof(_selector) == "object") ? _selector : $(_selector);
            _offsetTop      =(offsetTop == "") ? 0 : offsetTop;
            _offsetTop      =(typeof(_offsetTop) == "string") ? parseInt(_offsetTop) :(typeof(_offsetTop) == "number") ? _offsetTop : parseInt(_offsetTop);
            _classToFix     =(typeof(classToFix) == "string") ? classToFix : '';
            if(_tool >= _offsetTop) {
                _selector.addClass(_classToFix);
            } else {
                _selector.removeClass(_classToFix);
            }
        },
    };

    // Give the init function the yourPhoto prototype for later instantiation
    yourPhoto.fn.init.prototype = yourPhoto.fn;

    yourPhoto = yourPhoto.fn;

    // Expose yourPhoto to the global object

    window.yourPhoto  = yourPhoto;

})(jQuery, window);