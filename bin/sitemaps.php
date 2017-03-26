#!/usr/bin/php
<?php

namespace splitbrain\mancx\bin;

use splitbrain\mancx\main\Config;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

require __DIR__ . '/../vendor/autoload.php';

class Sitemaps extends CLI
{
    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    protected function setup(Options $options)
    {
        $options->setHelp('Build the sitemaps');
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
        $this->masterSitemap();
    }

    /**
     * Build the master sitemap index
     */
    protected function masterSitemap()
    {
        $temp = Config::SMAPDIR . '/sitemap.tmp';
        $target = Config::SMAPDIR . '/sitemap.xml.gz';

        $fh = gzopen($temp, 'w');
        if (!$fh) {
            $this->fatal("Could not open $temp for writing");
            return;
        }

        gzwrite($fh, '<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        gzwrite($fh, '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");

        $dirs = glob(Config::HTMLDIR . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $name = 'sitemap_' . basename($dir) . '.xml';
            $this->singleSitemap($dir, $name);

            gzwrite($fh, '  <sitemap>' . "\n");
            gzwrite($fh, '    <loc>' . Config::SMAPBASE . '/' . $name . '.gz' . '</loc>' . "\n");
            gzwrite($fh, '    <lastmod>' . date('c') . '</lastmod>' . "\n");
            gzwrite($fh, '  </sitemap>' . "\n");
        }

        gzwrite($fh, '</sitemapindex>' . "\n");
        gzclose($fh);

        if (rename($temp, $target)) {
            $this->success("wrote $target");
        } else {
            $this->fatal("failed to write $target");
        }
    }

    /**
     * Build a single sitemap
     *
     * @param string $dir full path to the html dir to recurse into
     * @param string $name base name for the sitemap
     */
    protected function singleSitemap($dir, $name)
    {
        $this->info("processing $dir");

        $temp = Config::SMAPDIR . '/' . $name . '.tmp';
        $target = Config::SMAPDIR . '/' . $name . '.gz';

        $fh = gzopen($temp, 'w');
        if (!$fh) {
            $this->error("Could not open $temp for writing");
            return;
        }

        gzwrite($fh, '<?xml version="1.0" encoding="UTF-8"?>' . "\n");
        gzwrite($fh, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n");

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        foreach ($iterator as $file => $info) {
            /** @var \SplFileInfo $info */
            if ($info->isDir()) continue;
            if ($info->getExtension() != 'html') continue;

            $local = substr($file, strlen(Config::HTMLDIR));
            if (preg_match('!(/[^/]*)?/man(.?)/(.*)(\.html)$!', $local, $m)) {
                $man = $m[3];
                $sec = $m[2];
                $lang = preg_replace('/[\.@].*$/', '', $m[1]);
            } else {
                $this->error("failed to determine man page from $file");
                continue;
            }

            gzwrite($fh, '  <url>' . "\n");
            gzwrite($fh, '    <loc>' . Config::URL . '/' . "$man($sec)$lang" . '</loc>' . "\n");
            gzwrite($fh, '    <lastmod>' . date('c', $info->getMTime()) . '</lastmod>' . "\n");
            gzwrite($fh, '    <changefreq>monthly</changefreq>' . "\n");
            gzwrite($fh, '  </url>' . "\n");
        }

        gzwrite($fh, '</urlset>' . "\n");
        gzclose($fh);

        if (rename($temp, $target)) {
            $this->success("wrote $target");
        } else {
            $this->error("failed to write $target");
        }
    }
}

$sitemaps = new Sitemaps();
$sitemaps->run();