(function ($) {
    "use strict"; // Start of use strict

    /* Main Menu */

    /* ---------------------------------------------
     Resize mega menu
     --------------------------------------------- */
    function fastshop_resizeMegamenu() {
        var window_size = jQuery('body').innerWidth();
        window_size += fastshop_get_scrollbar_width();
        if ( window_size > 1024 ) {
            if ( $('#header-sticky-menu .main-menu-wapper').length > 0 ) {
                var container = $('#header-sticky-menu .main-menu-wapper');
                if ( container != 'undefined' ) {
                    var container_width  = 0;
                    container_width      = container.innerWidth();
                    var container_offset = container.offset();
                    setTimeout(function () {
                        $('.main-menu .item-megamenu').each(function (index, element) {
                            $(element).children('.megamenu').css({'max-width': container_width + 'px'});
                            var sub_menu_width = $(element).children('.megamenu').outerWidth();
                            var item_width     = $(element).outerWidth();
                            $(element).children('.megamenu').css({'left': '-' + (sub_menu_width / 2 - item_width / 2) + 'px'});
                            var container_left  = container_offset.left;
                            var container_right = (container_left + container_width);
                            var item_left       = $(element).offset().left;
                            var overflow_left   = (sub_menu_width / 2 > (item_left - container_left));
                            var overflow_right  = ((sub_menu_width / 2 + item_left) > container_right);
                            if ( overflow_left ) {
                                var left = (item_left - container_left);
                                $(element).children('.megamenu').css({'left': -left + 'px'});
                            }
                            if ( overflow_right && !overflow_left ) {
                                var left = (item_left - container_left);
                                left     = left - ( container_width - sub_menu_width );
                                $(element).children('.megamenu').css({'left': -left + 'px'});
                            }
                        })
                    }, 100);
                }
            }
        }
    }

    function fastshop_get_scrollbar_width() {
        var $inner = jQuery('<div style="width: 100%; height:200px;">test</div>'),
            $outer = jQuery('<div style="width:200px;height:150px; position: absolute; top: 0; left: 0; visibility: hidden; overflow:hidden;"></div>').append($inner),
            inner  = $inner[ 0 ],
            outer  = $outer[ 0 ];
        jQuery('body').append(outer);
        var width1 = inner.offsetWidth;
        $outer.css('overflow', 'scroll');
        var width2 = outer.clientWidth;
        $outer.remove();
        return (width1 - width2);
    }

    /*==============================
     Auto width Vertical menu
     ===============================*/
    function fastshop_auto_width_vertical_menu() {
        setTimeout(function () {
            var full_width = parseInt($('.container').innerWidth()) - 30;
            var menu_width = parseInt($('.verticalmenu-content').actual('width'));

            var w = (full_width - menu_width);
            $('.verticalmenu-content').find('.megamenu').each(function () {
                $(this).css('max-width', w + 'px');
            });
        }, 100)
    }

    function fastshop_show_other_item_vertical_menu() {
        if ( $('.block-nav-category').length > 0 ) {

            $('.block-nav-category').each(function () {
                var all_item   = 0;
                var limit_item = $(this).data('items') - 1;
                all_item       = $(this).find('.vertical-menu>li').length;

                if ( all_item > (limit_item + 1) ) {
                    $(this).addClass('show-button-all');
                }
                $(this).find('.vertical-menu>li').each(function (i) {
                    all_item = all_item + 1;
                    if ( i > limit_item ) {
                        $(this).addClass('link-other');
                    }
                })
            })
        }
    }

    function dropdown_menu(contain) {
        $(contain).each(function () {
            var _main = $(this);
            _main.children('.menu-item.parent').each(function () {

                var curent = $(this).find('.submenu');

                $(this).children('.toggle-submenu').on('click', function () {
                    $(this).parent().children('.submenu').slideToggle(400);
                    _main.find('.submenu').not(curent).slideUp(400);

                    $(this).parent().toggleClass('show-submenu');
                    _main.find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                });

                var next_curent = $(this).find('.submenu');

                next_curent.children('.menu-item.parent').each(function () {

                    var child_curent = $(this).find('.submenu');
                    $(this).children('.toggle-submenu').on('click', function () {
                        $(this).parent().parent().find('.submenu').not(child_curent).slideUp(400);
                        $(this).parent().children('.submenu').slideToggle(400);

                        $(this).parent().parent().find('.menu-item.parent').not($(this).parent()).removeClass('show-submenu');
                        $(this).parent().toggleClass('show-submenu');
                    })
                });
            });
        });
    };

    /* ---------------------------------------------
     Scripts Categories
     --------------------------------------------- */
    $(document).ready(function () {
        $(".widget_product_categories .product-categories").each(function () {
            var _main = $(this);
            _main.find('.cat-parent').each(function () {
                $(this).children('a').after('<span class="carets fa fa-angle-right"></span>');
            });
            _main.children('.cat-parent').each(function () {
                var curent = $(this).find('.children');
                $(this).children('.carets').on('click', function () {
                    $(this).parent().toggleClass('show-sub');
                    $(this).parent().children('.children').slideToggle(400);
                    _main.find('.children').not(curent).slideUp(400);
                    _main.find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                });
                var next_curent = $(this).find('.children');
                next_curent.children('.cat-parent').each(function () {
                    var child_curent = $(this).find('.children');
                    $(this).children('.carets').on('click', function () {
                        $(this).parent().toggleClass('show-sub');
                        $(this).parent().parent().find('.cat-parent').not($(this).parent()).removeClass('show-sub');
                        $(this).parent().parent().find('.children').not(child_curent).slideUp(400);
                        $(this).parent().children('.children').slideToggle(400);
                    })
                });
            });
        });

        dropdown_menu('.block-nav-category .fastshop-nav');
        /*  [ All Categorie ]
         - - - - - - - - - - - - - - - - - - - - */
        $(document).on('click', '.open-cate', function () {
            $(this).closest('.block-nav-category').find('li.link-other').each(function () {
                $(this).slideDown();
            });
            var closetext = $(this).data('closetext');
            $(this).addClass('close-cate').removeClass('open-cate').html(closetext);
            return false;
        });

        /* Close Categorie */
        $(document).on('click', '.close-cate', function () {
            $(this).closest('.block-nav-category').find('li.link-other').each(function () {
                $(this).slideUp();
            });
            var alltext = $(this).data('alltext');
            $(this).addClass('open-cate').removeClass('close-cate').html(alltext);
            return false;
        });

        $(".block-nav-category .block-title").on('click', function () {
            $(this).toggleClass('active');
            $(this).parent().toggleClass('has-open');
            $("body").toggleClass("category-open");
        });

        if ( $('.category-search-option').length > 0 ) {
            $('.category-search-option').chosen();
        }
        /*  [ All Categorie ]
         - - - - - - - - - - - - - - - - - - - - */

        $(document).on('click', ".desktop-navigation", function () {
            $('body').toggleClass('desktop-navigation-open');
        });
        $(document).on('click', ".mobile-navigation", function () {
            $('body').addClass('box-mobile-menu-open');
            return false;
        });
        $("#box-mobile-menu .close-menu, .body-overlay").on('click', function () {
            $('body').removeClass('box-mobile-menu-open');
            return false;
        });
    });

    function fastshop_clone_main_menu() {
        var _winw       = $(window).innerWidth();
        var _clone_menu = $('#header .clone-main-menu');
        var _target     = $('#box-mobile-menu .clone-main-menu');

        if ( _winw <= 1024 ) {
            if ( _clone_menu.length > 0 ) {
                _clone_menu.each(function () {
                    $(this).appendTo('#box-mobile-menu .box-inner');
                });
            }
        } else {
            if ( _target.length > 0 ) {
                _target.each(function () {
                    $(this).appendTo('#header .box-header-nav');
                });
            }
        }
    }

    /* CHECK STICKY */
    function fastshop_sticky_product() {
        if ( $('body.single-product .style-with-sticky').length > 0 ) {
            var _height_header = $('#header-sticky-menu').outerHeight();
            if ( $('body').hasClass('admin-bar') ) {
                _height_header += 32;
            }
            console.log(_height_header);
            $('body.single-product .style-with-sticky').each(function () {
                $(this).find('.summary').sticky({
                    topSpacing: _height_header + 20,
                });
                $(window).resize($(this).find('.summary').sticky('update'));
            })
        }
    };

    function fastshop_stick_check() {
        if ( $('body.single-product .style-with-sticky').length > 0 ) {
            var scrollUp = 0;
            $(window).scroll(function (event) {
                var scrollTop          = $(this).scrollTop();
                var height_single_left = $('.single-left').outerHeight() - $('.summary').outerHeight();
                //Remove summary sticky
                if ( scrollTop > height_single_left / 2 ) {
                    $('.summary').addClass('remove-sticky-detail-half')
                } else {
                    $('.summary').removeClass('remove-sticky-detail-half');
                }
                if ( scrollTop > height_single_left ) {
                    $('.summary').addClass('remove-sticky-detail')
                } else {
                    $('.summary').removeClass('remove-sticky-detail');
                }

                scrollUp = scrollTop;
            })
        }
    };

    /* Main Menu */
    function fastshop_init_carousel() {

        $(".owl-carousel").each(function (index, el) {
            var config     = $(this).data();
            config.navText = [ '<i class="pe-7s-angle-left"></i>', '<i class="pe-7s-angle-right"></i>' ];
            var animateOut = $(this).data('animateout');
            var animateIn  = $(this).data('animatein');
            var slidespeed = $(this).data('slidespeed');

            if ( typeof animateOut != 'undefined' ) {
                config.animateOut = animateOut;
            }
            if ( typeof animateIn != 'undefined' ) {
                config.animateIn = animateIn;
            }
            if ( typeof (slidespeed) != 'undefined' ) {
                config.smartSpeed = slidespeed;
            }
            if ( fastshop_ajax_frontend.fastshop_enable_lazy == 1 ) {
                config.lazyLoad = true;
            }
            if ( $('body').hasClass('rtl') ) {
                config.rtl = true;
            }

            var owl = $(this);
            owl.on('initialized.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i            = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if ( i == 1 ) {
                            $(this).addClass('item-first');
                        }
                        if ( i == total_active ) {
                            $(this).addClass('item-last');
                        }
                    });

                }, 100);
            });
            owl.on('refreshed.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i            = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if ( i == 1 ) {
                            $(this).addClass('item-first');
                        }
                        if ( i == total_active ) {
                            $(this).addClass('item-last');
                        }
                    });

                }, 100);
            });
            owl.on('change.owl.carousel', function (event) {
                var total_active = owl.find('.owl-item.active').length;
                var i            = 0;
                owl.find('.owl-item').removeClass('item-first item-last');
                setTimeout(function () {
                    owl.find('.owl-item.active').each(function () {
                        i++;
                        if ( i == 1 ) {
                            $(this).addClass('item-first');
                        }
                        if ( i == total_active ) {
                            $(this).addClass('item-last');
                        }
                    });

                }, 100);
            });
            owl.owlCarousel(config);

        });
    }

    /* ---------------------------------------------
     COUNTDOWN
     --------------------------------------------- */
    function fastshop_countdown() {
        if ( $('.fastshop-countdown').length > 0 ) {
            var labels = [ 'Years', 'Months', 'Weeks', 'Days', 'Hrs', 'Mins', 'Secs' ];
            var layout = '<span class="box-count day"><span class="number">{dnn}</span> <span class="text">Days</span></span><span class="dot">:</span><span class="box-count hrs"><span class="number">{hnn}</span> <span class="text">Hours</span></span><span class="dot">:</span><span class="box-count min"><span class="number">{mnn}</span> <span class="text">Mins</span></span><span class="dot">:</span><span class="box-count secs"><span class="number">{snn}</span> <span class="text">Secs</span></span>';
            $('.fastshop-countdown').each(function () {
                var austDay = new Date($(this).data('y'), $(this).data('m') - 1, $(this).data('d'), $(this).data('h'), $(this).data('i'), $(this).data('s'));
                $(this).countdown({
                    until: austDay,
                    labels: labels,
                    layout: layout
                });
            });
        }
    };

    /* ---------------------------------------------
     Woocommerce Quantily
     --------------------------------------------- */
    function fastshop_woo_quantily() {
        $('body').on('click', '.quantity .quantity-plus', function () {
            var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                val_qty  = parseInt(obj_qty.val()),
                min_qty  = parseInt(obj_qty.data('min')),
                max_qty  = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty      = val_qty + step_qty;
            if ( max_qty && val_qty > max_qty ) {
                val_qty = max_qty;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });

        $('body').on('click', '.quantity .quantity-minus', function () {
            var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                val_qty  = parseInt(obj_qty.val()),
                min_qty  = parseInt(obj_qty.data('min')),
                max_qty  = parseInt(obj_qty.data('max')),
                step_qty = parseInt(obj_qty.data('step'));
            val_qty      = val_qty - step_qty;
            if ( min_qty && val_qty < min_qty ) {
                val_qty = min_qty;
            }
            if ( !min_qty && val_qty < 0 ) {
                val_qty = 0;
            }
            obj_qty.val(val_qty);
            obj_qty.trigger("change");
            return false;
        });
    }

    function fastshop_init_lazy_load() {
        if ( $("img.lazy").length > 0 ) {
            if ( $(window).width() > 767 ) {
                $("img.lazy").lazyload({
                    effect: "fadeIn",
                });
            } else {
                fastshop_ajax_lazy_load();
            }
        }
    }

    function fastshop_ajax_lazy_load() {
        if ( $('img.lazy').length > 0 ) {
            $('img.lazy').each(function () {
                if ( $(this).data('original') ) {
                    $(this).attr('src', $(this).data('original'));
                }
            });
        }
    }

    /* ---------------------------------------------
     TAB EFFECT
     --------------------------------------------- */
    function fastshop_tab_fade_effect() {
        // effect click
        $(document).on('click', '.fastshop-tabs .tab-link a', function () {
            var tab_id       = $(this).attr('href');
            var tab_animated = $(this).data('animate');

            tab_animated = ( tab_animated == undefined || tab_animated == "" ) ? '' : tab_animated;
            if ( tab_animated == "" ) {
                return false;
            }

            $(tab_id).find('.product-list-owl .owl-item.active, .product-list-grid .product-item').each(function (i) {

                var t     = $(this);
                var style = $(this).attr("style");
                style     = ( style == undefined ) ? '' : style;
                var delay = i * 400;
                t.attr("style", style +
                    ";-webkit-animation-delay:" + delay + "ms;"
                    + "-moz-animation-delay:" + delay + "ms;"
                    + "-o-animation-delay:" + delay + "ms;"
                    + "animation-delay:" + delay + "ms;"
                ).addClass(tab_animated + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function () {
                    t.removeClass(tab_animated + ' animated');
                    t.attr("style", style);
                });
            })
        })
    }

    /* ---------------------------------------------
     Ajax Tab
     --------------------------------------------- */
    $(document).on('click', '[data-ajax="1"]', function () {
        if ( !$(this).hasClass('loaded') ) {
            var t          = $(this);
            var id         = t.data('id');
            var tab_id     = t.attr('href');
            var section_id = t.data('section');

            $(tab_id).closest('.tab-container').append('<div class="cssload-wapper" style="min-height: 300px;position: relative"><div class="cssload-square"><div class="animation-tab"></div></div></div>');
            $(tab_id).closest('.panel-collapse').append('<div class="cssload-wapper" style="min-height: 300px;position: relative"><div class="cssload-square"><div class="animation-tab"></div></div></div>');
            $.ajax({
                type: 'POST',
                url: fastshop_ajax_frontend.ajaxurl,
                data: {
                    action: 'fastshop_ajax_tabs',
                    security: fastshop_ajax_frontend.security,
                    id: id,
                    section_id: section_id,
                },
                success: function (response) {
                    if ( response[ 'success' ] == 'ok' ) {
                        $(tab_id).closest('.tab-container').find('.cssload-wapper').remove();
                        $(tab_id).closest('.panel-collapse').find('.cssload-wapper').remove();
                        $(tab_id).html($(response[ 'html' ]).find('.vc_tta-panel-body').html());
                        t.addClass('loaded');
                    }
                },
                complete: function () {
                    fastshop_countdown();
                    fastshop_better_equal_elems();
                    fastshop_init_carousel();
                    fastshop_ajax_lazy_load();
                    fastshop_tab_fade_effect();
                    hover_product_item();
                }
            });
        }
    });

    function fastshop_google_maps() {
        if ( $('.fastshop-google-maps').length <= 0 ) {
            return;
        }
        $('.fastshop-google-maps').each(function () {
            var $this            = $(this),
                $id              = $this.attr('id'),
                $title_maps      = $this.attr('data-title_maps'),
                $phone           = $this.attr('data-phone'),
                $email           = $this.attr('data-email'),
                $zoom            = parseInt($this.attr('data-zoom')),
                $latitude        = $this.attr('data-latitude'),
                $longitude       = $this.attr('data-longitude'),
                $address         = $this.attr('data-address'),
                $map_type        = $this.attr('data-map-type'),
                $pin_icon        = $this.attr('data-pin-icon'),
                $modify_coloring = $this.attr('data-modify-coloring') === "true" ? true : false,
                $saturation      = $this.attr('data-saturation'),
                $hue             = $this.attr('data-hue'),
                $map_style       = $this.data('map-style'),
                $styles;

            if ( $modify_coloring == true ) {
                var $styles = [
                    {
                        stylers: [
                            {hue: $hue},
                            {invert_lightness: false},
                            {saturation: $saturation},
                            {lightness: 1},
                            {
                                featureType: "landscape.man_made",
                                stylers: [ {
                                    visibility: "on"
                                } ]
                            }
                        ]
                    }, {
                        featureType: 'water',
                        elementType: 'geometry',
                        stylers: [
                            {color: '#46bcec'}
                        ]
                    }
                ];
            }
            var map;
            var bounds     = new google.maps.LatLngBounds();
            var mapOptions = {
                zoom: $zoom,
                panControl: true,
                zoomControl: true,
                mapTypeControl: true,
                scaleControl: true,
                draggable: true,
                scrollwheel: false,
                mapTypeId: google.maps.MapTypeId[ $map_type ],
                styles: $styles
            };

            map = new google.maps.Map(document.getElementById($id), mapOptions);
            map.setTilt(45);

            // Multiple Markers
            var markers           = [];
            var infoWindowContent = [];

            if ( $latitude != '' && $longitude != '' ) {
                markers[ 0 ]           = [ $address, $latitude, $longitude ];
                infoWindowContent[ 0 ] = [ $address ];
            }

            var infoWindow = new google.maps.InfoWindow(), marker, i;

            for ( i = 0; i < markers.length; i++ ) {
                var position = new google.maps.LatLng(markers[ i ][ 1 ], markers[ i ][ 2 ]);
                bounds.extend(position);
                marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    title: markers[ i ][ 0 ],
                    icon: $pin_icon
                });
                if ( $map_style == '1' ) {

                    if ( infoWindowContent[ i ][ 0 ].length > 1 ) {
                        infoWindow.setContent(
                            '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="fastshop-map-info">' +
                            '<h4 class="map-title">' + $title_maps + '</h4>' +
                            '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                            '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' +
                            '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                            '</div>'
                        );
                    }

                    infoWindow.open(map, marker);

                }
                if ( $map_style == '2' ) {
                    google.maps.event.addListener(marker, 'click', (function (marker, i) {
                        return function () {
                            if ( infoWindowContent[ i ][ 0 ].length > 1 ) {
                                infoWindow.setContent(
                                    '<div style="background-color:#fff; padding: 30px 30px 10px 25px; width:290px;line-height: 22px" class="fastshop-map-info">' +
                                    '<h4 class="map-title">' + $title_maps + '</h4>' +
                                    '<div class="map-field"><i class="fa fa-map-marker"></i><span>&nbsp;' + $address + '</span></div>' +
                                    '<div class="map-field"><i class="fa fa-phone"></i><span>&nbsp;' + $phone + '</span></div>' +
                                    '<div class="map-field"><i class="fa fa-envelope"></i><span><a href="mailto:' + $email + '">&nbsp;' + $email + '</a></span></div> ' +
                                    '</div>'
                                );
                            }

                            infoWindow.open(map, marker);
                        }
                    })(marker, i));
                }

                map.fitBounds(bounds);
            }

            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function (event) {
                this.setZoom($zoom);
                google.maps.event.removeListener(boundsListener);
            });
        });
    }

    //EQUAL ELEM
    function fastshop_better_equal_elems() {
        setTimeout(function () {
            $('.equal-container.better-height').each(function () {
                var $this = $(this);
                if ( $this.find('.equal-elem').length ) {
                    $this.find('.equal-elem').css({
                        'height': 'auto'
                    });
                    var elem_height = 0;
                    $this.find('.equal-elem').each(function () {
                        var this_elem_h = $(this).height();
                        if ( elem_height < this_elem_h ) {
                            elem_height = this_elem_h;
                        }
                    });
                    $this.find('.equal-elem').height(elem_height);
                    fastshop_ajax_lazy_load();
                }
            });
        }, 2000);
    }

    /* MOBILE MENU */
    function fastshop_box_mobile_menu() {
        if ( $(window).width() <= 1024 ) {
            var content = $('#box-mobile-menu .clone-main-menu');
            content.each(function () {
                var t = $(this);
                t.addClass('active');
                $(this).find('.toggle-submenu').on('click', function () {
                    t.removeClass('active');
                    var text_next = $(this).prev().text();
                    $('#box-mobile-menu .box-title').html(text_next);
                    t.find('li').removeClass('mobile-active');
                    $(this).parent().addClass('mobile-active');
                    $(this).parent().closest('.submenu').css({
                        'position': 'static',
                        'height': '0',
                    });
                    $('#box-mobile-menu #back-menu').css('display', 'block');
                })
            });
            $('#box-mobile-menu #back-menu').on('click', function () {
                content.find('li.mobile-active').each(function () {
                    content.find('li').removeClass('mobile-active');
                    if ( $(this).parent().hasClass('main-menu') ) {
                        content.addClass('active');
                        $('#box-mobile-menu .box-title').html('MAIN MENU');
                        $('#box-mobile-menu #back-menu').css('display', 'none');
                    } else {
                        content.removeClass('active');
                        $(this).parent().parent().addClass('mobile-active');
                        $(this).parent().css({
                            'position': 'absolute',
                            'height': 'auto',
                        });
                        var text_prev = $(this).parent().parent().children('a').text();
                        $('#box-mobile-menu .box-title').html(text_prev);
                    }
                })
            });
        }
    }

    /* update wishlist count */
    function fastshop_update_wishlist_count() {
        var fastshop_update_wishlist_count = function () {
            $.ajax({
                beforeSend: function () {

                },
                complete: function () {

                },
                data: {
                    action: 'fastshop_update_wishlist_count'
                },
                success: function (data) {
                    //do something
                    $('.block-wishlist .count').text(data);
                },

                url: fastshop_ajax_frontend[ 'ajaxurl' ]
            });
        };

        $('body').on('added_to_wishlist removed_from_wishlist', fastshop_update_wishlist_count);
    }

    /* JAVASCRIPT SHOP PAGE */

    function fastshop_ajax_shop_page() {
        /* ORDER BY */
        $(document).on('submit', '.woocommerce-ordering', function () {
            return false;
        });
        $(document).on('change', '.woocommerce-ordering .orderby', function () {
            var _val = $(this).val();
            var _url = window.location.href;
            var xhttp;

            _url += ( _url.indexOf("?") === -1 ? "?" : "&" ) + "orderby=" + _val;

            $('.main-content').addClass('loading');

            if ( window.XMLHttpRequest )
                xhttp = new XMLHttpRequest();
            else
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xhttp.onreadystatechange = function () {
                if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
                    var $html       = $.parseHTML(xhttp.responseText);
                    var $new_form   = $('.main-content', $html);
                    var $new_form_2 = $('.woocommerce-breadcrumb', $html);

                    $('.main-content').replaceWith($new_form);
                    $('.woocommerce-breadcrumb').replaceWith($new_form_2);

                    $('.shop-perpage .option-perpage,.woocommerce-ordering .orderby').trigger("chosen:updated");
                    $('.main-content').removeClass('loading');
                    fastshop_ajax_lazy_load();
                    fastshop_better_equal_elems();
                }
            };
            xhttp.open("GET", _url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(null);
            return false;
        });

        /* SET PRODUCT PER PAGE */
        $(document).on('change', '.shop-perpage .option-perpage', function () {
            var _mode = $(this).val();
            var _url  = window.location.href;
            var xhttp;

            _url += ( _url.indexOf("?") === -1 ? "?" : "&" ) + 'product_per_page=' + _mode;

            $('.main-content').addClass('loading');

            if ( window.XMLHttpRequest )
                xhttp = new XMLHttpRequest();
            else
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xhttp.onreadystatechange = function () {
                if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
                    var $html       = $.parseHTML(xhttp.responseText);
                    var $new_form   = $('.main-content', $html);
                    var $new_form_2 = $('.woocommerce-breadcrumb', $html);

                    $('.main-content').replaceWith($new_form);
                    $('.woocommerce-breadcrumb').replaceWith($new_form_2);

                    $('.shop-perpage .option-perpage,.woocommerce-ordering .orderby').trigger("chosen:updated");
                    $('.main-content').removeClass('loading');
                    fastshop_ajax_lazy_load();
                    fastshop_better_equal_elems();
                }
            };
            xhttp.open("POST", _url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("fastshop_woo_products_perpage=" + _mode);
            return false;
        });
        /*  VIEW GRID LIST */
        $(document).on('click', '.display-mode', function () {
            var _mode = $(this).data('mode');
            var _url  = window.location.href;
            var xhttp;

            _url += ( _url.indexOf("?") === -1 ? "?" : "&" ) + 'shop_page_layout=' + _mode;

            $(this).addClass('active').siblings().removeClass('active');
            $('.main-content').addClass('loading');

            if ( window.XMLHttpRequest )
                xhttp = new XMLHttpRequest();
            else
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            xhttp.onreadystatechange = function () {
                if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
                    var $html       = $.parseHTML(xhttp.responseText);
                    var $new_form   = $('.main-content', $html);
                    var $new_form_2 = $('.woocommerce-breadcrumb', $html);

                    $('.main-content').replaceWith($new_form);
                    $('.woocommerce-breadcrumb').replaceWith($new_form_2);

                    $('.shop-perpage .option-perpage,.woocommerce-ordering .orderby').trigger("chosen:updated");
                    $('.main-content').removeClass('loading');
                    fastshop_ajax_lazy_load();
                    fastshop_better_equal_elems();
                }
            };
            xhttp.open("POST", _url, true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("shop_display_mode=" + _mode);
            return false;
        });
    }

    /* JAVASCRIPT SHOP PAGE */

    /* AJAX REMOVE */
    $(document).on('click', '.fastshop-mini-cart .mini_cart_item a.remove', function (e) {
        var $this      = $(this);
        var thisItem   = $this.closest('.fastshop-mini-cart');
        var remove_url = $this.attr('href');
        var product_id = $this.attr('data-product_id');

        if ( thisItem.is('.loading') ) {
            return false;
        }

        if ( $.trim(remove_url) !== '' && $.trim(remove_url) !== '#' ) {

            thisItem.addClass('loading');

            var nonce         = fastshop_get_url_var('_wpnonce', remove_url);
            var cart_item_key = fastshop_get_url_var('remove_item', remove_url);

            var data = {
                action: 'fastshop_remove_cart_item_via_ajax',
                product_id: product_id,
                cart_item_key: cart_item_key,
                nonce: nonce
            };

            $.post(fastshop_ajax_frontend[ 'ajaxurl' ], data, function (response) {

                if ( response[ 'err' ] != 'yes' ) {
                    $('.fastshop-mini-cart').html(response[ 'mini_cart_html' ]);
                }
                thisItem.removeClass('loading');

            });

            e.preventDefault();
        }

        return false;

    });

    function fastshop_get_url_var(key, url) {
        var result = new RegExp(key + "=([^&]*)", "i").exec(url);
        return result && result[ 1 ] || "";
    }

    /* SINGLE ADD TO CART */
    $(document).on('click', '.single_add_to_cart_button', function (e) {
        e.preventDefault();
        var _this       = $(this);
        var _product_id = _this.val();
        var _form       = _this.closest('form');
        var _form_data  = _form.serialize();

        if ( _product_id != '' ) {
            var _data = 'add-to-cart=' + _product_id + '&' + _form_data;
        } else {
            var _data = _form_data;
        }
        _this.addClass('loading');
        $.post(wc_add_to_cart_params.wc_ajax_url.toString().replace('wc-ajax=%%endpoint%%', ''), _data, function (response) {
            $(document.body).trigger('wc_fragment_refresh');
            _this.removeClass('loading');
        });
    });

    /* TOGGLE MINI - CART*/
    $(document).mouseup(function (e) {
        var container = $('.shopcart-description');
        $(document).on('click', '.shopcart-dropdown', function () {
            $(this).parent().find('.shopcart-description').toggleClass('open');
            return false;
        });
        if ( !container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0 ) // ... nor a descendant of the container
        {
            container.removeClass('open');
        }
    });

    function fastshop_slick_slider() {
        if ( $('body').hasClass('rtl') ) {
            $('.banner-shop').slick({
                rtl: true,
            });
        } else {
            $('.banner-shop').slick();
        }
        $('.style-standard-horizon .flex-control-thumbs,.style-with-sticky .flex-control-thumbs').each(function () {
            if ( $(this).children().length == 0 ) {
                return;
            }
            var _config = [];

            _config.slidesToShow  = 4;
            _config.infinite      = false;
            _config.focusOnSelect = true;
            _config.prevArrow     = '<span class="pe-7s-angle-left"></span>';
            _config.nextArrow     = '<span class="pe-7s-angle-right"></span>';
            _config.responsive    = [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 3,
                    }
                }
            ];
            if ( $('body').hasClass('rtl') ) {
                _config.rtl = true;
            }

            $(this).slick(_config);
        });
        $('.style-standard-vertical .flex-control-thumbs').each(function () {
            if ( $(this).children().length == 0 ) {
                return;
            }
            var _config = [];

            _config.vertical      = true;
            _config.slidesToShow  = 4;
            _config.infinite      = false;
            _config.focusOnSelect = true;
            _config.prevArrow     = '<span class="pe-7s-angle-up"></span>';
            _config.nextArrow     = '<span class="pe-7s-angle-down"></span>';
            _config.responsive    = [
                {
                    breakpoint: 1025,
                    settings: {
                        vertical: false,
                        slidesToShow: 3,
                        prevArrow: '<span class="pe-7s-angle-left"></span>',
                        nextArrow: '<span class="pe-7s-angle-right"></span>',
                    }
                }
            ];
            if ( $('body').hasClass('rtl') ) {
                _config.rtl = true;
            }

            $(this).slick(_config);
        });
    }

    function fastshop_banner_slide() {
        var _config_main = [];
        var _config_dots = [];

        _config_main.slidesToShow = 1;
        _config_main.fade         = true;
        _config_main.arrows       = false;
        _config_main.infinite     = false;
        if ( $('body').hasClass('rtl') ) {
            _config_main.rtl = true;
        }
        _config_main.asNavFor = '.fastshop-banner .second-slide';
        $('.fastshop-banner .main-slide').slick(_config_main);

        _config_dots.slidesToShow  = 3;
        _config_dots.arrows        = false;
        _config_dots.dots          = true;
        _config_dots.infinite      = false;
        _config_dots.focusOnSelect = true;
        if ( $('body').hasClass('rtl') ) {
            _config_dots.rtl = true;
        }
        _config_dots.asNavFor = '.fastshop-banner .main-slide';
        $('.fastshop-banner .second-slide').slick(_config_dots);
    }

    /* ---------------------------------------------
     Init popup
     --------------------------------------------- */
    function fastshop_init_popup() {
        if ( fastshop_ajax_frontend.fastshop_enable_popup_mobile != 1 ) {
            if ( $(window).width() + fastshop_get_scrollbar_width() < 768 ) {
                return false;
            }
        }
        var disabled_popup_by_user = getCookie('fastshop_disabled_popup_by_user');
        if ( disabled_popup_by_user == 'true' ) {
            return false;
        } else {
            if ( $('body').hasClass('home') && fastshop_ajax_frontend.fastshop_enable_popup == 1 ) {
                setTimeout(function () {
                    $('#popup-newsletter').modal({
                        keyboard: false
                    })
                }, fastshop_ajax_frontend.fastshop_popup_delay_time);

            }
        }
    }

    $(document).on('change', '.fastshop_disabled_popup_by_user', function () {
        if ( $(this).is(":checked") ) {
            setCookie("fastshop_disabled_popup_by_user", 'true', 7);
        } else {
            setCookie("fastshop_disabled_popup_by_user", '', 0);
        }
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires     = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca   = document.cookie.split(';');
        for ( var i = 0; i < ca.length; i++ ) {
            var c = ca[ i ];
            while ( c.charAt(0) == ' ' ) {
                c = c.substring(1);
            }
            if ( c.indexOf(name) == 0 ) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    /* Reinit some important things after ajax */
    $(document).ajaxComplete(function (event, xhr, settings) {

    });

    /* BACK TO TOP */
    $(document).on('click', 'a.backtotop', function () {
        $('html, body').animate({scrollTop: 0}, 800);
    });

    function fastshop_toggle_search_form() {
        $('.form-search.style1 .btn-search-drop').on('click', function () {
            $(this).parent().toggleClass('open');
        })
    }

    function fastshop_sticky_header() {
        if ( fastshop_ajax_frontend.fastshop_enable_sticky_menu == 1 ) {
            var _height = $('#header').outerHeight();
            var _winw   = $(window).innerWidth();
            if ( _winw > 1024 ) {
                if ( $(window).scrollTop() > _height ) {
                    $('#header-sticky-menu').addClass('active');
                } else {
                    $('#header-sticky-menu').removeClass('active');
                }
            }
        }
    }

    function fastshop_masonry() {
        $('.cp-portfolio').each(function () {
            var $masonry    = $(this).find('.portfolio-grid');
            var $layoutMode = 'packery';

            // init Isotope
            var $grid = $masonry.isotope({
                layoutMode: $layoutMode,
                itemSelector: '.item-portfolio',
            });
            $grid.imagesLoaded().always(function () {
                $grid.isotope({
                    itemSelector: '.item-portfolio',
                    layoutMode: $layoutMode,
                    itemPositionDataEnabled: true
                });
            });
        });
    };

    function fastshop_hover_product_item() {
        var _winw = $(window).innerWidth();
        if ( _winw > 1024 ) {
            $('.product-list-owl .product-item.style-1, .product-grid .owl-carousel .product-item.style-1').hover(
                function () {
                    $(this).closest('.owl-stage-outer').css({
                        'padding': '10px 10px 200px',
                        'margin': '-10px -10px -200px',
                    });
                }, function () {
                    $(this).closest('.owl-stage-outer').css({
                        'padding': '0',
                        'margin': '0',
                    });
                }
            );

            $('.product-list-owl .product-item.style-10').hover(
                function () {
                    $(this).closest('.owl-stage-outer').css({
                        'padding': '10px 10px 200px',
                        'margin': '-10px -10px -200px',
                    });
                }, function () {
                    $(this).closest('.owl-stage-outer').css({
                        'padding': '0',
                        'margin': '0',
                    });
                }
            );

            $('.product-list-owl .product-item.style-11').hover(
                function () {
                    $(this).closest('.owl-stage-outer').css({
                        'padding': '10px 10px 200px',
                        'margin': '-10px -10px -200px',
                    });
                }, function () {
                    $(this).closest('.owl-stage-outer').css({
                        'padding': '0',
                        'margin': '0',
                    });
                }
            );
        }
    }

    /* ---------------------------------------------
     Scripts bind
     --------------------------------------------- */

    $(window).bind("load", function () {
        fastshop_better_equal_elems();
        fastshop_masonry();
        fastshop_init_lazy_load();
    });

    /* ---------------------------------------------
     Scripts load
     --------------------------------------------- */

    $(window).load(function () {
        fastshop_clone_main_menu();
        fastshop_box_mobile_menu();
        fastshop_init_carousel();
        fastshop_slick_slider();
    });

    /* ---------------------------------------------
     Scripts resize
     --------------------------------------------- */

    $(window).on("resize", function () {
        fastshop_stick_check();
        fastshop_masonry();
        fastshop_init_carousel();
        fastshop_better_equal_elems();
        fastshop_auto_width_vertical_menu();
        fastshop_resizeMegamenu();
        fastshop_clone_main_menu();
        fastshop_box_mobile_menu();
        fastshop_sticky_header();
    });

    /* ---------------------------------------------
     Scripts scroll
     --------------------------------------------- */

    $(window).scroll(function () {
        fastshop_sticky_header();
        if ( $(window).scrollTop() > 500 ) {
            $('.backtotop').show();
        } else {
            $('.backtotop').hide();
        }
    });

    /* ---------------------------------------------
     Scripts ready
     --------------------------------------------- */
    $(document).ready(function () {
        fastshop_sticky_product();
        fastshop_stick_check();
        fastshop_hover_product_item();
        fastshop_init_popup();
        fastshop_sticky_header();
        fastshop_show_other_item_vertical_menu();
        fastshop_countdown();
        fastshop_woo_quantily();
        fastshop_tab_fade_effect();
        fastshop_google_maps();
        fastshop_resizeMegamenu();
        fastshop_auto_width_vertical_menu();
        fastshop_update_wishlist_count();
        fastshop_ajax_shop_page();
        fastshop_banner_slide();
        fastshop_toggle_search_form();
    });
})
(jQuery); // End of use strict