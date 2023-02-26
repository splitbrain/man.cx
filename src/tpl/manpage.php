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
    </ul>
</nav>
<?php $this->end() //TOC?>

<?php echo $mf->getHTML() ?>
