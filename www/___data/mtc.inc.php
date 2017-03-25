<?

    require_once('mtc.class.php');
    $MTC = new MTC();
    $MTC->notify = 'andi@splitbrain.org';
    $MTC->self = $ME.'/___data/mtc.class.php';
    $MTC->adminpass = 'hoppel';
    $MTC->gravopts  = '&amp;rating=R&amp;size=25';
    $MTC->addcss = false;
    $MTC->init();


?>
