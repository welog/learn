!function($) {
    
    var params = {};
    $.Prompt = { 
        Alert: function(msg, callback) {
            generateHtml('ALERT', msg);
            confirmBtn(callback);
        },
        Confirm: function(msg, callback, obj) {
            generateHtml('CONFIRM', msg);
            confirmBtn(callback);
            cancelBtn();
            for (var k in obj) {
                params[k] = obj[k];
            }
        },
        Custom: function(msg, footer, callback) {
            generateHtml('CUSTOM', msg, footer);
            confirmBtn(callback);
        }
    }

    //生成Html
    var generateHtml = function(type, msg, footer) {
        var str = '';
        str += '<div id="zh-mask"></div><div id="zh-modal">';
        str += '<div id="zh-modal_main">' + msg + '</div><div id="zh-modal_footer">';

        if (type === 'ALERT') {
            str += '<a id="zh-confirm" href="javascript:;" style="width:100%;">确定</a>';
        } else if (type === 'CONFIRM') {
            str += '<a id="zh-cancel" href="javascript:;" style="width:50%;">取消</a>';
            str += '<a id="zh-confirm" href="javascript:;" style="width:50%;">确定</a>';
        } else if (type === 'CUSTOM') {
            str += footer;
        }
        str += '</div></div>';

        $('body').append(str);
        generateCss();
    }

    //生成Css
    var generateCss = function() {
        $('#zh-mask').css({
            width: '100%',
            height: '100%',
            filter: 'Alpha(opacity=60)',
            'background-color': 'rgba(0,0,0,.5)',
            position: 'fixed',
            top: '0',
            left: '0',
            'z-index': '100001'
        });

        $('#zh-modal').css({
            width: '240px',
            'background-color': '#fff',
            'box-shadow': '0px 1px 2px #777 ',
            'border-radius': '1px',
            position: 'fixed',
            'z-index': '100001'
        });

        $('#zh-modal_main').css({
            padding: '15px',
            'line-height': '20px',
            'font-size': '14px',
            'min-height': '70px'
        });

        $('#zh-modal_footer').css({
            margin: '0',
            'text-align': 'center',
            'background-color': '#0f9aeb'
        });

        $('#zh-confirm, #zh-cancel').css({
            display: 'inline-block',
            height: '34px',
            'line-height': '34px',
            color: '#fff',
            'background-color': '#0f9aeb',
            'font-size': '15px',
            'text-decoration': 'none'
        });
        
        $('#zh-cancel').css({ 'border-right': '1px solid #70c2f2' });

        var docWidth = document.documentElement.clientWidth;
        var docHeight = document.documentElement.clientHeight;
        var boxWidth = $('#zh-modal').width();
        var boxHeight = $('#zh-modal').height();

        $('#zh-modal').css({
            top: (((docHeight - boxHeight) / 2) - 40) + 'px',
            left: (docWidth - boxWidth) / 2 + 'px'
        });
    }

    //确定按钮事件
    var confirmBtn = function(callback) {
        $('#zh-confirm').click(function() {
            $('#zh-mask, #zh-modal').remove();
            if (typeof callback == 'function') {
                callback(params);
            }
        });
    }

    //取消按钮事件
    var cancelBtn = function() {
        $('#zh-cancel').click(function() {
            $('#zh-mask, #zh-modal').remove();
        });
    }

}(jQuery);
