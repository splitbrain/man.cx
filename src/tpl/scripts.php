<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-83791-7"></script>
<script>
    // google analytics
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-83791-7', { 'anonymize_ip': true });

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
