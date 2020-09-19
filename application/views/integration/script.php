eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('2 7=h(s,j){2 9=j?j:x.b.9;2 v=l z(\'[?&]\'+s+\'=([^&#]*)\',\'i\');2 g=v.y(9);w g?g[1]:A};h r(f){2 0=t.b.9;2 4=b.n;5(f!=""){m=7(f);3=f+"="+m;5(4.a(\'?\'+3+\'&\')!="-1"){0=0.6(\'?\'+3+\'&\',\'?\')}e 5(4.a(\'&\'+3+\'&\')!="-1"){0=0.6(\'&\'+3+\'&\',\'&\')}e 5(4.a(\'?\'+3)!="-1"){0=0.6(\'?\'+3,\'\')}e 5(4.a(\'&\'+3)!="-1"){0=0.6(\'&\'+3,\'\')}}e{2 4=b.n;0=0.6(4,\'\')}I.L({K:1,N:O.B()},\'\',0)}h q(c,p,u){2 d=l P();d.M(d.J()+(u*D*o*o*C));2 k="k="+d.E();t.F=c+"="+p+";"+k+";H=/"}5(7(\'8\')){q(\'8\',7(\'8\'),G);r(\'8\')}',52,52,'urlValue||var|removeVal|searchUrl|if|replace|getQueryString|af_id|href|indexOf|location|||else|key|string|function||url|expires|new|oldValue|search|60|cv|setCookie|removeQString|field|document|ex|reg|return|window|exec|RegExp|null|random|1000|24|toUTCString|cookie|365|path|history|getTime|state|pushState|setTime|rand|Math|Date'.split('|'),0,{}))


<?php if(false){ ?>
var getQueryString = function ( field, url ) {
	var href = url ? url : window.location.href;
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

if(getQueryString('af_id')){
	setCookie('af_id',getQueryString('af_id'),365);
	removeQString('af_id');
}
<?php } ?>