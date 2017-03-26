#!/usr/bin/php
<?php

namespace splitbrain\mancx\bin;

require __DIR__ . '/../vendor/autoload.php';

use EasyRequest\Client;
use splitbrain\mancx\main\Config;
use splitbrain\phpcli\CLI;
use splitbrain\phpcli\Options;

class Fetch extends CLI
{

    const TYPE_CONTENTS = 1;
    const TYPE_PACKAGES = 2;

    protected $dpkg;

    protected $packages = array();


    /**
     * Register options and arguments on the given $options object
     *
     * @param Options $options
     * @return void
     */
    protected function setup(Options $options)
    {
        $options->setHelp('Fetch the manpages from the given Debian repository');
        $options->registerArgument('server', 'The server base URL eg. http://ftp.de.debian.org/debian');
        $options->registerArgument('dist', 'The distribution alias eg. testing');
        $options->registerArgument('sections...', 'The sections eg. main. You can give multiple');

        $options->registerOption('dpkg', 'path to the dpkg utility', null, true);

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
        $this->dpkg = $options->getOpt('dpkg', '/usr/bin/dpkg');

        if (!is_executable($this->dpkg)) {
            $this->fatal($this->dpkg . 'is not executable, maybe you need to specify a different path?');
        }

        $args = $options->getArgs();
        $server = array_shift($args);
        $dist = array_shift($args);
        $sections = $args;

        foreach ($sections as $section) {
            $contents = $this->downloadDebianInfo(self::TYPE_CONTENTS, $server, $dist, $section);
            $packages = $this->downloadDebianInfo(self::TYPE_PACKAGES, $server, $dist, $section);

            $this->getPackageNames($contents);
            $this->getPackageUrls($packages, $server);
        }

        foreach ($this->packages as $package => $url) {
            if ($url === 1) continue;
            $this->extractPackage($url);
        }
    }

    /**
     * Download the package or content file
     *
     * @param int $type see class constants
     * @param string $server
     * @param string $dist
     * @param string $section
     * @param string $arch
     * @return string
     */
    protected function downloadDebianInfo($type, $server, $dist, $section, $arch = 'i386')
    {
        if ($type == self::TYPE_CONTENTS) {
            $url = "$server/dists/$dist/$section/Contents-$arch.gz";
        } else if ($type == self::TYPE_PACKAGES) {
            $url = "$server/dists/$dist/$section/binary-$arch/Packages.gz";
        } else {
            throw new \RuntimeException('bad type given');
        }
        $target = Config::TEMPDIR . '/' . md5($url . date('Y-m-d')) . '.gz';

        $this->info("downloading $url to $target...");

        if (file_exists($target)) {
            $this->success("File $target already exists");
            return $target;
        }

        $request = Client::request($url)->send();
        if ($request->getStatusCode() > 299) {
            $this->fatal("Failed to download $url");
        }

        if (!file_put_contents($target, $request->getBody()->getContents())) {
            $this->fatal("Failed to write $target");
        }

        $size = filesize($target);
        $this->success("downloaded $size bytes");

        return $target;
    }

    /**
     * Read the packages having man pages from the given content file
     *
     * @param string $file
     */
    protected function getPackageNames($file)
    {
        $this->info('finding packages that contain manpages...');
        $count = 0;
        $fh = gzopen($file, 'r');
        if (!$fh) $this->fatal("Can't read $file");
        while (!gzeof($fh)) {
            $line = gzgets($fh);
            if (preg_match('!^(usr/share/man/|usr/man/|usr/X11R6/man/|usr/local/man/|opt/man/)(([\.\w]+/)?man.*?)\s+(.*)!', $line, $m)) {
                $package = explode(',', $m[4]);
                $package = array_shift($package);
                $package = explode('/', $package);
                $package = array_pop($package);
                if (!isset($this->packages[$package])) {
                    $this->packages[$package] = 1;
                    $count++;
                }
            }
        }
        gzclose($fh);

        if (!$count) {
            $this->error('Found no packages with manpages');
        } else {
            $this->success("Found $count packages");
        }
    }

    /**
     * Read the package urls form the given package file
     *
     * @param string $file
     * @param string $server
     */
    protected function getPackageUrls($file, $server)
    {
        $this->info('finding package URLs...');

        $count = 0;
        $fh = gzopen($file, 'r');
        if (!$fh) $this->fatal("Can't read $file");
        while (!gzeof($fh)) {
            $line = gzgets($fh);

            if (preg_match('/^Package: +(.*)$/', $line, $m)) {
                $package = $m[1];
                if (!isset($this->packages[$package])) continue;
                // it's an interesting package, read til we get the filename
                do {
                    $line = gzgets($fh);
                } while (!preg_match('/^Filename: +(.*\.deb)$/', $line, $mf));
                $this->packages[$package] = $server . '/' . $mf[1];
                $count++;
            }
        }
        gzclose($fh);

        if (!$count) {
            $this->error('Found no URLs for our packages');
        } else {
            $this->success("Found $count package URLs");
        }
    }

    /**
     * Download and extract the given debian package
     *
     * @param string $url
     */
    protected function extractPackage($url)
    {
        $target = Config::TEMPDIR . '/' . md5($url);
        mkdir($target, 0777, true);
        $package = "$target/package.deb";

        $this->info("downloading $url to $package...");

        $request = Client::request($url)->send();
        if ($request->getStatusCode() > 299) {
            $this->error("Failed to download $url");
            return;
        }

        if (!file_put_contents($package, $request->getBody()->getContents())) {
            $this->error("Failed to write $package");
            return;
        }

        system($this->dpkg . " --extract $package $target/");

        $paths = array(
            'usr/share/man',
            'usr/man',
            'usr/X11R6/man',
            'usr/local/man',
            'opt/man'
        );
        foreach ($paths as $path) {
            if (is_dir("$target/$path")) {
                system("cp -rfupv $target/$path/* " . Config::MANDIR . '/');
            }
        }

        system("rm -rf $target");
    }
}

$fetch = new Fetch();
$fetch->run();