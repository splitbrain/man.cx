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
    <meta name="robots" content="<?php echo $this->e($robots) ?>"/>
    <meta name="verify-v1" content="c4FN8RTU/IsvamiZtYSUOfRl4+hibnML0HgCPDrvLuc="/>
    <link rel="shortcut icon" type="image/png" href="<?php echo $base ?>___/book.png"/>

    <link rel="stylesheet" type="text/css" href="<?php echo $base ?>___/layout.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo $base ?>___/styles.css"/>


    <script language="JavaScript" type="text/javascript">
        function niceurl(base, tform) {
            window.location.href = base + tform.elements.page.value;
            return false;
        }
    </script>
</head>
<body>

<header>
    <h1><a name="top" href="<?php echo $base ?>">Manpages</a></h1>
    <form method="get" accept-charset="utf-8" action="<?php echo $base ?>"
          onsubmit="return niceurl('<?php echo $base ?>',this)">
        <label for="lookup">Manpage:</label>
        <input name="page" id="lookup" type="text"/>
        <input type="submit" value="go" name="do[go]"/>
        <!-- input type="submit" value="search" name="do[search]" /-->
    </form>
</header>

<div class="main">
    <main>
        <?php echo $this->section('content') ?>
    </main>

    <aside>
        <?php echo $this->section('toc') ?>
    </aside>
</div>

<footer>
    A <a href="http://www.splitbrain.org">splitbrain.org</a> Service |

    <a href="#top">Back to top</a>
</footer>


<?php $this->insert('scripts') ?>

</body>
</html>
