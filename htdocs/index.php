<?php
require('___inc/functions.php');

# What to do?
$TITLE = 'Linux Manpages Online';
if(isset($_REQUEST['do']['search'])){
    $SEARCH = $_REQUEST['page'];
}else{
    list($MAN,$SEC,$LANG) = parseparams($_REQUEST['page']);
}
if($MAN){
    $TITLE = "Manpage for $MAN";
}




header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html>';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo hsc($TITLE)?> - man.cx manual pages</title>
        <meta name="robots" content="index,follow" />
        <meta name="verify-v1" content="c4FN8RTU/IsvamiZtYSUOfRl4+hibnML0HgCPDrvLuc=" />
        <link rel="shortcut icon" type="image/png" href="/book.png" />
        <link rel="stylesheet" type="text/css" href="/___inc/style.css" />
    </head>
    <body>
    <div id="page">

    <div id="header">
        <?php include('___inc/adsense.php')?>

        <h1><a name="top" href="/">Manpages</a></h1>
        <form method="get" accept-charset="utf-8" action="/">
            <label for="lookup">Manpage:</label>
            <input name="page" id="lookup" type="text" />
            <input type="submit" value="go" name="do[go]" />
            <!-- input type="submit" value="search" name="do[search]" /-->
        </form>
    </div>
    <div class="clearer">&nbsp;</div>
    <?php

        if($MAN){
            p_manpage($MAN,$SEC,$LANG);
        }else{
            p_intro();
        }

    ?>
    </div>
    <div id="footer"><div>
        A <a href="http://www.splitbrain.org">splitbrain.org</a> Service |

        <a href="#top">Back to top</a>
    </div></div>
    </body>
</html>
