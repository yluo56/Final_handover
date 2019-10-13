(function (e) {
    "use strict";
    var n = window.TWP_JS || {};
    var iScrollPos = 0;
    var loadType, loadButton, loader, pageNo, loading, morePost, scrollHandling;

    n.mobileMenu = {
        init: function () {
            this.toggleMenu(), this.menuMobile(), this.menuArrow(), this.submenuHover()
        },
        toggleMenu: function () {
            e('#masthead').on('click', '.toggle-menu', function (event) {
                var ethis = e('.main-navigation .menu .menu-mobile');
                if (ethis.css('display') == 'block') {
                    ethis.slideUp('300');
                    e("#masthead").removeClass('menu-active');
                } else {
                    ethis.slideDown('300');
                    e("#masthead").addClass('menu-active');
                }
                e('.ham').toggleClass('exit');
            });
            e('#masthead .main-navigation ').on('click', '.menu-mobile a i', function (event) {
                event.preventDefault();
                var ethis = e(this),
                    eparent = ethis.closest('li'),
                    esub_menu = eparent.find('> .sub-menu');
                if (esub_menu.css('display') == 'none') {
                    esub_menu.slideDown('300');
                    ethis.addClass('active');
                } else {
                    esub_menu.slideUp('300');
                    ethis.removeClass('active');
                }
                return false;
            });
        },
        menuMobile: function () {
            if (e('.main-navigation .menu > ul').length) {
                var ethis = e('.main-navigation .menu > ul'),
                    eparent = ethis.closest('.main-navigation'),
                    pointbreak = eparent.data('epointbreak'),
                    window_width = window.innerWidth;
                if (typeof pointbreak == 'undefined') {
                    pointbreak = 991;
                }
                if (pointbreak >= window_width) {
                    ethis.addClass('menu-mobile').removeClass('menu-desktop');
                    e('.main-navigation .toggle-menu').css('display', 'block');
                } else {
                    ethis.addClass('menu-desktop').removeClass('menu-mobile').css('display', '');
                    e('.main-navigation .toggle-menu').css('display', '');
                }
            }
        },
        menuArrow: function () {
            if (e('#masthead .main-navigation div.menu > ul').length) {
                e('#masthead .main-navigation div.menu > ul .sub-menu').parent('li').find('> a').append('<i class="ion-ios-arrow-down">');
            }
        },

        submenuHover: function () {
            e('.site-header .main-navigation li.mega-menu .sub-cat-list li').on('hover', function () {
                if ( ! e( this ).hasClass( 'current' ) ) {
                    var eposts = e( this ).parents( '.sub-cat-list' ).first().siblings( '.sub-cat-posts' ).first();
                    e( this ).siblings( '.current' ).removeClass( 'current' ).end().addClass( 'current' );
                    eposts.children( '.current' ).removeClass( 'current' );
                    eposts.children( '.' + $( this ).attr( 'data-id' ) ).addClass( 'current' );
                }
            } )
        }
    },

        n.twp_preloader = function () {
            e(window).load(function () {
                e("body").addClass("page-loaded");
            });
        },
        n.twp_matchheight = function () {
            jQuery('.widget-area').theiaStickySidebar({
                additionalMarginTop: 30
            });
        },
        n.DataBackground = function () {
            e('.bg-image').each(function () {
                var src = e(this).children('img').attr('src');
                e(this).css('background-image', 'url(' + src + ')').children('img').hide();
            });
        },

        n.InnerBanner = function () {
            var pageSection = e(".data-bg");
            pageSection.each(function (indx) {
                if (e(this).attr("data-background")) {
                    e(this).css("background-image", "url(" + e(this).data("background") + ")");
                }
            });
        },

        n.TwpSlider = function () {
            e(".twp-slider").each(function () {
                e(this).owlCarousel({
                    loop: (e('.twp-slider').children().length) == 1 ? false : true,
                    autoplay: 5000,
                    nav: true,
                    navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
                    items: 1
                });
            });

            e(".gallery-columns-1").each(function () {
                e(this).owlCarousel({
                    loop: (e('.gallery-columns-1').children().length) == 1 ? false : true,
                    margin: 3,
                    autoplay: 5000,
                    nav: true,
                    navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
                    items: 1
                });
            });

            e(".wp-block-gallery.columns-1").each(function () {
                e(this).owlCarousel({
                    loop: (e('.wp-block-gallery.columns-1').children().length) == 1 ? false : true,
                    margin: 3,
                    autoplay: 5000,
                    nav: true,
                    navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
                    items: 1
                });
            });

            e(".recommendation-slides").owlCarousel({
                loop: (e('.recommendation-slides').children().length) == 1 ? false : true,
                autoplay: 3500,
                nav: true,
                navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
                items: 7,
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    640: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    990: {
                        items: 4
                    },
                    1500: {
                        items: 5
                    }
                }
            });

            e(".insta-slider").owlCarousel({
                loop: (e('.insta-slider').children().length) == 1 ? false : true,
                autoplay: 3500,
                nav: true,
                navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
                items: 7,
                dots: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    500: {
                        items: 2
                    },
                    768: {
                        items: 3
                    },
                    990: {
                        items: 4
                    },
                    1025: {
                        items: 7
                    }
                }
            });
        },

        n.MagnificPopup = function () {
            e('.entry-content .gallery, .widget .gallery, .blocks-gallery-item, .zoom-gallery').each(function () {
                e(this).magnificPopup({
                delegate: 'a',
                type: 'image',
                closeOnContentClick: false,
                closeBtnInside: false,
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                image: {
                    verticalFit: true,
                    titleSrc: function (item) {
                        return item.el.attr('title');
                    }
                },
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true,
                    duration: 300,
                    opener: function (element) {
                        return element.find('img');
                    }
                }
                });
            });
        },


        n.scroll_up = function () {
            e(".scroll-up").on("click", function () {
                e("html, body").animate({
                    scrollTop: 0
                }, 700);
                return false;
            });
        },

        n.show_hide_scroll_top = function () {
            if (e(window).scrollTop() > e(window).height() / 2) {
                e("#recommendation-panel-content").fadeIn(300).css({'opacity': 1});
            } else {
                e("#recommendation-panel-content").fadeOut(300).css({'opacity': 0});
            }
        },

        n.fixed_drawer = function () {
            e('#recommendation-panel-content').each(function(){
                var post_bar = e(this);
                var post_button = e(this).siblings('#recommendation-panel-handle');

                if( post_bar.css('display') != 'none' ){
                    e('html').css({'padding-bottom': 75});
                }

                e(this).on('click', '.recommendation-panel-close', function(){
                    post_bar.slideUp(200, function(){
                        post_button.addClass('rec-panel-active');
                    });
                    e('html').animate({'padding-bottom':0}, 200);
                    e('html').addClass('recommendation-panel-disabled');
                });

                post_button.on('click', function(){
                    post_button.removeClass('rec-panel-active');
                    post_bar.slideDown(200);
                    e('html').animate({'padding-bottom': 75}, 200);
                    e('html').removeClass('recommendation-panel-disabled');
                });
            })
        },

        n.setLoadPostDefaults = function () {
            if(  e('.load-more-posts').length > 0 ){
                loadButton = e('.load-more-posts');
                loader = e('.load-more-posts .ajax-loader');
                loadType = loadButton.attr('data-load-type');
                pageNo = 2;
                loading = false;
                morePost = true;
                scrollHandling = {
                    allow: true,
                    reallow: function() {
                        scrollHandling.allow = true;
                    },
                    delay: 400
                };
            }
        },

        n.fetchPostsOnScroll = function () {
            if(  e('.load-more-posts').length > 0 && 'scroll' === loadType ){
                var iCurScrollPos = e(window).scrollTop();
                if( iCurScrollPos > iScrollPos ){
                    if( ! loading && scrollHandling.allow && morePost ) {
                        scrollHandling.allow = false;
                        setTimeout(scrollHandling.reallow, scrollHandling.delay);
                        var offset = e(loadButton).offset().top - e(window).scrollTop();
                        if( 2000 > offset ) {
                            loading = true;
                            n.ShowPostsAjax(loadType);
                        }
                    }
                }
                iScrollPos = iCurScrollPos;
            }
        },

        n.fetchPostsOnClick = function () {
            if( e('.load-more-posts').length > 0 && 'click' === loadType ){
                e('.load-more-posts a').on('click',function (event) {
                    event.preventDefault();
                    n.ShowPostsAjax(loadType);
                });
            }
        },

        n.ShowPostsAjax = function (loadType) {
            e.ajax({
                type : 'GET',
                url : knightVal.ajaxurl,
                data : {
                    action : 'knight_load_more',
                    nonce: knightVal.nonce,
                    page: pageNo,
                    post_type: knightVal.post_type,
                    search: knightVal.search,
                    cat: knightVal.cat,
                    taxonomy: knightVal.taxonomy,
                    author: knightVal.author,
                    year: knightVal.year,
                    month: knightVal.month,
                    day: knightVal.day
                },
                dataType:'json',
                beforeSend: function() {
                    loader.addClass('ajax-loader-enabled');
                },
                success : function( response ) {
                    loader.removeClass('ajax-loader-enabled');
                    if(response.success){
                        e('.knight-posts-lists').append( response.data.content );

                        pageNo++;
                        loading = false;
                        if(!response.data.more_post){
                            morePost = false;
                            loadButton.fadeOut();
                        }

                        /*For audio and video to work properly after ajax load*/
                        e('video, audio').mediaelementplayer({ alwaysShowControls: true });
                        /**/
                        e(".gallery-columns-1").owlCarousel({
                            loop: (e('.gallery-columns-1').children().length) == 1 ? false : true,
                            margin: 3,
                            autoplay: 5000,
                            nav: true,
                            navText: ["<i class='ion-ios-arrow-left'></i>", "<i class='ion-ios-arrow-right'></i>"],
                            items: 1
                        });
                    }else{
                        loadButton.fadeOut();
                    }
                }
            });
        },

        e(document).ready(function () {
            n.mobileMenu.init(), n.twp_preloader(), n.DataBackground(), n.InnerBanner(), n.TwpSlider(), n.MagnificPopup(), n.fixed_drawer(), n.scroll_up(), n.twp_matchheight(), n.setLoadPostDefaults(), n.fetchPostsOnClick();
        }),
        e(window).scroll(function () {
            n.show_hide_scroll_top(), n.fetchPostsOnScroll();
        }),
        e(window).resize(function () {
            n.mobileMenu.menuMobile();
        })
})(jQuery);