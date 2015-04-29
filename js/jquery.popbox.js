(function($) {

    var current = null;
    $.fn.popBox = function(options)
    {
        var settings = $.extend(true, {}, $.fn.popBox.defaults, options);
        return $(this).each(function() {
            var o = new $.popBox($(this), settings);
            o.init();
        });
    };

    $.popBox = function(el, settings) {

        var me = this;
        this.el = el;
        this.settings = settings;

        this.init = function() {
            el.hover(
                    function() {
                        settings.popBox.trigger.name = setTimeout(function() {
                            clearTimeout(settings.popBox.show.name);
                            me.build();
                        },
                                settings.popBox.trigger.time
                                );
                    },
                    function() {
                        clearTimeout(settings.popBox.trigger.name);
                        settings.popBox.show.name = me.closeTimer(settings.boxType, settings.popBox.show.time);
                    }
            );
        }

        this.build = function()
        {
            me.box = me.exists() ? $(settings.container).find("div[node-type='popBox'][node-data='" + settings.boxType + "']") : $(settings.template);

            if (settings.boxType) {
                me.box.attr({"node-data": settings.boxType});
            }

            if (settings.contentBoxStyle) {
                me.box.find("div[node-type='inner']").css(settings.contentBoxStyle);
            }

            if (settings.classname) {
                me.box.addClass(settings.classname);
            }

            //标题
            me.box.find("[node-type='content']").find("[node-type='title']").remove();
            if (settings.title) {
                me.box.find("[node-type='content']").prepend('<div class="title" node-type="title"><span node-type="title_content">' + settings.title + '</span></div>');
            }

            //是否允许关闭
            me.box.find("[node-type='content']").find("[node-type='close']").remove();
            if (settings.closeAble == "true") {
                me.box.find("[node-type='content']").prepend('<a href="javascript:void(0);" class="icon-close-2 nllink TP_close" title="关闭" node-type="close"></a>');
            }

            //加载到dom结构
            me.append();

            //设置内容
            me.setContent();

            //绑定事件
            me.bindEvt();
            
            //设置位置
            me.setPos();
            
            me.show(settings.boxType);
        }
        
        this.setContent = function() {
            var self = this;
            var _content = '';
            var selfContentDom = me.box.find("div[node-type='inner']");

            //ajax动态获取数据
            if (settings.ajax.url)
            {
                $.ajax({
                    type: settings.ajax.type,
                    url: settings.ajax.url,
                    data: $.isFunction(settings.ajax.data) ? settings.ajax.data(el) : settings.ajax.data,
                    dataType: settings.ajax.dataType,
                    beforeSend: function() {
                        selfContentDom.html(settings.loadingTmp);
                    },
                    success: function(data) {
                        _content = settings.ajax.dataType == "json" ? settings.dataFormat(data) : data;
                        selfContentDom.html(_content);

                        //增加成功之后的回调函数
                        if (settings.callback) {
                            settings.callback();
                        }

                        //填充内容后重新设置位置
                        me.setPos();
                    },
                    error: function() {
                        selfContentDom.html(settings.errTips);
                    }
                });
            } else {
                selfContentDom.html(settings.content);
                me.setPos();
            }
        }

        this.setPos = function() {

            me.box.show();
            var holderPos = settings.holder.offset();
            var selfPos = el.offset();
            var arrSize = me.box.find("div[node-type='layerArr']");
            var arrSize_top = arrSize_plus = 0;

            //修正内容增高，外层高度计算错误的问题
            var boxContentHeight = me.box.find(".bg").outerHeight(true);
            if (settings.direction == "left" || settings.direction == "right")
            {
                //如果到达最底部，则设置最小边距
                if ((selfPos.top + boxContentHeight + 5) > $(window).height())
                {
                    me.box.css({
                        "bottom": 5 + "px",
                        "top": "auto"
                    });

                    //超过边界值则加上对应的差值
                    arrSize_plus = selfPos.top - me.box.offset().top;
                } else {
                    me.box.css({
                        "top": selfPos.top + "px",
                        "bottom": "auto"
                    });
                }

                if (settings.direction == "left")
                {
                    arrSize.css('left', 'none').addClass('arrow_l');
                    me.box.css("left", selfPos.left + el.outerWidth(true) + 5 + "px");
                } else {
                    arrSize.css('right', 'none').addClass('arrow_r');
                    me.box.css("left", selfPos.left - me.box.outerWidth(true) - 5 + "px");
                }

                arrSize_top = el.outerHeight(true) / 2 - arrSize.height() / 2 + arrSize_plus;

                //如果为负数则设置默认top值
                arrSize.css("top", (arrSize_top > 0 ? arrSize_top : 5) + 'px');

            } else if (settings.direction == "top") {
                me.box.find("div[node-type='layerArr']").addClass('arrow_t');

                //如果到达最右侧，则设置最小边距
                if ((selfPos.left + me.box.outerWidth(true)) > $(window).width())
                {
                    me.box.css({
                        "right": "5px",
                        "left": "none"
                    });
                    arrSize.css({
                        "right": $(window).width() - selfPos.left - el.outerWidth(true) / 2 - arrSize.width() / 2,
                        "left": "none"
                    });
                } else {
                    me.box.css("left", selfPos.left + "px");
                    arrSize.css('left', el.outerWidth(true) / 2 - arrSize.width() / 2);
                }

                me.box.css("top", el.outerHeight(true) + selfPos.top + 5 + "px");
            } else if (settings.direction == "bottom") {
                me.box.find("div[node-type='layerArr']").addClass('arrow_b');

                me.box.css({
                    "top": selfPos.top - me.box.outerHeight(true) - 5 + "px",
                    "left": selfPos.left
                });
                arrSize.css('left', el.outerWidth(true) / 2 - arrSize.width() / 2);
            }
        }

        this.bindEvt = function() {

            //设置鼠标进入事件
            me.box.bind({
                "mouseenter": function() {
                    $(this).attr('status', 'on');
                }
            });

            //设置鼠标离开事件
            if (!settings.blurclose) {
                me.box.bind({
                    "mouseleave": function() {
                        $(this).attr('status', 'off').hide();
                    }
                });
            } else {
                me.box.unbind('mouseleave');
            }

            me.box.find("[node-type='close']").click(function() {
                me.box.hide();
            });
        }

        this.closeTimer = function(n, t) {
            return setTimeout(function() {
                if (me.status(n) != 'on')
                    me.hide(n);
            }, t);
        }

        this.append = function() {
            if (!me.exists())
                $(settings.container).append(me.box);
        }

        this.exists = function() {
            return $(settings.container).find("div[node-type='popBox'][node-data='" + settings.boxType + "']").length > 0;
        }

        this.hide = function(n) {
            $(settings.container).find("div[node-type='popBox'][node-data='" + n + "']").hide();
        }

        this.show = function(n) {
            $(settings.container).find("div[node-type='popBox'][node-data='" + n + "']").attr('status', 'off').css('visibility', 'visible').show();
        }

        this.status = function(n) {
            return $(settings.container).find("div[node-type='popBox'][node-data='" + n + "']").attr('status');
        }
    }

    $.fn.popBox.defaults = {
        popBox: {
            show: {name: null, time: 200},
            trigger: {name: null, time: 500}
        },
        boxType: 'popBox',
        closeAble: false,
        blurclose: false,
        ajax: {
            url: '',
            data: {} || $.noop(),
            type: 'GET',
            dataType: 'json'
        },
        callback: $.noop(),
        contentBoxStyle: {},
        holder: $.noop(),
        direction: 'left',
        container: 'body',
        dataFormat: $.noop(),
        errTips: '加载错误请重试',
        template: '<div class="TP_layer" node-type="popBox"><div class="bg"><table cellspacing="0" cellpadding="0" border="0"><tbody><tr><td><div class="content" node-type="content"><div class="content_inner clearfix" node-type="inner"></div></div></td></tr></tbody></table><div node-type="layerArr" class="arrow"></div></div></div>',
        loadingTmp: '<div class="TP_loading"><span>正在加载，请稍候...</span></div>'
    };

})(jQuery);
