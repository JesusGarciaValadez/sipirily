//  @codekit-prepend "plugins.js";
/**
 *
 *  @function
 *  @description:   Anonimous function autoexecutable
 *  @params jQuery $.- An jQuery object instance
 *  @params window window.- A Window object Instance
 *  @author: @_Chucho_
 *
 */
(function($, window, document, undefined) {
    //  Revisa la disponibilidad de localStorage
    var storage;
    if ('localStorage' in window && window.localStorage !== null) {
        storage = localStorage;
    } else{
        try{
            if (localStorage.getItem) {
                storage = localStorage;
            }
        } catch(e) {
            storage ={};
        }
    }

    //  When DOM is loaded
    $(function() {
        yourPhoto.init();
        //  Click behavior for Menu button in mobile version
        $('.cell').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            $('.header ul').toggleClass('active');
        });
    });

    //  When page is finished loaded
    $('document').ready(function() {
        //  Calculate paginator's width
        if ($('.pageList').exists()) {
            yourPhoto.resizePaginator();
        }
        $(window).on('resize', function(e) {
            e.preventDefault();
            e.stopPropagation();
            //  Crea las instancias de jScrollPane para los elementos que simulan
            //  ser select tags
            if (!$('.jspScrollable').exists()) {
                yourPhoto.makeScrollBar($('.select_municipality .mask'));
            }
            //  Calculate paginator's width
            if ($('.pageList').exists()) {
                yourPhoto.resizePaginator();
            }
        });

        $('header form.mobile fieldset, .year-picker.mobile fieldset').on('change', 'select', function(e) {
            var _value  = $(e.currentTarget).val();
            window.location.href = _value;
        });

        if ($('form[name="contact-form"]').exists()) {

            //  Validation of the contact form
            $('input[type="submit"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var contact         ={};
                contact.nombre      = $.trim($('input[name="name"]').val());
                contact.correo      = $.trim($('input[name="email"]').val());
                contact.mensaje     = $.trim($('textarea[name="comments"]').val());
                var flag            = true;

                $('input.error, textarea.error').removeClass('error');

                //  Valida el nombre
                if (!yourPhoto._validateMinLength(2, contact.nombre.length)) {
                    $('input[name="name"]').addClass('error');
                    flag    = false;
                }
                if (!yourPhoto._validateMaxLength(140, contact.nombre.length)) {
                    $('input[name="name"]').addClass('error');
                    flag    = false;
                }

                //  Valida el correo
                if (!yourPhoto._validateMinLength(2, contact.correo.length)) {
                    $('input[name="email"]').addClass('error');
                    flag    = false;
                }
                if (!yourPhoto._validateMail(contact.correo)) {
                    $('input[name="email"]').addClass('error');
                    flag    = false;
                }

                //  Valida que se escriba un mensaje
                if (!yourPhoto._validateMinLength(8, contact.mensaje.length)) {
                    $('textarea').addClass('error');
                    flag    = false;
                }
                if (!yourPhoto._validateMaxLength(140, contact.mensaje.length)) {
                    $('textarea').addClass('error');
                    flag    = false;
                }

                if (!flag) {
                    return false;
                }

                var errorFunction   = function() {
                    yourPhoto.showAlert('fail', 'Error al hacer el envío');
                };

                var successFunction = function(responseText) {
                    //console.log(responseText.success);

                    var _timer, _class, _message;

                    try{
                        if ($.parseJSON(responseText)) {

                            responseText    = $.parseJSON(responseText);

                            if (responseText &&(responseText.success === 'true' || responseText.success === true)) {
                                _class  = 'success';
                                _message= 'Éxito';
                            } else{
                                _class  = 'fail';
                                _message= 'Error al hacer el envío';
                            }
                        } else{
                            throw new Error('No es un JSON');
                        }
                    } catch(e) {
                        _class  = 'info';
                        _message= 'Intenta más tarde.';
                    } finally{
                        yourPhoto.showAlert(_class, _message);
                        _timer  = setTimeout(function() {
                            yourPhoto.hideAlert(_class);
                            clearTimeout(_timer);
                        }, 5000);
                    }
                };

                yourPhoto.validateForm('form[name="contact-form"]', contact, errorFunction, successFunction);
            });
        }

        if ($('form[name="share-form"]').exists()) {

            // Show the form to share the image.
            $('.social-button:last-child').on('click', 'a', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $('form[name="share-form"]').fadeToggle(150);
            });

            //  validation of Share form
            $('input[type="submit"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var share         ={};
                share.correo      = $.trim($('input[name="email"]').val());
                share.image       = $.trim($('input[name="image"]').val());
                var flag          = true;

                $('input.error').removeClass('error');

                //  Valida el correo
                if (!yourPhoto._validateMinLength(2, share.correo.length)) {
                    $('input[name="email"]').addClass('error');
                    flag    = false;
                }
                if (!yourPhoto._validateMail(share.correo)) {
                    $('input[name="email"]').addClass('error');
                    flag    = false;
                }

                if (!flag) {
                    return false;
                }

                var errorFunction   = function() {
                    yourPhoto.showAlert('fail', 'Error al hacer el envío');
                };

                var successFunction = function(responseText) {
                    //console.log(responseText.success);

                    var _timer, _class, _message;

                    try{
                        if ($.parseJSON(responseText)) {

                            responseText    = $.parseJSON(responseText);

                            if (responseText &&(responseText.success === 'true' || responseText.success === true)) {
                                _class  = 'success';
                                _message= 'Éxito';
                            } else{
                                _class  = 'fail';
                                _message= 'Error al hacer el envío';
                            }
                        } else{
                            throw new Error('No es un JSON');
                        }
                    } catch(e) {
                        _class  = 'info';
                        _message= 'Intenta más tarde.';
                    } finally{
                        yourPhoto.showAlert(_class, _message);
                        _timer  = setTimeout(function() {
                            yourPhoto.hideAlert(_class);
                            clearTimeout(_timer);
                        }, 5000);
                    }
                };

                yourPhoto.validateForm('form[name="share-form"]', share, errorFunction, successFunction);
            });
        }

        //  Funcionamiento del botón Regresar
        if ($('.return').exists()) {
            $('section.central').on('click', '.return', function(e) {
                e.stopPropagation();
                e.preventDefault();

                window.history.back();
            });
        }

        /**
         *
         *  Anchor the menu when the screen is up to certain Y coord while doing scroll
         *
         */
        //  !Ancla el menú cuando pasa de cierta coordenada Y al hacer scroll
        if ($('.photo aside').exists()) {
            $(window).on('scroll', function() {
                yourPhoto.anchorMenu('.photo aside', '30', 'share');
            });
        }

        if ( $( '.navigator' ).exists() ) {
            window.addEventListener( "keydown", function ( e ) {
                if ( e.defaultPrevented ) {
                    return; // Should do nothing if the default action has been cancelled
                }

                var handled = false, _key, _url;
                if ( e.key !== undefined ) {
                    // Handle the event with KeyboardEvent.key and set handled true.
                    _key    = e.key;
                    handled = true;
                } else if ( e.keyIdentifier !== undefined ) {
                    // Handle the event with KeyboardEvent.keyIdentifier and set handled true.
                    _key    = e.keyIdentifier;
                    handled = true;
                } else if ( e.keyCode !== undefined ) {
                    // Handle the event with KeyboardEvent.keyCode and set handled true.
                    _key    = e.keyCode;
                    handled = true;
                }

                switch ( _key ) {
                    case 'Left':
                    case '37':
                        _url = $('.navigator.prev li a').attr( 'href' );
                        location.href = _url;
                        break;
                    case 'Right':
                    case '39':
                        _url = $('.navigator.next li a').attr( 'href' );
                        location.href = _url;
                        break;
                    default:
                        break;
                }

                if ( handled ) {
                    // Suppress "double action" if event handled
                    e.preventDefault();
                }
            }, true );
        }

        //  Mostrar el logo del sitio al azar entre la grilla de imágenes del home
        if ($('.home').exists()) {
            var _images = $('.central figure'), choosenOne;
            setInterval( function () {
                _images.removeClass('displayed');
                choosenOne = Math.random() * 100;
                while (choosenOne > 12 ) {
                    choosenOne = Math.floor(Math.random() * 100);
                }
                _images.eq(choosenOne).addClass('displayed');
            }, 3000 );
        }
    });
})(jQuery, window, document);