<?php

namespace splitbrain\mancx\main;

/**
 * Configures the various file locations
 *
 * @package splitbrain\mancx\main
 */
class Config
{
    /** the full qualitfied url of the site */
    const URL = 'https://man.cx';

    /** where the man pages are stored */
    const MANDIR = __DIR__ . '/../data/man';

    /** where temporary data can be unpacked */
    const TEMPDIR = __DIR__ . '/../data/temp';

    /** where converted html and toc data is stored */
    const HTMLDIR = __DIR__ . '/../data/html';

    /** where plates templates are stored */
    const TPLDIR = __DIR__ . '/tpl';

    /** where the sitemaps are stored */
    const SMAPDIR = __DIR__ . '/../www/___/sitemaps';

    /** base url to access above sitemaps */
    const SMAPBASE = self::URL . '/www/___/sitemaps';

    /** where images are stored (has to be within web root) */
    const IMGDIR = __DIR__ . '/../www/___/img';

    /** base url for accessing above images */
    const IMGBASE = '/___/img';


}