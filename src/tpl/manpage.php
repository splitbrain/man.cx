<?php
/** @var \League\Plates\Template\Template $this */
/** @var string $base */
/** @var string $man */
/** @var string $sec */
/** @var \splitbrain\mancx\main\ManFormat $mf */
?>
<?php $this->layout('layout') ?>


<?php $this->start('toc') ?>
<nav>
    <h3><svg viewBox="3 3 21 21"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
        Available in
    </h3>
    <div>
        <?php
        foreach ($mf->getVersions() as $v) {
            echo '<a href="' . $base . $man . $v . '">' . $v . '</a> ';
        }
        ?>
    </div>
</nav>

<nav>
    <h3><svg viewBox="3 3 21 21"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg>
        Contents
    </h3>
    <ul>
        <?php echo $mf->getTOC() ?>
        <li><a href="#comments">COMMENTS</a></li>
    </ul>
</nav>
<?php $this->end() //TOC?>



<?php echo $mf->getHTML() ?>


<div class="alignfix">
    <!-- adsense -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-5892664235245840"
         data-ad-slot="5556632923"
         data-ad-format="auto"></ins>
</div>


<h2><a href="#comments" name="comments">COMMENTS</a></h2>
<div id="com" class="alignfix">

    <div id="disqus_thread"></div>
    <script>
        var disqus_config = function () {
            this.page.url = 'https://man.cx/<?php echo "$man$sec"?>';
            this.page.identifier = '<?php echo "$man$sec"?>';
        };

        (function () { // DON'T EDIT BELOW THIS LINE
            var d = document, s = d.createElement('script');
            s.src = 'https://mancx.disqus.com/embed.js';
            s.setAttribute('data-timestamp', +new Date());
            (d.head || d.body).appendChild(s);
        })();
    </script>
    <noscript>
        Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by
            Disqus.</a>
    </noscript>

</div>