require.config({
    baseUrl: "/js",
    "paths": {
        "jquery": "//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min",
        'bootstrap': '//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min',
        'jquery-ui': '//code.jquery.com/ui/1.11.1/jquery-ui'
    },
    shim: {
        /* Set bootstrap dependencies (just jQuery) */
        'bootstrap': ['jquery'],
        'jquery-ui': ['bootstrap'] // http://stackoverflow.com/a/15750649
    }
});

require(['jquery', 'utils', 'ajax-api', 'bootstrap', 'jquery-ui'], function($, utils, api) {
    $(function() {
        console.log(window.location.hash);
        $(window.location.hash).addClass('highlight');

        $(window).on('hashchange', function() {
            $('.highlight.line').removeClass('highlight');
            $(window.location.hash).addClass('highlight');
        });

        $('.line-menu-close').click(function() {
            $(this).parents('.line-menu').hide();
        });
        $('.line-menu-show').click(function() {
            $(this).parent().find('.line-menu').show();
        });
        $('.view-level-up').click(function() {
            api.TraceLevelUp($(this).parents('.line-menu').hide().data());
        });
        $('.view-level-down').click(function() {
            api.TraceLevelDown($(this).parents('.line-menu').hide().data());
        });
        $('.view-call-tree').click(function() {
            api.TraceCallTree($(this).parents('.line-menu').hide().data());
        });

        $('body').on('click', '.toggler', function() {
            var self = $(this);
            if (self.hasClass('glyphicon-plus')) {
                self.hasClass('store') && window.localStorage.setItem(self.attr('id'), 1);
                self.parent().parent().children('.sub-list').addClass('open');
                self.removeClass('glyphicon-plus').addClass('glyphicon-minus');
            } else {
                self.hasClass('store') && window.localStorage.removeItem(self.attr('id'));
                self.parent().parent().children('.sub-list').removeClass('open');
                self.removeClass('glyphicon-minus').addClass('glyphicon-plus');
            }
        });
        $('.toggler').each(function() {
            var self = $(this);
            if (window.localStorage.getItem(self.attr('id')) == 1) {
                self.parent().parent().children('.sub-list').addClass('open');
                self.removeClass('glyphicon-plus').addClass('glyphicon-minus');
            }
        });
    });
});