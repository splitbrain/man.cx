<?php

$CONF['man']  = dirname(__FILE__).'/man';
$CONF['html'] = dirname(__FILE__).'/html';
$CONF['img']  = dirname(__FILE__).'/htdocs/___img'; #image directory
$CONF['himg'] = '/___img'; #image url path


traversedir();
#processmanpage('man1','man.1.gz');


function traversedir($dir=''){
    global $CONF;
    $base = $CONF['man'];

    $dh = opendir("$base/$dir");
    if(!$dh){
        echo "ERROR can't read $base/$dir\n";
        return;
    }

    while (($file = readdir($dh)) !== false) {
        if($file{0} == '.') continue;
        if(is_dir("$base/$dir/$file")){
            traversedir("$dir/$file");
            continue;
        }

        processmanpage(ltrim($dir,'/'),$file);
    }
    closedir($dh);
}


function processmanpage($dir,$file){
    global $CONF;
    $man  = $CONF['man'];
    $base = preg_replace('/\..*$/','',$file);


    $img  = $CONF['img']."/$dir";
    @mkdir($img,0777,true);

    $cmd  = 'LANG=en_US.UTF-8 '.
            'MANROFFOPT="-P -D'.escapeshellarg($img).' -P -I'.escapeshellarg($base).' -P -n -P -l -P -i120" '.
            '/usr/bin/man -E UTF-8 --nj --html=/bin/cat -l '.
            escapeshellarg("$man/$dir/$file").' 2>/dev/null';
    $text = shell_exec($cmd);

    $p = array(
        # header & footer
        '/^.*?(<hr>)/s',
        '/(<hr>).*?$/s',

        # headlines
        '#<h2>(.*?)<a name="(heading\d+)"></a>\s*</h2>#s',

        # images
        '/<img src="'.preg_quote($CONF['img'],'/').'/',

        # links
        '/((<.>)|([\s,]))([\w\-\.\+]+)(<\/.>)?\((<.>)?([\dnol]\w*)(<\/.>)?\)(,)?(<\/.>)?/u',
        '/([\s,>])([\w\-\.\+]+)\(([\dnol]\w*)\)/u',
        '/([\s,])<b>([\w\-\.\+]+)<\/b>\(([\dnol]\w*)\)/u',
        '/(([\w\-\.]+)@([\w\-]+)(\.[\w\-]+)+)/u',
        '/([\w]+:\/\/[\w-?&;#~=\.\/\@]+[\w\/])/ui'
    );
    $r = array(
        # header & footer
        '',
        '',

        # headlines
        '<h2><a href="#\\2" name="\\2">\\1</a></h2>',

        # images
        '<img src="'.$CONF['himg'],

        # links
        "\\3\\4(\\7)\\9",
        "\\1<a href=\"/\\2(\\3)\">\\2(\\3)</a>",
        "\\1<a href=\"/\\2(\\3)\"><strong>\\2</strong>(\\3)</a>",
        "<a href=\"mailto:\\2 [AT] \\3\\4\">\\2 <em>[AT]</em> \\3\\4</a>",
        "<a href=\"\\1\">\\1</a>"
    );
    $text = preg_replace($p,$r,$text);

    # save raw html
    $out = $CONF['html']."/$dir";
    @mkdir($out,0777,true);
    file_put_contents("$out/$base.html",$text);

    # create TOC
    preg_match_all('!<h2><a href="#(heading\d+)" name="(heading\d+)">(.*?)</a></h2>!s',
                   $text,$m,PREG_SET_ORDER);
    $toc = '';
    foreach($m as $r){
        $toc .= '<li><a href="#'.$r[1].'">'.trim($r[3]).'</a></li>';
    }
    file_put_contents("$out/$base.toc",$toc);

    echo "$dir/$file processed\n";
}
