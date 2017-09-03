/****  Variables Initiation  ****/
var doc = document;
var docEl = document.documentElement;
var $body = $('body');
var $sidebar = $('.sidebar');
var $sidebarFooter = $('.sidebar .sidebar-footer');
var $mainContent = $('.main-content');
var $pageContent = $('.page-content');
var $topbar = $('.topbar');
var $logopanel = $('.logopanel');
var $sidebarWidth = $(".sidebar").width();
var content = document.querySelector('.page-content');
var $loader = $('#preloader');
var docHeight = $(document).height();
var windowHeight = $(window).height();
var topbarWidth = $('.topbar').width();
var headerLeftWidth = $('.header-left').width();
var headerRightWidth = $('.header-right').width();
var start = delta = end = 0;

$(window).load(function() {
    "use strict";
    setTimeout(function() {
        $('.loader-overlay').addClass('loaded');
        $('body > section').animate({
            opacity: 1,
        }, 100);
    }, 100);
});

/****  Full Screen Toggle  ****/
function toggleFullScreen() {
    if (!doc.fullscreenElement && !doc.msFullscreenElement && !doc.webkitIsFullScreen && !doc.mozFullScreenElement) {
        if (docEl.requestFullscreen) {
            docEl.requestFullscreen();
        } else if (docEl.webkitRequestFullScreen) {
            docEl.webkitRequestFullscreen();
        } else if (docEl.webkitRequestFullScreen) {
            docEl.webkitRequestFullScreen();
        } else if (docEl.msRequestFullscreen) {
            docEl.msRequestFullscreen();
        } else if (docEl.mozRequestFullScreen) {
            docEl.mozRequestFullScreen();
        }
    } else {
        if (doc.exitFullscreen) {
            doc.exitFullscreen();
        } else if (doc.webkitExitFullscreen) {
            doc.webkitExitFullscreen();
        } else if (doc.webkitCancelFullScreen) {
            doc.webkitCancelFullScreen();
        } else if (doc.msExitFullscreen) {
            doc.msExitFullscreen();
        } else if (doc.mozCancelFullScreen) {
            doc.mozCancelFullScreen();
        }
    }
}
$('.toggle_fullscreen').click(function() {
    toggleFullScreen();
});

/* Simulate Ajax call on Panel with reload effect */
function blockUI(item) {
    $(item).block({
        message: '<svg class="circular"><circle class="path" cx="40" cy="40" r="10" fill="none" stroke-width="2" stroke-miterlimit="10"/></svg>',
        css: {
            border: 'none',
            width: '14px',
            backgroundColor: 'none'
        },
        overlayCSS: {
            backgroundColor: '#fff',
            opacity: 0.6,
            cursor: 'wait'
        }
    });
}

function unblockUI(item) {
    $(item).unblock();
}

/**** PANEL ACTIONS ****/
function handlePanelAction() {
    /* Create Portlets Controls automatically: reload, fullscreen, toggle, remove, popout */
    function handlePanelControls() {
        $('.md-panel-controls.panel-init-close').each(function() {
            var controls_html = '<div class="control-btn"><a href="#" class="panel-toggle"><i class="mdi-navigation-expand-more"></i></a></div>';
            $(this).append(controls_html);
            $(this).toggleClass("closed").parents(".panel:first").find(".panel-content").slideToggle();
        });
        // $('.md-panel-controls.panel-init-open').each(function() {
        //     var controls_html = '<div class="control-btn"><a href="#" class="panel-toggle"><i class="mdi-navigation-expand-less"></i></a></div>';
        //     $(this).append(controls_html);
        // });
    }
    handlePanelControls();
    // Toggle Panel Content
    $(document).on("click", ".panel-header .panel-toggle", function(event) {
        event.preventDefault();
        $(this).toggleClass("closed").parents(".panel:first").find(".panel-content").slideToggle();
    });
}

function maximizePanel(){
    if($('.maximized').length){
        var panel = $('.maximized');
        var windowHeight = $(window).height() - 2;
        panelHeight = panel.find('.panel-header').height() + panel.find('.panel-content').height() + 100;
        if(panel.hasClass('maximized')){
            if(windowHeight > panelHeight){
                panel.parent().height(windowHeight);
            } 
            else{
                if($('.main-content').height() > panelHeight) {
                    panel.parent().height($('.main-content').height()); 
                }
                else{
                    panel.parent().height(panelHeight); 
                }
            } 
        }
        else {
            panel.parent().height('');
        }
    }
}

/****  Custom Scrollbar  ****/
/* Create Custom Scroll for elements like Portlets or Dropdown menu */
function customScroll() {
    if ($.fn.mCustomScrollbar) {
        $('.withScroll').each(function() {
            $(this).mCustomScrollbar("destroy");
            var scroll_height = $(this).data('height') ? $(this).data('height') : 'auto';
            var data_padding = $(this).data('padding') ? $(this).data('padding') : 0;
            if ($(this).data('height') == 'window') {
                thisHeight = $(this).height();
                windowHeight = $(window).height() - data_padding - 50;
                if (thisHeight < windowHeight) scroll_height = thisHeight;
                else scroll_height = windowHeight;
            }
            $(this).mCustomScrollbar({
                scrollButtons: {
                    enable: false
                },
                autoHideScrollbar: $(this).hasClass('show-scroll') ? false : true,
                scrollInertia: 150,
                theme: "light",
                set_height: scroll_height,
                advanced: {
                    updateOnContentResize: true
                }
            });
        });
    }
}

/* ==========================================================*/
/* BEGIN SIDEBAR                                             */
/* Sidebar Sortable menu & submenu */
function handleSidebarSortable() {
    $('.menu-settings').on('click', '#reorder-menu', function(e) {
        e.preventDefault();
        $('.nav-sidebar').removeClass('remove-menu');
        $(".nav-sidebar").sortable({
            connectWith: ".nav-sidebar > li",
            handle: "a",
            placeholder: "nav-sidebar-placeholder",
            opacity: 0.5,
            axis: "y",
            dropOnEmpty: true,
            forcePlaceholderSize: true,
            receive: function(event, ui) {
                $("body").trigger("resize")
            }
        });
        /* Sortable children */
        $(".nav-sidebar .children").sortable({
            connectWith: "li",
            handle: "a",
            opacity: 0.5,
            dropOnEmpty: true,
            forcePlaceholderSize: true,
            receive: function(event, ui) {
                $("body").trigger("resize")
            }
        });
        $(this).attr("id", "end-reorder-menu");
        $(this).html('End reorder menu');
        $('.remove-menu').attr("id", "remove-menu").html('Remove menu');
    });
    /* End Sortable Menu Elements*/
    $('.menu-settings').on('click', '#end-reorder-menu', function(e) {
        e.preventDefault();
        $(".nav-sidebar").sortable();
        $(".nav-sidebar").sortable("destroy");
        $(".nav-sidebar .children").sortable().sortable("destroy");
        $(this).attr("id", "remove-menu").html('Reorder menu');
    });
}

/* Sidebar Remove Menu Elements*/
function handleSidebarRemove() {
    /* Remove Menu Elements*/
    $('.menu-settings').on('click', '#remove-menu', function(e) {
        e.preventDefault();
        $(".nav-sidebar").sortable();
        $(".nav-sidebar").sortable("destroy");
        $(".nav-sidebar .children").sortable().sortable("destroy");
        $('.nav-sidebar').removeClass('remove-menu').addClass('remove-menu');
        $(this).attr("id", "end-remove-menu").html('End remove menu');
        $('.reorder-menu').attr("id", "reorder-menu").html('Reorder menu');
    });
    /* End Remove Menu Elements*/
    $('.menu-settings').on('click', '#end-remove-menu', function(e) {
        e.preventDefault();
        $('.nav-sidebar').removeClass('remove-menu');
        $(this).attr("id", "remove-menu").html('Remove menu');
    });
    $('.sidebar').on('click', '.remove-menu > li', function() {
        $menu = $(this);
        if ($(this).hasClass('nav-parent')) $remove_txt = "Are you sure to remove this menu (all submenus will be deleted too)?";
        else $remove_txt = "Are you sure to remove this menu?";
        bootbox.confirm($remove_txt, function(result) {
            if (result === true) {
                $menu.addClass("animated bounceOutLeft");
                window.setTimeout(function() {
                    $menu.remove();
                }, 300);
            }
        });
    });
}

/* Hide User & Search Sidebar */
function handleSidebarHide() {
    hiddenElements = $(':hidden');
    visibleElements = $(':visible');
    $('.menu-settings').on('click', '#hide-top-sidebar', function(e) {
        e.preventDefault();
        var this_text = $(this).text();
        $('.sidebar .sidebar-top').slideToggle(300);
        if (this_text == 'Hide user & search') {
            $(this).text('Show user & search');
        }
    });
    $('.topbar').on('click', '.toggle-sidebar-top', function(e) {
        e.preventDefault();
        $('.sidebar .sidebar-top').slideToggle(300);
        if ($('.toggle-sidebar-top span').hasClass('icon-user-following')) {
            $('.toggle-sidebar-top span').removeClass('icon-user-following').addClass('icon-user-unfollow');
        }
        else {
            $('.toggle-sidebar-top span').removeClass('icon-user-unfollow').addClass('icon-user-following');
        }
    });
}

/* Create custom scroll for sidebar used for fixed sidebar */
function createSideScroll() {
    if ($.fn.mCustomScrollbar) {
        destroySideScroll();
        if (!$('body').hasClass('sidebar-collapsed') && !$('body').hasClass('sidebar-collapsed') && !$('body').hasClass('submenu-hover') && $('body').hasClass('fixed-sidebar')) {
            $('.sidebar-inner').mCustomScrollbar({
                scrollButtons: {
                    enable: false
                },
                autoHideScrollbar: true,
                scrollInertia: 150,
                theme: "light-thin",
                advanced: {
                    updateOnContentResize: true
                }
            });
        }
        if ($('body').hasClass('sidebar-top')) {
            destroySideScroll();
        }
    }
}

/* Destroy sidebar custom scroll */
function destroySideScroll() {
    $('.sidebar-inner').mCustomScrollbar("destroy");
}

/* Toggle submenu open */
function toggleSidebarMenu() {
    // Check if sidebar is collapsed
    if ($('body').hasClass('sidebar-collapsed') || $('body').hasClass('sidebar-top') || $('body').hasClass('submenu-hover'))
        $('.nav-sidebar .children').css({
            display: ''
        });
    else $('.nav-active.active .children').css('display', 'block');
    $('.sidebar').on('click', '.nav-sidebar li.nav-parent > a', function (e) {
        e.preventDefault();
        if ($('body').hasClass('sidebar-collapsed') && !$('body').hasClass('sidebar-hover')) return;
        if ($('body').hasClass('submenu-hover')) return;
        var parent = $(this).parent().parent();
        parent.children('li.active').children('.children').slideUp(200);
        $('.nav-sidebar .arrow').removeClass('active');
        parent.children('li.active').removeClass('active');
        var sub = $(this).next();
        if (sub.is(":visible")) {
            sub.children().addClass('hidden-item')
            $(this).parent().removeClass("active");
            sub.slideUp(200, function () {
                sub.children().removeClass('hidden-item')
            });
        } else {
            $(this).find('.arrow').addClass('active');
            sub.children().addClass('is-hidden');
            setTimeout(function () {
                sub.children().addClass('is-shown');
            }, 0);
            sub.slideDown(200, function () {
                $(this).parent().addClass("active");
                setTimeout(function () {
                    sub.children().removeClass('is-hidden').removeClass('is-shown');
                }, 500);
            });
        }
    });
}

// Add class everytime a mouse pointer hover over it
var hoverTimeout;
$('.nav-sidebar > li').hover(function() {
    clearTimeout(hoverTimeout);
    $(this).siblings().removeClass('nav-hover');
    $(this).addClass('nav-hover');
}, function() {
    var $self = $(this);
    hoverTimeout = setTimeout(function() {
        $self.removeClass('nav-hover');
    }, 200);
});
$('.nav-sidebar > li .children').hover(function() {
    clearTimeout(hoverTimeout);
    $(this).closest('.nav-parent').siblings().removeClass('nav-hover');
    $(this).closest('.nav-parent').addClass('nav-hover');
}, function() {
    var $self = $(this);
    hoverTimeout = setTimeout(function() {
        $(this).closest('.nav-parent').removeClass('nav-hover');
    }, 200);
});
/* END SIDEBAR                                               */
/* ========================================================= */
/* Switch Top navigation to Sidebar */
function reposition_topnav() {
    if ($('.nav-horizontal').length > 0) {
        topbarWidth = $('.topbar').width();
        headerRightWidth = $('.header-right').width();
        if ($('.header-left .nav-horizontal').length) headerLeftWidth = $('.header-left').width() + 40;
        else headerLeftWidth = $('.nav-sidebar.nav-horizontal > li').length * 140;
        var topbarSpace = topbarWidth - headerLeftWidth - headerRightWidth;
        // top navigation move to left nav if not enough space in topbar
        if ($('.nav-horizontal').css('position') == 'relative' || topbarSpace < 0) {
            if ($('.sidebar .nav-sidebar').length == 2) {
                $('.nav-horizontal').insertAfter('.nav-sidebar:eq(1)');
            } else {
                // only add to bottom if .nav-horizontal is not yet in the left panel
                if ($('.sidebar .nav-horizontal').length == 0) {
                    $('.nav-horizontal').appendTo('.sidebar-inner');
                    $('.sidebar-widgets').css('margin-bottom', 20);
                }
            }
            $('.nav-horizontal').css({
                display: 'block'
            }).addClass('nav-sidebar').css('margin-bottom', 100);
            createSideScroll();
            $('.nav-horizontal .children').removeClass('dropdown-menu');
            $('.nav-horizontal > li').each(function() {
                $(this).removeClass('open');
                $(this).find('a').removeAttr('class');
                $(this).find('a').removeAttr('data-toggle');
            });
            /* We hide mega menu in sidebar since video / images are too big and not adapted to sidebar */
            if ($('.nav-horizontal').hasClass('mmenu')) $('.nav-horizontal.mmenu').css('height', 0).css('overflow', 'hidden');
        } else {
            if ($('.sidebar .nav-horizontal').length > 0) {
                $('.sidebar-widgets').css('margin-bottom', 100);
                $('.nav-horizontal').removeClass('nav-sidebar').appendTo('.topnav');
                $('.nav-horizontal .children').addClass('dropdown-menu').removeAttr('style');
                $('.nav-horizontal li:last-child').show();
                $('.nav-horizontal > li > a').each(function() {
                    $(this).parent().removeClass('active');
                    if ($(this).parent().find('.dropdown-menu').length > 0) {
                        $(this).attr('class', 'dropdown-toggle');
                        $(this).attr('data-toggle', 'dropdown');
                    }
                });
            }
            /* If mega menu, we make it visible */
            if ($('.nav-horizontal').hasClass('mmenu')) $('.nav-horizontal.mmenu').css('height', '').css('overflow', '');
        }
    }
}

// Check if sidebar is collapsed
if ($('body').hasClass('sidebar-collapsed')) $('.nav-sidebar .children').css({
    display: ''
});
// Handles form inside of dropdown 
$('.dropdown-menu').find('form').click(function(e) {
    e.stopPropagation();
});
/***** Scroll to top button *****/
function scrollTop() {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 100) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });
    $('.scrollup').click(function() {
        $("html, body").animate({
            scrollTop: 0
        }, 1000);
        return false;
    });
}

function sidebarBehaviour() {
    windowWidth = $(window).width();
    windowHeight = $(window).height() - $('.topbar').height();
    sidebarMenuHeight = $('.nav-sidebar').height();
    if (windowWidth < 1024) {
        $('body').removeClass('sidebar-collapsed');
    }
    if ($('body').hasClass('sidebar-collapsed') && sidebarMenuHeight > windowHeight) {
        $('body').removeClass('fixed-sidebar');
        destroySideScroll();
    }
}

/* Function for datables filter in head */
function stopPropagation(evt) {
    if (evt.stopPropagation !== undefined) {
        evt.stopPropagation();
    } else {
        evt.cancelBubble = true;
    }
}

function detectIE() {
    var ua = window.navigator.userAgent;
    var msie = ua.indexOf('MSIE ');
    var trident = ua.indexOf('Trident/');
    var edge = ua.indexOf('Edge/');
    if (msie > 0 || trident > 0 || edge > 0) {
        $('html').addClass('ie-browser');   
    }
}

/****  Initiation of Main Functions  ****/
$(document).ready(function() {
    createSideScroll();
    toggleSidebarMenu();
    customScroll();
    handleSidebarSortable();
    reposition_topnav();
    handleSidebarRemove();
    handleSidebarHide();
    handlePanelAction();
    scrollTop();
    sidebarBehaviour();
    detectIE();
});

/****  Resize Event Functions  ****/
$(window).resize(function() {
    setTimeout(function() {
        customScroll();
        reposition_topnav();
        if (!$('body').hasClass('fixed-sidebar') && !$('body').hasClass('builder-admin')) sidebarBehaviour();
        maximizePanel();
    }, 100);
});
