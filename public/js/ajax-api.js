define(['jquery', 'utils'], function($, utils) {
    var api = {};

    function drawCallHierarchy(node, idPrefix, level)
    {
        level = level || 0;
        var list = '';
        var basename = /^.*\//;
        for (var i in node) {
            list += '<div>';
            var id = idPrefix + utils.hashCode(node[i].file + ':' + node[i].line);
            var href = '/file-trace/view/' + encodeURIComponent(utils.queryParam.trace) +
                '/' + encodeURIComponent(node[i].file) +
                '#line' + encodeURIComponent(node[i].line);
            var children = utils.count(node[i].children);
            list +=
                '<div>' +
                    (children ? '<span id="' + id + '" class="toggler store glyphicon ' + (children == 1 ? 'glyphicon-minus' : 'glyphicon-plus') + '"></span>' : '') +
                    '<a href="' + href + '" title="' + node[i].file + '">' + node[i].file.replace(basename, '') + ':' + node[i].line + '</a> ' + node[i].function +
                    '</div>';
            if (children) {
                list += '<div class="sub-list' + (children == 1 ? ' open' : '') + '">';
                list += drawCallHierarchy(node[i].children, idPrefix, level + 1);
                list += '</div>';
            }
            list += '</div>';
        }
        return list;
    }

    api.TraceLevelUp = function(request)
    {
        $.getJSON('/file-trace/level-up', request, function(data) {
            var idPrefix = utils.hashCode(request.file + ':' + request.line);
            $('<div title="One level up">' +
                '<div class="pre">' + drawCallHierarchy(data, idPrefix) + '</div>' +
                '</div>')
                .dialog({width: '90%'})
                .find('.toggler').each(function() {
                    var self = $(this);
                    if (window.localStorage.getItem(self.attr('id')) == 1) {
                        self.parent().parent().children('.sub-list').addClass('open');
                        self.removeClass('glyphicon-plus').addClass('glyphicon-minus');
                    }
                });
        });
    };

    api.TraceLevelDown = function(request)
    {
        $.getJSON('/file-trace/level-down', request, function(data) {
            var idPrefix = utils.hashCode(request.file + ':' + request.line);
            $('<div title="One level down">' +
                '<div class="pre">' + drawCallHierarchy(data, idPrefix) + '</div>' +
                '</div>')
                .dialog({width: '90%'})
                .find('.toggler').each(function() {
                    var self = $(this);
                    if (window.localStorage.getItem(self.attr('id')) == 1) {
                        self.parent().parent().children('.sub-list').addClass('open');
                        self.removeClass('glyphicon-plus').addClass('glyphicon-minus');
                    }
                });
        });
    };

    api.TraceAllCall = function(request)
    {
        location.href = '/file-trace/calls' +
            '?trace=' + encodeURIComponent(request.trace) +
            '&file=' + encodeURIComponent(request.file) +
            '&line=' + encodeURIComponent(request.line);
    };

    api.TraceCallTree = function(request)
    {
        $.getJSON('/file-trace/call-tree', request, function(data) {
            var idPrefix = utils.hashCode(request.file + ':' + request.line);
            $('<div class="call-hierarchy" title="Here is call hierarchy leading to selected line of code">' +
                '<div class="pull-right"><span class="a expand-all">Expand all</span> <span class="a collapse-all">Collapse all</span></div>' +
                '<div class="pre">' + drawCallHierarchy(data, idPrefix) + '</div>' +
                '</div>')
                .dialog({width: '90%'})
                .find('.toggler').each(function() {
                    var self = $(this);
                    if (window.localStorage.getItem(self.attr('id')) == 1) {
                        self.parent().parent().children('.sub-list').addClass('open');
                        self.removeClass('glyphicon-plus').addClass('glyphicon-minus');
                    }
                });
        });
    };

    return api;
});