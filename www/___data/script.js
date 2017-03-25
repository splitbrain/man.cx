function niceurl(base,tform){
    window.location.href=base+tform.elements.page.value;
    return false;
}

function nicesearch(base,telem){
    window.location.href=base+'?q='+telem.form.elements.page.value;
    return false;
}

function niceapropos(base,telem){
    window.location.href=base+'?a='+telem.form.elements.page.value;
    return false;
}

function findPos(obj) {
    var curleft = curtop = 0;
    if (obj.offsetParent) {
        curleft = obj.offsetLeft
        curtop = obj.offsetTop
        while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft
            curtop += obj.offsetTop
        }
    }
    return [curleft,curtop];
}

window.onscroll = function(){
    var toc = document.getElementById('toc');

    if(toc.offsetHeight > (self.innerHeight || document.documentElement.clientHeight)){
        toc.style.height = (self.innerHeight || document.documentElement.clientHeight)+'px';
    }

    var pos = findPos(document.getElementById('manpage'));
    var top = pos[1];

    if( window.XMLHttpRequest ) { // IE 6 doesn't implement position fixed nicely...
        if (document.documentElement.scrollTop > top || self.pageYOffset > top) {
            toc.style.position = 'fixed';
            toc.style.top = '0';
        } else {
            toc.style.position = 'absolute';
            toc.style.top = top+'px';
        }
    }
}
