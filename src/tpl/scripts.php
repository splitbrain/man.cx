<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-83791-7"></script>
<script>
    // google analytics
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-83791-7', { 'anonymize_ip': true });

    // adsense - one push for every ad
    var adcount = document.getElementsByClassName('adsbygoogle').length;
    for (var i = 0; i < adcount; i++) {
        (adsbygoogle = window.adsbygoogle || []).push({});
    }

    // open/close toc content on header click
    var asideheaders = document.querySelectorAll('aside nav > h3');
    Array.prototype.forEach.call(asideheaders, function(el, index, array){
        el.addEventListener('click', function (e) {
            // avoid having to use computed style. http://stackoverflow.com/a/21696585/172068
            if(this.nextElementSibling.offsetParent === null){
                this.nextElementSibling.style.display = 'block';
            } else {
                this.nextElementSibling.style.display = 'none';
            }
        });
    });

</script>
