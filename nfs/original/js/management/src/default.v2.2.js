/*@cc_on _d=document;eval('var document=_d')@*/

// include
document.write('<script type="text/javascript" src="/js/management/prototype.1.6.0.3/prototype.js"></script>');
document.write('<script type="text/javascript" src="/js/management/prototype.1.6.0.3/scriptaculous.1.8.2/effects.js"></script>');
document.write('<script type="text/javascript" src="/js/management/prototype.1.6.0.3/scriptaculous.1.8.2/dragdrop.js"></script>');

/**
http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.2/prototype.js
http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.1/scriptaculous.js?load=effects,dragdrop
**/


/** @deprecated */
function getCookie(name) { return Cookie.get(name); }
/** @deprecated */
function setCookie(name, value) { Cookie.expireInMonth(name, value); }

/** cookie操作関数 */
var Cookie = {
    /** nameで指定されたcookieがセットされていない場合空文字を返す */
    get: function(name) {
        var match = ('; ' + document.cookie + ';').match('; ' + encodeURIComponent(name) + '=(.*?);');
        return match ? decodeURIComponent(match[1]) : '';
    },
    /** expiresにDateオブジェクトを渡す点以外は、PHPのsetcookieと同じ */
    set: function(name, value, expires, path, domain, secure) {
        var buffer = encodeURIComponent(name) + '=' + encodeURIComponent(value);
        if (typeof expires != 'undefined') buffer += '; expires=' + new Date(expires).toUTCString();
        if (typeof path != 'undefined') buffer += '; path=' + path;
        if (typeof domain != 'undefined') buffer += '; domain=' + domain;
        if (secure) buffer += '; secure';
        document.cookie = buffer;
    },
    /** 1ヶ月後に切れるcookieを発行する */
    expireInMonth: function(name, value) {
        this.set(name, value, (new Date()).getTime() + 1000 * 60 * 60 * 24 * 30,'/');
    }
};

/** @deprecated */
function display(obj){ $(obj).toggle(); }


function openWindow( url ){
  location.href=url;
  //window.open(url);
}

function AllChecked(check, form, ele) {
    if (!form) return;

    var cb = form.elements[ele];
    if (!cb) return;

    if (!cb.length) {
        cb = [cb];
    }
    for (var i = 0 ; i < cb.length ; i++) {
        if (!cb[i].disabled) {
            cb[i].checked = check;
        }
    }
}

var contents_obj;
function SPAW_updateInput(obj) {
    if(obj){
        var ap = obj.getActivePage();
        obj.updatePageInput(ap);
    }
}


// 入力補助全角→半角
function changeHankaku(elm, mode) {
    if (elm.value == '') return;
    //全角数字と／：を半角に置換し、各種記号を半角ハイフンに置換
    var value = elm.value.replace(/[／-：]/g, function(s) {
        return String.fromCharCode(s.charCodeAt(0) - 65248);
    }).replace(/[ー？―‐]/g, '-');

    var test = function(test, message) {
        if (test) elm.value = value; else window.alert(message);
    };

    switch (mode) {
        case 'number':
            test(!isNaN(value), '半角数字で入力してください。');
            break;
        case 'date':
            test(value.match(/^\d{4}(\/\d{1,2}){2}$/), '｢/｣区切りの半角数字で入力してください。\n例:1970/01/01');
            break;
        case 'time':
            test(value.match(/^\d{1,2}(:\d{1,2}){1,2}$/), '｢:｣区切りの半角数字で入力してください。\n例:09:00');
            break;
        case 'tel':
            test(value.match(/^[\d-]+$/), '｢-｣区切りの半角数字で入力してください。\n例:00-0000-0000');
            break;
    }
}

/**
 * サーバからデータ取得する。取得完了したらcallbackを呼び出す
 */
function getModuleData(module_type, contents_type, data_kbn, callback, args) {
    var pars = { module_type: module_type, data_kbn: data_kbn};
    if (contents_type != '') pars.contents_type = contents_type;

    new Ajax.Request('/js/getModuleData.php', {
        method: 'get',
        parameters: $H(pars),
        onComplete: function(x) { callback(eval(x.responseText), args); }
    });
}

/**
 * jQuery風hover関数
 * @param Element elm 対象エレメント
 * @param function f mouseoverイベント
 * @param function g mouceoutイベント
 */
function hover(elm, f, g) {
    var handler = (function(e) {
        switch (e.type) {
            case 'mouseover':
                var p = e.relatedTarget || e.fromElement;
                do if (!p || p == this) return; while (p = p.parentNode);
                return f.call(this, e);
            case 'mouseout':
                var p = e.relatedTarget || e.toElement;
                do if (!p || p == this) return; while (p = p.parentNode);
                return g.call(this, e);
        }
    }).bindAsEventListener(elm)

    return $(elm).observe('mouseover', handler).observe('mouseout', handler);
}

/**
 * ロード前ならonloadイベントに登録し、ロード後ならば実行する
 * @param function callback 実行する関数
 */
function onReady(callback) {
    if (onReady._ready) callback();
    else Event.observe(window, 'load', function() {
        onReady._ready = true;
        callback();
    });
}

// 兄弟Elementと入れ替え
function swapSibling(d, ele) {
    ele = $(ele);
    var target = getTarget(d, ele);
    if (!target) {
        return false;
    }
    if (d < 0) {
        ele.parentNode.insertBefore(ele, target);
    }
    else {
        ele.parentNode.insertBefore(target, ele);
    }
    function getTarget(d, ele) {
        var prop = (d < 0) ? 'previousSibling' : 'nextSibling';
        var sib = ele[prop];
        for (var sib = ele[prop] ; sib != null ; sib = sib[prop]) {
            if (ele.tagName == sib.tagName) {
                return sib;
            }
        }
        return null;
    }
}

var DUI = DUI || {};
DUI.calendar = DUI.calendar || {};
DUI.calendar.PopupWindow = function(cfg) {
    var ycal = new YAHOO.widget.Calendar(cfg.tableId, cfg.boxId, {title: cfg.title || 'aaa', close:true, START_WEEKDAY: 0});
    ycal.buildMonthLabel = function() {
        var pageDate = this.cfg.getProperty(YAHOO.widget.Calendar._DEFAULT_CONFIG.PAGEDATE.key);
        return pageDate.getFullYear() + "\u5E74 " + this.Locale.LOCALE_MONTHS[pageDate.getMonth()];
    };
    ycal.cfg.setProperty("MDY_YEAR_POSITION", 1);
    ycal.cfg.setProperty("MDY_MONTH_POSITION", 2);
    ycal.cfg.setProperty("MDY_DAY_POSITION", 3);
    ycal.cfg.setProperty("MY_YEAR_POSITION", 1);
    ycal.cfg.setProperty("MY_MONTH_POSITION", 2);
    ycal.cfg.setProperty("MONTHS_SHORT",   ["1\u6708", "2\u6708", "3\u6708", "4\u6708", "5\u6708", "6\u6708", "7\u6708", "8\u6708", "9\u6708", "10\u6708", "11\u6708", "12\u6708"]);
    ycal.cfg.setProperty("MONTHS_LONG",    ["1\u6708", "2\u6708", "3\u6708", "4\u6708", "5\u6708", "6\u6708", "7\u6708", "8\u6708", "9\u6708", "10\u6708", "11\u6708", "12\u6708"]);
    ycal.cfg.setProperty("WEEKDAYS_1CHAR", ["\u65E5", "\u6708", "\u706B", "\u6C34", "\u6728", "\u91D1", "\u571F"]);
    ycal.cfg.setProperty("WEEKDAYS_SHORT", ["\u65E5", "\u6708", "\u706B", "\u6C34", "\u6728", "\u91D1", "\u571F"]);
    ycal.selectEvent.subscribe(function(type,args,obj) {
        var dates = args[0];
        var date = dates[0];
        var year = date[0], month = date[1], day = date[2];
        $(cfg.textId).value =  date[0] + "/" + date[1] + "/" + date[2];
        if (cfg.onchange) {
          cfg.onchange();
        }
        ycal.hide();
    });
    ycal.render();
    Event.observe($(cfg.btnId), 'click', function() {
        var s = $(cfg.textId).value;
        var d = new Date(s.replace(/-/g, '/'));
        if (isNaN(d.getTime())) {
            ycal.deselectAll();
        }
        else {
            ycal.select(d);
        }
        var selecteds = ycal.getSelectedDates();
        ycal.cfg.setProperty("pagedate", (selecteds.length > 0) ? selecteds[0] : new Date());
        ycal.render();
        ycal.show();
        YAHOO.util.Dom.setXY(cfg.boxId, YAHOO.util.Dom.getXY(cfg.btnId));
    });
}
DUI.i18n = {
  Switcher: function(opt) {
    var obj = {
      init: function(opt) {
        this.box = $('languageSwitcher');
        if (!this.box) {
          this.box = document.createElement('div');
          document.body.appendChild(this.box);
        }
        Element.addClassName(this.box, 'languageSwitcher');
        Element.hide(this.box);
        var ol = document.createElement('ol');
        this.box.appendChild(ol);
        var me = this;
        for (var i in opt.languages) {
          var li = document.createElement('li');
          li.lang = i.replace('_', '-');
          li.appendChild(document.createTextNode(opt.languages[i]));
          ol.appendChild(li);
          Event.observe(li, 'click', function(e) {
            var item = Event.element(e); 
            DUI.i18n.setLanguage(item.getAttribute('lang'));
            me.hide();
            if (opt.changed) {
              opt.changed();
            }
          });
        }
      },
      show: function() {
        var cur = DUI.i18n.getLanguage();
        var items = this.box.getElementsByTagName('li');
        for (var i = 0 ; i < items.length ; i++) {
          var it = items[i];
          if (it.getAttribute('lang') == cur) {
            Element.addClassName(it, 'selected');
          }
          else {
            Element.removeClassName(it, 'selected');
          }
        }
        Element.show(this.box);
      },
      hide: function() {
        Element.hide(this.box);
      }
    };
    obj.init(opt);
    return obj;
  },
  getLanguage: function() {
    return Cookie.get('_lang');
  },
  setLanguage: function(lang) {
    Cookie.set('_lang', lang, new Date().getTime() + 30*24*60*60*1000, '/');
  }
};
