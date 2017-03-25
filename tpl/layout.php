<!DOCTYPE html>
<?php
/** @var \League\Plates\Template\Template $this */
/** @var string $lang */
/** @var string $title */
/** @var string $base */
/** @var string $robots */
?>
<html lang="<?php echo $this->e($lang) ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $this->e($title) ?> - man.cx manual pages</title>
    <meta name="robots" content="<?php echo $this->e($robots)?>"/>
    <meta name="verify-v1" content="c4FN8RTU/IsvamiZtYSUOfRl4+hibnML0HgCPDrvLuc="/>
    <link rel="shortcut icon" type="image/png" href="<?php echo $base ?>___data/book.png"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base ?>___inc/style.css"/>

    <script language="JavaScript" type="text/javascript">
        function niceurl(base, tform) {
            window.location.href = base + tform.elements.page.value;
            return false;
        }
    </script>
</head>
<body>
<div id="page">

    <div id="header">
        <div style="float:right;">
            <!-- adsense -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:728px;height:90px"
                 data-ad-client="ca-pub-5892664235245840"
                 data-ad-slot="7209992921"></ins>
        </div>


        <h1><a name="top" href="<?php echo $base ?>">Manpages</a></h1>
        <form method="get" accept-charset="utf-8" action="<?php echo $base ?>" onsubmit="return niceurl('<?php echo $base ?>',this)">
            <label for="lookup">Manpage:</label>
            <input name="page" id="lookup" type="text"/>
            <input type="submit" value="go" name="do[go]"/>
            <!-- input type="submit" value="search" name="do[search]" /-->
        </form>
    </div>
    <div class="clearer">&nbsp;</div>

    <div id="toc">
        <?php echo $this->section('toc') ?>
    </div>

    <div id="manpage">
        <?php echo $this->section('content') ?>
    </div>
</div>
<div id="footer">
    <div>
        A <a href="http://www.splitbrain.org">splitbrain.org</a> Service |

        <a href="#top">Back to top</a>
    </div>
</div>

<?php $this->insert('scripts') ?>

</body>
</html>
