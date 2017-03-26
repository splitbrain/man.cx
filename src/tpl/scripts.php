<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
    // google analytics
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-83791-7', 'auto');
    ga('send', 'pageview');

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