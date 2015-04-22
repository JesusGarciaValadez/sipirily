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
    } else {
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
        sipirily.init();
        //  Click behavior for Menu button in mobile version
        $('.cell').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            $('.header ul').toggleClass('active');
        });
    });

    //  When page is finished loaded
    $('document').ready(function() {
        if ($('.wrapper-gallery').exists()) {
            sipirily.inicializeCarrousel( '.wrapper-gallery .gallery .scrollable', {
                speed: 1000,
                circular: true,
                keyboard: false,
                next: '.next',
                prev: '.prev'
            }, {
                activeClass: "active",
                navi: ".wrapper-gallery .gallery .navigator",
                naviItem: "a",
                indexed: false
            }, {
                steps: 1,
                interval: 10000,
                autoplay: true,
                autopause: true
            } );
        }

        if ($('.branch-hightlighted').exists()) {
            sipirily.inicializeCarrousel( '.branch-hightlighted .panes .photo.scrollable', {
                speed: 1000,
                circular: false,
                keyboard: false,
                next: '.branch-hightlighted .panes .photo .btn-next',
                prev: '.branch-hightlighted .panes .photo .btn-prev'
            }, {}, {
                steps: 1,
                interval: 10000,
                autoplay: false,
                autopause: false
            } );

            sipirily.inicializeCarrousel( '.branch-hightlighted .panes .video.scrollable', {
                speed: 1000,
                circular: false,
                keyboard: false,
                next: '.branch-hightlighted .panes .video .btn-next',
                prev: '.branch-hightlighted .panes .video .btn-prev'
            }, {}, {
                steps: 1,
                interval: 10000,
                autoplay: false,
                autopause: false
            } );

            $('ul.tabs').tabs('div.panes > div');
        }

        if ($('.character-gallery').exists()) {
            sipirily.inicializeCarrousel( '.character-gallery .scrollable', {
                speed: 1000,
                circular: true,
                keyboard: false,
                next: '.character-gallery .next',
                prev: '.character-gallery .prev'
            }, {}, {
                steps: 1,
                interval: 10000,
                autoplay: true,
                autopause: true
            } );
        }

        if ($('.banner-our-promotions').exists()) {
            sipirily.inicializeCarrousel( '.banner-our-promotions .scrollable', {
                speed: 1000,
                circular: true,
                keyboard: false,
                next: '.banner-our-promotions .btn-next-green',
                prev: '.banner-our-promotions .btn-prev-green'
            }, {}, {
                steps: 1,
                interval: 10000,
                autoplay: true,
                autopause: true
            } );
        }

        if ($('form').exists()) {

            //  Validation of the contact form
            $('input[type="submit"]').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var contact         ={};
                contact.sucursal    = $.trim($('input[name="branch"]').val());
                contact.nombre      = $.trim($('input[name="name"]').val());
                contact.correo      = $.trim($('input[name="mail"]').val());
                contact.subject     = $.trim($('input[name="subject"]').val());
                contact.mensaje     = $.trim($('textarea[name="message"]').val());
                var flag            = true;

                $('input.error, textarea.error').removeClass('error');


                //  Valida el correo de la sucursal
                if (!sipirily._validateMinLength(2, contact.sucursal.length)) {
                    $('input[name="branch"]').addClass('error');
                    flag    = false;
                }
                if (!sipirily._validateMail(contact.sucursal)) {
                    $('input[name="branch"]').addClass('error');
                    flag    = false;
                }

                //  Valida el nombre
                if (!sipirily._validateMinLength(2, contact.nombre.length)) {
                    $('input[name="name"]').addClass('error');
                    flag    = false;
                }
                if (!sipirily._validateMaxLength(140, contact.nombre.length)) {
                    $('input[name="name"]').addClass('error');
                    flag    = false;
                }

                //  Valida el correo
                if (!sipirily._validateMinLength(2, contact.correo.length)) {
                    $('input[name="mail"]').addClass('error');
                    flag    = false;
                }
                if (!sipirily._validateMail(contact.correo)) {
                    $('input[name="mail"]').addClass('error');
                    flag    = false;
                }

                //  Valida el subject
                if (!sipirily._validateMinLength(2, contact.subject.length)) {
                    $('input[name="subject"]').addClass('error');
                    flag    = false;
                }
                if (!sipirily._validateMaxLength(140, contact.subject.length)) {
                    $('input[name="subject"]').addClass('error');
                    flag    = false;
                }

                //  Valida que se escriba un mensaje
                if (!sipirily._validateMinLength(8, contact.mensaje.length)) {
                    $('textarea').addClass('error');
                    flag    = false;
                }
                if (!sipirily._validateMaxLength(140, contact.mensaje.length)) {
                    $('textarea').addClass('error');
                    flag    = false;
                }

                if (!flag) {
                    return false;
                }

                var errorFunction   = function() {
                    sipirily.showAlert('fail', 'Error al hacer el envío');
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
                        sipirily.showAlert(_class, _message);
                        _timer  = setTimeout(function() {
                            sipirily.hideAlert(_class);
                            clearTimeout(_timer);
                        }, 5000);
                    }
                };

                sipirily.validateForm('form[name="contact-form"]', contact, errorFunction, successFunction);
            });
        }

        if ($('.branches, .media').exists()) {
            $('ul.tabs').tabs('div.panes > div');

            $('ul.tabs').on( 'click', 'li', function ( e ) {
                e.preventDefault();
                e.stopPropagation();

                $('ul.tabs li').removeClass('active');
                $(e.currentTarget).addClass('active');
            } );
        }

        /**
         *
         *  Anchor the menu when the screen is up to certain Y coord while doing scroll
         *
         */
        //  !Ancla el menú cuando pasa de cierta coordenada Y al hacer scroll
        /*if ($('.wrapper-header').exists()) {
            $(window).on( 'scroll', function() {
                sipirily.anchorMenu( '.wrapper-header', '75', 'fixed');
            });
        }*/
    });
})(jQuery, window, document);