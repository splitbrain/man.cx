<?php

namespace splitbrain\mancx\main;


class Config
{
    /** where the man pages are stored */
    const MANDIR = __DIR__ . '/../data/man';

    /** where temporary data can be unpacked */
    const TEMPDIR = __DIR__ . '/../data/temp';

    /** where converted html and toc data is stored */
    const HTMLDIR = __DIR__ . '/../data/html';

    /** where plates templates are stored */
    const TPLDIR = __DIR__ . '/tpl';

    /** where images are stored (has to be within web root) */
    const IMGDIR = __DIR__ . '/../www/___/img';

    /** base url for accessing above images */
    const IMGBASE = '/___/img';


}