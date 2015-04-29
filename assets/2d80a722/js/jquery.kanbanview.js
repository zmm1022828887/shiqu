/*
 * jQuery kanbanView plugin file.
 * 
 * @author fl <fl@tongda2000.com>
 */


(function($){
    var methods, kanbanSettings = [];
    
    methods = {
        
        init : function(options) {
            var settings = $.extend({
                ajaxUpdate: [],
                ajaxVar: 'ajax',
                pagerClass: 'pager',
                loadingClass: 'loading',
                filterClass: 'filters',
                tableClass: 'items',
                selectableRows: 1
            }, options || {});
            
            settings.tableClass = settings.tableClass.replace(/\s+/g, '.');
            
            return this.each(function(){
                var $kanban = $(this),
                    id = $kanban.attr('id'),
                    pagerSelector = '#' + id + ' .' + settings.pagerClass.replace(/\s+/g, '.') + ' a';
                    
                settings.updateSelector = settings.updateSelector
                    .replace('{page}', pagerSelector);
            
                kanbanSettings[id] = settings;
                
				if (settings.ajaxUpdate.length > 0) {
					$(document).on('click.kanbanView', settings.updateSelector, function () {
						// Check to see if History.js is enabled for our Browser
						if (settings.enableHistory && window.History.enabled) {
							// Ajaxify this link
							var url = $(this).attr('href').split('?'),
								params = $.deparam.querystring('?'+ (url[1] || ''));

							delete params[settings.ajaxVar];
							window.History.pushState(null, document.title, decodeURIComponent($.param.querystring(url[0], params)));
						} else {
							$('#' + id).kanbanView('update', {url: $(this).attr('href')});
						}
						return false;
					});
				}
                
                if(settings.checkbox == true){
                    $(document).on('mouseenter.kanbanView', '#' + id + ' .kanban-card', function () {
                        if(!$(this).hasClass('active'))
                            $(this).prepend('<div class="mark pull-right" style="top:-7px;position:relative;"><input type="checkbox" /></div>');
                        return false;
                    });

                    $(document).on('click.kanbanView', '#' + id + ' input[type="checkbox"]', function () {
                        if(this.checked)
                            $(this).parents('div.kanban-card').addClass('active');
                        else
                            $(this).parents('div.kanban-card').removeClass('active');
                    });

                    $(document).on('mouseleave.kanbanView', '#' + id + ' .kanban-card', function () {
                        if(!$(this).hasClass('active'))
                            $(this).find('.mark').remove();
                        return false;
                    });
                }
            });
        },
                
        getUrl : function() {
            var sUrl = kanbanSettings[this.attr('id')].url;
            return sUrl || this.children('.keys').attr('title');
        },
        
        update : function(options) {
            return this.each(function(){
                var $kanban = $(this),
                    id = $kanban.attr('id'),
                    settings = kanbanSettings[id];
                $kanban.addClass(settings.loadingClass);
                options = $.extend({
                    type:'get',
                    url:$kanban.kanbanView('getUrl'),
                    success:function(data){
                        var $data = $('<div>' + data + '</div>');
						$kanban.removeClass(settings.loadingClass);
						$.each(settings.ajaxUpdate, function (i, el) {
							var updateId = '#' + el;
							$(updateId).replaceWith($(updateId, $data));
						});
                    },
                    error:function(){
                        
                    }
                }, options || {});
                
                if(settings.ajaxUpdate !== false) {
                    $.ajax(options);
                } else {
                    
                }
            });
        }        
    };
    
    $.fn.kanbanView = function(method) {
        if(methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method ) {
            return methods.init.apply(this, arguments);
        }else {
            $.error('Method ' + method + ' does not exist on jQuery.kanbanView');
            return false;
        }
    };
    
	$.fn.kanbanView.update = function (id, options) {
		$('#' + id).kanbanView('update', options);
	};
    
})(jQuery);