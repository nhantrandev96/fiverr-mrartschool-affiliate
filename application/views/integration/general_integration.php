var _af_url = '<?= base_url() ?>';
var _af_my_url = window.location.host;
var af_script = '<?= $script ?>';

<?php if(false) { ?>
var getQueryString = function ( field, url ) {
	var href = window.location.href;
	var reg = new RegExp( '[?&]' + field + '=([^&#]*)', 'i' );
	var string = reg.exec(href);
	return string ? string[1] : null;
};

function removeQString(key) {
    var urlValue=document.location.href;
    var searchUrl=location.search;
    
    if(key!="") {
        oldValue = getQueryString(key);
        removeVal=key+"="+oldValue;
        if(searchUrl.indexOf('?'+removeVal+'&')!= "-1") {
            urlValue=urlValue.replace('?'+removeVal+'&','?');
        }
        else if(searchUrl.indexOf('&'+removeVal+'&')!= "-1") {
            urlValue=urlValue.replace('&'+removeVal+'&','&');
        }
        else if(searchUrl.indexOf('?'+removeVal)!= "-1") {
            urlValue=urlValue.replace('?'+removeVal,'');
        }
        else if(searchUrl.indexOf('&'+removeVal)!= "-1") {
            urlValue=urlValue.replace('&'+removeVal,'');
        }
    }
    else {
        var searchUrl=location.search;
        urlValue=urlValue.replace(searchUrl,'');
    }
    history.pushState({state:1, rand: Math.random()}, '', urlValue);
}

function setCookie(c, cv, ex) {
    var d = new Date();
    d.setTime(d.getTime() + (ex*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = c + "=" + cv + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

if(getQueryString('af_id')){
	setCookie('af_id',getQueryString('af_id'),365);
    removeQString('af_id');
}

function af_call_api(url,data) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {};
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-type", "application/json");

    
    data['current_page_url'] = btoa(window.location.href);
    data['base_url']    = btoa(_af_my_url);
    data['af_id']       = getCookie("af_id");
    data['script_name'] = af_script;

    var p = Object.keys(data).map(key => key + '=' + data[key]).join('&');
    xhttp.send(p);
}

var AffTracker = {
	productClick: function (product_id) {
        af_call_api(_af_url + 'integration/addClick',{
            "product_id"  : product_id,
        })
    },
    createAction: function (actionCode) {
        af_call_api(_af_url + 'integration/addClick',{
            "actionCode"  : actionCode,
        })
    },
    setWebsiteUrl(url) {
        _af_my_url = url;
    },
    add_order: function (data) {
		af_call_api(_af_url + 'integration/addOrder',{
			"order_id"       : data['order_id'],
            "order_currency" : data['order_currency'],
            "order_total"    : data['order_total'],
            "product_ids"    : data['product_ids'],
		})
	},
    stop_recurring: function (order_id) {
        af_call_api(_af_url + 'integration/stopRecurring',{
            "order_id" : order_id,
        })
    },
    generalClick:function (page_name) {
        af_call_api(_af_url + 'integration/addClick',{
            "page_name"  : page_name,
        })
    },
}
//eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('2 n=5(R,h){2 k=V.f.k;2 N=r X(\'[?&]\'+R+\'=([^&#]*)\',\'i\');2 A=N.Y(k);t A?A[1]:11};5 F(7){2 4=u.f.k;2 9=f.Q;8(7!=""){P=n(7);6=7+"="+P;8(9.j(\'?\'+6+\'&\')!="-1"){4=4.b(\'?\'+6+\'&\',\'?\')}o 8(9.j(\'&\'+6+\'&\')!="-1"){4=4.b(\'&\'+6+\'&\',\'&\')}o 8(9.j(\'?\'+6)!="-1"){4=4.b(\'?\'+6,\'\')}o 8(9.j(\'&\'+6)!="-1"){4=4.b(\'&\'+6,\'\')}}o{2 9=f.Q;4=4.b(9,\'\')}12.19({1a:1,18:16.1d()},\'\',4)}5 G(c,H,D){2 d=r 15();d.14(d.13()+(D*17*C*C*1c));2 y="y="+d.1b();u.E=c+"="+H+";"+y+";10=/"}5 K(I){2 x=I+"=";2 L=Z(u.E);2 v=L.1x(\';\');1A(2 i=0;i<v.B;i++){2 c=v[i];1B(c.1C(0)==\' \'){c=c.J(1)}8(c.j(x)==0){t c.J(x.B,c.B)}}t""}8(n(\'a\')){G(\'a\',n(\'a\'),1e);F(\'a\')}5 g(h,3){2 e=r 1z();e.1y=5(){};e.1D("1H",h,1I);e.1G("1F-1E","1w/1v");3[\'1k\']=M(V.f.k);3[\'1l\']=M(O);3[\'a\']=K("a");3[\'1j\']=1i;2 p=1f.1g(3).1h(7=>7+\'=\'+3[7]).1m(\'&\');e.1n(p)}2 1t={1u:5(s){g(m+\'l/w\',{"s":s,})},1s:5(z){g(m+\'l/w\',{"z":z,})},1r(h){O=h},1o:5(3){g(m+\'l/1p\',{"S":3[\'S\'],"W":3[\'W\'],"U":3[\'U\'],"T":3[\'T\'],})},1q:5(q){g(m+\'l/w\',{"q":q,})},}',62,107,'||var|data|urlValue|function|removeVal|key|if|searchUrl|af_id|replace|||xhttp|location|af_call_api|url||indexOf|href|integration|_af_url|getQueryString|else||page_name|new|product_id|return|document|ca|addClick|name|expires|actionCode|string|length|60|ex|cookie|removeQString|setCookie|cv|cname|substring|getCookie|decodedCookie|btoa|reg|_af_my_url|oldValue|search|field|order_id|product_ids|order_total|window|order_currency|RegExp|exec|decodeURIComponent|path|null|history|getTime|setTime|Date|Math|24|rand|pushState|state|toUTCString|1000|random|365|Object|keys|map|af_script|script_name|current_page_url|base_url|join|send|add_order|addOrder|generalClick|setWebsiteUrl|createAction|AffTracker|productClick|json|application|split|onreadystatechange|XMLHttpRequest|for|while|charAt|open|type|Content|setRequestHeader|POST|true'.split('|'),0,{}))
<?php } ?>

var _0x1a5a=['=([^&#]*)','length','base_url','open','href','application/json','search','random',';path=/','integration/addClick','getTime','script_name','[?&]','expires=','integration/stopRecurring','setRequestHeader','order_total','split','substring','join','map','toUTCString','charAt','integration/addOrder','indexOf','location','af_id','order_id','onreadystatechange','Content-type','current_page_url','cookie','replace'];(function(_0x2f7ca5,_0x44f6fd){var _0x147fe5=function(_0x4021c9){while(--_0x4021c9){_0x2f7ca5['push'](_0x2f7ca5['shift']());}};_0x147fe5(++_0x44f6fd);}(_0x1a5a,0xd0));var _0x2573=function(_0x2f7ca5,_0x44f6fd){_0x2f7ca5=_0x2f7ca5-0x0;var _0x147fe5=_0x1a5a[_0x2f7ca5];return _0x147fe5;};var getQueryString=function(_0x178c6c,_0x38cc51){var _0x581d97=window[_0x2573('0xf')][_0x2573('0x1b')];var _0x270637=new RegExp(_0x2573('0x2')+_0x178c6c+_0x2573('0x17'),'i');var _0x12729d=_0x270637['exec'](_0x581d97);return _0x12729d?_0x12729d[0x1]:null;};function removeQString(_0x3290e3){var _0x2c11ef=document[_0x2573('0xf')][_0x2573('0x1b')];var _0xc9a445=location[_0x2573('0x1d')];if(_0x3290e3!=''){oldValue=getQueryString(_0x3290e3);removeVal=_0x3290e3+'='+oldValue;if(_0xc9a445[_0x2573('0xe')]('?'+removeVal+'&')!='-1'){_0x2c11ef=_0x2c11ef['replace']('?'+removeVal+'&','?');}else if(_0xc9a445[_0x2573('0xe')]('&'+removeVal+'&')!='-1'){_0x2c11ef=_0x2c11ef['replace']('&'+removeVal+'&','&');}else if(_0xc9a445[_0x2573('0xe')]('?'+removeVal)!='-1'){_0x2c11ef=_0x2c11ef[_0x2573('0x16')]('?'+removeVal,'');}else if(_0xc9a445[_0x2573('0xe')]('&'+removeVal)!='-1'){_0x2c11ef=_0x2c11ef[_0x2573('0x16')]('&'+removeVal,'');}}else{var _0xc9a445=location[_0x2573('0x1d')];_0x2c11ef=_0x2c11ef['replace'](_0xc9a445,'');}history['pushState']({'state':0x1,'rand':Math[_0x2573('0x1e')]()},'',_0x2c11ef);}function setCookie(_0x494d56,_0x4bef58,_0x33c123){var _0xad3f5c=new Date();_0xad3f5c['setTime'](_0xad3f5c[_0x2573('0x0')]()+_0x33c123*0x18*0x3c*0x3c*0x3e8);var _0x23b526=_0x2573('0x3')+_0xad3f5c[_0x2573('0xb')]();document[_0x2573('0x15')]=_0x494d56+'='+_0x4bef58+';'+_0x23b526+_0x2573('0x1f');}function getCookie(_0x4181bc){var _0x57b321=_0x4181bc+'=';var _0x40ff6f=decodeURIComponent(document['cookie']);var _0xeffebc=_0x40ff6f[_0x2573('0x7')](';');for(var _0x135e62=0x0;_0x135e62<_0xeffebc[_0x2573('0x18')];_0x135e62++){var _0xadb3cf=_0xeffebc[_0x135e62];while(_0xadb3cf[_0x2573('0xc')](0x0)=='\x20'){_0xadb3cf=_0xadb3cf[_0x2573('0x8')](0x1);}if(_0xadb3cf[_0x2573('0xe')](_0x57b321)==0x0){return _0xadb3cf[_0x2573('0x8')](_0x57b321[_0x2573('0x18')],_0xadb3cf['length']);}}return'';}if(getQueryString(_0x2573('0x10'))){setCookie(_0x2573('0x10'),getQueryString(_0x2573('0x10')),0x16d);removeQString(_0x2573('0x10'));}function af_call_api(_0x41af84,_0xe25c12){var _0x3d8b99=new XMLHttpRequest();_0x3d8b99[_0x2573('0x12')]=function(){};_0x3d8b99[_0x2573('0x1a')]('POST',_0x41af84,!![]);_0x3d8b99[_0x2573('0x5')](_0x2573('0x13'),_0x2573('0x1c'));_0xe25c12[_0x2573('0x14')]=btoa(window[_0x2573('0xf')]['href']);_0xe25c12[_0x2573('0x19')]=btoa(_af_my_url);_0xe25c12[_0x2573('0x10')]=getCookie(_0x2573('0x10'));_0xe25c12[_0x2573('0x1')]=af_script;var _0x3fcc7e=Object['keys'](_0xe25c12)[_0x2573('0xa')](_0x5e3087=>_0x5e3087+'='+_0xe25c12[_0x5e3087])[_0x2573('0x9')]('&');_0x3d8b99['send'](_0x3fcc7e);}var AffTracker={'productClick':function(_0x4749da){af_call_api(_af_url+_0x2573('0x20'),{'product_id':_0x4749da});},'createAction':function(_0x10e4d1){af_call_api(_af_url+_0x2573('0x20'),{'actionCode':_0x10e4d1});},'setWebsiteUrl'(_0x405c6e){_af_my_url=_0x405c6e;},'add_order':function(_0x197ef0){af_call_api(_af_url+_0x2573('0xd'),{'order_id':_0x197ef0[_0x2573('0x11')],'order_currency':_0x197ef0['order_currency'],'order_total':_0x197ef0[_0x2573('0x6')],'product_ids':_0x197ef0['product_ids']});},'stop_recurring':function(_0x44c25e){af_call_api(_af_url+_0x2573('0x4'),{'order_id':_0x44c25e});},'generalClick':function(_0x4f85bc){af_call_api(_af_url+_0x2573('0x20'),{'page_name':_0x4f85bc});}};