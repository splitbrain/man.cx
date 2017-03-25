<?php
if(defined('E_DEPRECATED')){ // since php 5.3
    error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
}else{
    error_reporting(E_ALL ^ E_NOTICE);
}

/**
 * Load a list of all versions of the given man page
 */
function listversions($man){
    $sections  = explode(',','1,2,3,4,5,6,7,8,9,n,l,p,o,1x');
    $languages = explode(',',',ar,bg,ca,cf,cs,da,de,el,en,es,et,eu,fi,fr,gc,gl,he,hr,hu,id,it,ja,jp,ko,lt,nb,nl,or,pl,ps,pt,pt_br,py,ro,ru,sh,sk,sr,sv,ta,th,tk,tr,ug,uk,vi,zh,zh_cn,zh_tw');

    $result = array();
    foreach($languages as $lang){
        foreach($sections as $sec){
            if(file_exists("../html/$lang/man$sec/$man.html")){
                $id = "($sec)";
                if($lang) $id .= "/$lang";

                $result[$id] = "../html/$lang/man$sec/$man";
            }
        }
    }
    return $result;
}

/**
 * print the full formatted man page
 */
function p_manpage($man,$sec='',$lang=''){
    $versions = listversions($man);

    if($sec){
        $id = trim("($sec)/$lang",'/');
    }else{
        $id = array_shift(array_keys($versions));
    }

    if(!$versions[$id]){
        echo 'no such man page: '.$man.$id;
        return;
    }

    list($sec) = explode('/',$id);

    echo '<div id="toc">';
    echo '<h3>Available in</h3>';
    echo '<div>';
    foreach(array_keys($versions) as $v){
        echo '<a href="/'.$man.$v.'">'.$v.'</a> ';
    }
    echo '</div>';

    echo '<h3>Contents</h3>';
    echo '<ul>';
    echo file_get_contents($versions[$id].'.toc');
    echo '<li><a href="#comments">COMMENTS</a></li>';
    echo '</ul>';
    echo '</div>';

    echo '<div id="manpage">';
    echo file_get_contents($versions[$id].'.html');

        echo '<h2><a href="#comments" name="comments">COMMENTS</a></h2>';
        echo '<div id="com">';
        echo '<div id="disqus_thread"></div>';
        echo '<script type="text/javascript">';
        echo "var disqus_shortname = 'mancx';";
        echo "var disqus_identifier = '$man$sec';";
        echo "var disqus_url = 'http://man.cx/$man$sec';";
        echo "
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = 'https://' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
        </script>
        <noscript>Please enable JavaScript to view the <a href=\"http://disqus.com/?ref_noscript\">comments powered by Disqus.</a></noscript>
        <a href=\"https://disqus.com\" class=\"dsq-brlink\">blog comments powered by <span class=\"logo-disqus\">Disqus</span></a>";
        echo '</div>';
    echo '</div>';


}

function p_intro(){
    ?>
  <div id="toc">
  </div>

  <div id="manpage">

  <h2>What is man.cx?</h2>

  <p style="margin-left: 11%;">Have you ever wanted to check a manual page for a tool you hadn't installed on
  the current machine? Well, it happend to me various times. There are some
  manpage interfaces available on the net, but they all just provide access to the
  GNU tools or maybe to the tools installed on the host, but they are always missing
  some pages. So I thought, why isn't there a page with all manpages? So I just
  built one.</p>

  <h2>All Manpages?</h2>

  <p style="margin-left: 11%;">Well okay not all, but a lot. In fact, I extracted all the manpages from all
  available packages in the <a href="http://www.debian.org">Debian</a> testing
  distribution, plus some pages from other sources. This makes a total of
  <b>119686 available manpages</b> (including translations).</p>

  <h2>Cool! How do I use it?</h2>

  <p style="margin-left: 11%;">Just enter the name of the page you want in the inputbox at the top of the page.
  You can add a section number (in parentheses) if you want. But you can simply use
  the fast to type URL <i>man.cx/pagename</i> in
  your browser's addressbar.
  </p>

  <h2>It sucks!</h2>

  <p style="margin-left: 11%;">Try your luck at any of these alternative pages:</p>

  <ul style="margin-left: 11%;">
    <li><a href="http://www.freebsd.org/cgi/man.cgi">http://www.freebsd.org/cgi/man.cgi</a></li>
    <li><a href="http://man.linuxquestions.org/">http://man.linuxquestions.org/</a></li>
    <li><a href="http://www.die.net/doc/linux/man/">http://www.die.net/doc/linux/man/</a></li>
    <li><a href="http://man.he.net/">http://man.he.net/</a></li>
  </ul>
  </div>
<?php
}

function hsc($s){
    return htmlspecialchars($s);
}

/**
 * Parse the parameters
 */
function parseparams($query){
    if(empty($query)){
        preg_match('/\?=(.+)/',$_SERVER['REQUEST_URI'],$matches);
        $query = $matches[1];
    }

    $query=trim($query,'/');
    $query=mb_strtolower($query,'utf-8');

    preg_match('/([a-z0-9_\-:\.]+)(\(([0-9nlpo][0-9a-z]*)\))?(\/([a-z_]+))?/',$query,$matches);

    $man  = $matches[1];
    $sec  = $matches[3];
    $lang = $matches[5];

    return array($man,$sec,$lang);
}


