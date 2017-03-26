<?php

namespace splitbrain\mancx\main;

use League\Plates;

class App
{
    /** @var Plates\Engine the templating engine */
    protected $plates;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $base = '/';
        if ($_SERVER['HTTP_HOST'] == 'localhost') $base = '/mp/www/';

        $this->plates = new Plates\Engine(Config::TPLDIR);
        $this->plates->addData(array(
            'title' => 'Linux Manpages Online',
            'lang' => 'en',
            'base' => $base,
            'robots' => 'index,follow'
        ));
    }

    /**
     * Execute the App
     */
    public function run()
    {
        header('Content-Type: text/html; charset=utf-8');
        $tpl = $this->route();
        echo $tpl->render();
    }

    /**
     * Return the correct template for the requested data
     *
     * @return array|\League\Plates\Template\Template
     */
    protected function route()
    {

        $page = '';
        if (isset($_REQUEST['page'])) $page = $_REQUEST['page'];

        if (isset($_REQUEST['do']['search'])) {
            return $this->view_search($page);
        }

        list($man, $sec, $lang) = $this->parseparams($page);

        if ($man) {
            return $this->view_manpage($man, $sec, $lang);
        }

        return $this->view_intro();
    }


    /**
     * Parse a given page into separate parameters
     *
     * @param string $query the page as given
     * @return array
     */
    protected function parseparams($query)
    {
        if (empty($query) && preg_match('/\?=(.+)/', $_SERVER['REQUEST_URI'], $matches)) {
            $query = $matches[1];
        }

        $query = trim($query, '/');
        $query = mb_strtolower($query, 'utf-8');

        $man = $sec = $lang = '';
        if (preg_match('/([a-z0-9_\-:\.]+)(\(([0-9nlpo][0-9a-z]*)\))?(\/([a-z_]+))?/', $query, $matches)) {
            if (isset($matches[1])) $man = $matches[1];
            if (isset($matches[3])) $sec = $matches[3];
            if (isset($matches[5])) $lang = $matches[5];
        }

        $man = preg_replace('/\.\.+/', '.', $man);

        return array($man, $sec, $lang);
    }


    protected function view_search($query)
    {
        $this->plates->addData(array('robots' => 'noindex,follow'));

        $tpl = $this->plates->make('search');
        $tpl->data(array('query' => $query));

        return $tpl;
    }

    protected function view_intro()
    {
        return $this->plates->make('intro');
    }

    protected function view_manpage($man, $sec, $lang)
    {
        try {
            $mf = new ManFormat($man, $sec, $lang);
        } catch (\Exception $e) {
            return $this->view_search($man);
        }

        $data = array(
            'title' => "Manpage for $man",
            'mf' => $mf,
            'man' => $man,
            'sec' => $mf->getSec()
        );
        if ($lang) $data['lang'] = $lang;


        $tpl = $this->plates->make('manpage');
        $tpl->data($data);
        return $tpl;
    }
}
