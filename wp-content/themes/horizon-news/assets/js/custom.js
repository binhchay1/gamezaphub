jQuery(function ($) {
    /* -----------------------------------------
    Navigation
    ----------------------------------------- */
    $('.menu-toggle').click(function () {
        $(this).toggleClass('open');
    });

    /* -----------------------------------------
    Rtl Check
    ----------------------------------------- */
    $.RtlCheck = function () {
        if ($('body').hasClass("rtl")) {
            return true;
        } else {
            return false;
        }
    }
    $.RtlSidr = function () {
        if ($('body').hasClass("rtl")) {
            return 'right';
        } else {
            return 'left';
        }
    }

    /* -----------------------------------------
    Header Search
    ----------------------------------------- */
    $('.header-search-wrap').find(".search-submit").bind('keydown', function (e) {
        var tabKey = e.keyCode === 9;
        if (tabKey) {
            e.preventDefault();
            $('.header-search-icon').focus();
        }
    });

    $('.header-search-icon').on('keydown', function (e) {
        var tabKey = e.keyCode === 9;
        var shiftKey = e.shiftKey;
        if ($('.header-search-wrap').hasClass('show')) {
            if (shiftKey && tabKey) {
                e.preventDefault();
                $('.header-search-wrap').removeClass('show');
                $('.header-search-icon').focus();
            }
        }
    });

    /* -----------------------------------------
    Keyboard Navigation
    ----------------------------------------- */
    $(window).on('load resize', function () {
        if ($(window).width() < 992 && $(window).width() >= 768) {
            $('.main-navigation').find("a").unbind('keydown');
            $('.main-navigation').find("li").last().bind('keydown', function (e) {
                if (e.which === 9) {
                    e.preventDefault();
                    $('#masthead').find('.menu-toggle').focus();
                }
            });
        } else if ($(window).width() < 768) {
            $('.main-navigation').find("li").unbind('keydown');
            $('.main-navigation').find("a").last().bind('keydown', function (e) {
                if (e.which === 9) {
                    e.preventDefault();
                    $('#masthead').find('.menu-toggle').focus();
                }
            });
        } else {
            $('.main-navigation').find("li").unbind('keydown');
            $('.main-navigation').find("a").unbind('keydown');
        }
    });

    var primary_menu_toggle = $('#masthead .menu-toggle');
    primary_menu_toggle.on('keydown', function (e) {
        var tabKey = e.keyCode === 9;
        var shiftKey = e.shiftKey;

        if (primary_menu_toggle.hasClass('open')) {
            if (shiftKey && tabKey) {
                e.preventDefault();
                $('.main-navigation').toggleClass('toggled');
                primary_menu_toggle.removeClass('open');
            };
        }
    });

    // sub menu button
    $('#masthead .main-navigation div.menu-primary-menu-container > ul .sub-menu')
        .parent('li')
        .find('> a')
        .append('<button class="ascendoor-mobile-dropdown fa fa-angle-down" aria-expanded="false"></button>');

    $('#masthead .main-navigation').on('click', '.ascendoor-mobile-dropdown', function (e) {
        e.preventDefault();
        var $this = $(this);
        var $subMenu = $this.closest('li').find('> .sub-menu');

        // Toggle submenu visibility
        var isActive = $this.hasClass('active');
        $this.toggleClass('active');
        $this.attr('aria-expanded', !isActive);
        $subMenu.slideToggle();
    });

    // Keyboard navigation for sub-menus
    $('#masthead .main-navigation').on('keydown', '.ascendoor-mobile-dropdown', function (e) {
        var $this = $(this);
        var $parentLi = $this.closest('li');
        var $subMenu = $parentLi.find('> .sub-menu');
        var $firstItem = $subMenu.find('a').first();
        var $lastItem = $subMenu.find('a').last();

        if (e.key === 'Enter') {
            // Toggle submenu with Enter key
            e.preventDefault();
            var isActive = $this.hasClass('active');
            $this.toggleClass('active');
            $this.attr('aria-expanded', !isActive);
            $subMenu.slideToggle();
            if (!isActive) {
                $firstItem.focus(); // Focus on the first submenu item when opening
            }
        } else if ($this.hasClass('active')) {
            // Trap focus within the submenu when the button is active
            if (e.key === 'Tab' && !e.shiftKey && document.activeElement === $lastItem[0]) {
                // Focus back to the button when Tab key is pressed on the last item
                e.preventDefault();
                $this.focus();
            } else if (e.key === 'Tab' && e.shiftKey && document.activeElement === $this[0]) {
                // Close the submenu and move focus to the parent link when Shift+Tab is pressed on the button
                e.preventDefault();
                $this.removeClass('active');
                $this.attr('aria-expanded', 'false');
                $subMenu.slideUp();
                $parentLi.find('> a').focus();
            }
        }
    });

    // sub menu button

    $('.header-search-wrap').find(".search-submit").bind('keydown', function (e) {
        var tabKey = e.keyCode === 9;
        if (tabKey) {
            e.preventDefault();
            $('.header-search-icon').focus();
        }
    });

    $('.header-search-icon').on('keydown', function (e) {
        var tabKey = e.keyCode === 9;
        var shiftKey = e.shiftKey;
        if ($('.header-search-wrap').hasClass('show')) {
            if (shiftKey && tabKey) {
                e.preventDefault();
                $('.header-search-wrap').removeClass('show');
                $('.header-search-icon').focus();
            }
        }
    });

    /* -----------------------------------------
    Search
    ----------------------------------------- */
    var searchWrap = $('.header-search-wrap');
    $(".header-search-icon").click(function (e) {
        e.preventDefault();
        searchWrap.toggleClass("show");
        searchWrap.find('input.search-field').focus();
    });
    $(document).click(function (e) {
        if (!searchWrap.is(e.target) && !searchWrap.has(e.target).length) {
            $(".header-search-wrap").removeClass("show");
        }
    });

    /* -----------------------------------------
    Banner slider  
    ----------------------------------------- */
    $('.banner-section .banner-slider').slick({
        autoplay: false,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        rtl: $.RtlCheck(),
        appendArrows: '.banner-slider-arrows',
        nextArrow: '<button class="fa-solid fa-angle-right slick-next"></button>',
        prevArrow: '<button class="fa-solid fa-angle-left slick-prev"></button>',
    });
    $('.banner-section .trending-wrapper').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        infinite: true,
        loop: true,
        vertical: true,
        verticalSwiping: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        appendArrows: '.banner-trending-arrows',
        nextArrow: '<button class="fa-solid fa-angle-up slick-next"></button>',
        prevArrow: '<button class="fa-solid fa-angle-down slick-prev"></button>',
    });

    /* -----------------------------------------
    Marquee
    ----------------------------------------- */
    $('.marquee').marquee({
        speed: 600,
        gap: 0,
        delayBeforeStart: 0,
        direction: $.RtlSidr(),
        duplicated: true,
        pauseOnHover: true,
        startVisible: true
    });

    /* -----------------------------------------
    Scroll Top
    ----------------------------------------- */
    var scrollToTopBtn = $('.magazine-scroll-to-top');

    $(window).scroll(function () {
        if ($(window).scrollTop() > 400) {
            scrollToTopBtn.addClass('show');
        } else {
            scrollToTopBtn.removeClass('show');
        }
    });

    scrollToTopBtn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '300');
    });

    (function ($) {
        "use strict";

        $(document).ready(function () {
            "use strict";

            //Scroll back to top

            var progressPath = document.querySelector('.progress-wrap .square');
            if (progressPath !== null) {
                var pathLength = progressPath.getTotalLength();
                progressPath.style.transition = progressPath.style.WebkitTransition = 'none';
                progressPath.style.strokeDasharray = pathLength + ' ' + pathLength;
                progressPath.style.strokeDashoffset = pathLength;
                progressPath.getBoundingClientRect();
                progressPath.style.transition = progressPath.style.WebkitTransition = 'stroke-dashoffset 10ms linear';
                var updateProgress = function () {
                    var scroll = $(window).scrollTop();
                    var height = $(document).height() - $(window).height();
                    var progress = pathLength - (scroll * pathLength / height);
                    progressPath.style.strokeDashoffset = progress;
                }
                updateProgress();
                $(window).scroll(updateProgress);
            }
        });

    })(jQuery);

    $('.user-menu').on('click', function (e) {
        e.preventDefault();
        $(this).find('.dropdown-menu').toggle();
    });

    // Ẩn dropdown khi nhấn ra ngoài
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.user-menu').length) {
            $('.dropdown-menu').hide();
        }
    });
});