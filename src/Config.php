<?php

namespace splitbrain\mancx\main;


class Config
{
    /** where the man pages are stored */
    const MANDIR = __DIR__ . '/../man';

    /** where temporary data can be unpacked */
    const TEMPDIR = __DIR__ . '/../temp';

    /** where converted html and toc data is stored */
    const HTMLDIR = __DIR__ . '/../html';

    /** where plates templates are stored */
    const TPLDIR = __DIR__ . '/../templates';

    /** where images are stored (has to be within web root) */
    const IMGDIR = __DIR__ . '/../htdocs/___img';

    /** base url for accessing above images */
    const IMGBASE = '/___img';


}