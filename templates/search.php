<?php
/** @var \League\Plates\Template\Template $this */
/** @var string $query */
?>
<?php $this->layout('layout') ?>

<h2>Search results for <code><?php echo $this->e($query)?></code></h2>

<div id="results" style="margin-left:11%; margin-top: 1em"></div>

<!-- http://stackoverflow.com/a/20142261/172068 -->
<script>
    function gcseCallback() {
        if (document.readyState !== 'complete')
            return google.setOnLoadCallback(gcseCallback, true);
        google.search.cse.element.render({gname:'gsearch', div:'results', tag:'searchresults-only', attributes:{linkTarget:''}});
        var element = google.search.cse.element.getElement('gsearch');
        element.execute("<?php echo $this->e($query)?>");
    }
    window.__gcse = {
        parsetags: 'explicit',
        callback: gcseCallback
    };
    (function() {
        var cx = '009387785156597221763:sfpn_f0lw3e';
        var gcse = document.createElement('script');
        gcse.type = 'text/javascript';
        gcse.async = true;
        gcse.src = (document.location.protocol === 'https:' ? 'https:' : 'http:') +
            '//www.google.com/cse/cse.js?cx=' + cx;
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(gcse, s);
    })();
</script>


