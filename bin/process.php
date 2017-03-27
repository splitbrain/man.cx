#!/usr/bin/php
<?php

namespace splitbrain\mancx\bin;

use splitbrain\mancx\main\Config;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

require __DIR__ . '/../vendor/autoload.php';

class Process extends CLI
{

    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    protected function setup(Options $options)
    {
        $options->setHelp('Process the man pages into HTML');
    }

    /**
     * Your main program
     *
     * Arguments and options have been parsed when this is run
     *
     * @param Options $options
     * @return void
     */
    protected function main(Options $options)
    {
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(Config::MANDIR));

        foreach ($iterator as $file => $info) {
            /** @var \SplFileInfo $info */
            if ($info->isDir()) continue;

            // local part without extension
            $local = ltrim(substr($file, strlen(Config::MANDIR)), '/');
            $local = preg_replace('/(\.[^\/]+)*$/', '', $local);

            $htmlfile = Config::HTMLDIR . '/' . $local . '.html';
            $tocfile = Config::HTMLDIR . '/' . $local . '.toc';

            // skip if uptodate
            if(filemtime($file) <= @filemtime($htmlfile)) continue;

            // generate
            $html = $this->processmanpage($file, $local);
            if($html === '') continue;
            $toc = $this->createToc($html);

            // save
            $dir = dirname($htmlfile);
            if(!is_dir($dir)) mkdir($dir, 0777, true);
            file_put_contents($htmlfile, $html);
            file_put_contents($tocfile, $toc);
            touch($htmlfile, filemtime($file));
            touch($tocfile, filemtime($file));
            $this->success("Saved $htmlfile");
        }
    }

    /**
     * Create the HTML version of the man page
     *
     * @param string $file full file name to the man page
     * @param string $local the local part without extension
     * @return string
     */
    function processmanpage($file, $local)
    {
        $this->info("processing $file");

        $imgdir = Config::IMGDIR . '/' . dirname($local);
        @mkdir($imgdir, 0777, true);

        $cmd = 'LANG=en_US.UTF-8 ' .
            'MANROFFOPT="-P -D' . escapeshellarg($imgdir) . ' -P -I' . escapeshellarg($local) . ' -P -n -P -l -P -i120" ' .
            '/usr/bin/timeout 60 /usr/bin/man -E UTF-8 --nj --html=/bin/cat -l ' .
            escapeshellarg($file) . ' 2>/dev/null';
        $text = shell_exec($cmd);

        if(trim($text) === '') {
            $this->error("Failed to run $cmd");
            return '';
        }

        $p = array(
            # header & footer
            '/^.*?(<hr>)/s',
            '/(<hr>).*?$/s',

            # headlines
            '#<h2>(.*?)<a name="(heading\d+)"></a>\s*</h2>#s',

            # images
            '/<img src="' . preg_quote(Config::IMGDIR, '/') . '/',

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
            '<img src="' . Config::IMGBASE,

            # links
            "\\3\\4(\\7)\\9",
            "\\1<a href=\"/\\2(\\3)\">\\2(\\3)</a>",
            "\\1<a href=\"/\\2(\\3)\"><strong>\\2</strong>(\\3)</a>",
            "<a href=\"mailto:\\2 [AT] \\3\\4\">\\2 <em>[AT]</em> \\3\\4</a>",
            "<a href=\"\\1\">\\1</a>"
        );
        $text = preg_replace($p, $r, $text);
        return $text;
    }

    /**
     * Create the HTML TOC for the given html
     *
     * @param string $html
     * @return string
     */
    public function createToc($html)
    {
        # create TOC
        preg_match_all('!<h2><a href="#(heading\d+)" name="(heading\d+)">(.*?)</a></h2>!s',
            $html, $m, PREG_SET_ORDER);
        $toc = '';
        foreach ($m as $r) {
            $toc .= '<li><a href="#' . $r[1] . '">' . trim($r[3]) . '</a></li>'."\n";
        }
        return $toc;
    }
}

$process = new Process();
$process->run();