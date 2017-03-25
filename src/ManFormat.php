<?php

namespace splitbrain\mancx\main;


class ManFormat
{
    protected $sections = array();
    protected $languages = array();
    protected $versions = array();
    protected $manfile;


    public function __construct($man, $sec, $lang)
    {
        $this->sections = explode(',', '1,2,3,4,5,6,7,8,9,n,l,p,o,1x');
        $this->languages = $this->getLanguages();
        $this->versions = $this->getVersions($man);

        if($sec){
            $id = trim("($sec)/$lang",'/');
        }else{
            $keys = array_keys($this->versions);
            $id = reset($keys);
        }

        if(!$this->versions[$id]){
            throw new \Exception('no such man page: '.$man.$id);
        }

        list($sec) = explode('/',$id);

        $this->manfile = $this->versions[$id];


        print "$man $sec $lang";
        print date('r', filemtime($this->manfile));

        print_r($this->versions);
    }





    /**
     * Load a list of all versions of the given man page
     *
     * @param string $man
     * @return array
     */
    function getVersions($man)
    {
        $result = array();
        foreach ($this->languages as $lang => $langdirs) {
            foreach ($this->sections as $sec) {
                foreach ($langdirs as $langdir) {
                    $file = "$langdir/man$sec/$man.html";
                    if(!file_exists($file)) continue;
                    $id = "($sec)";
                    if($lang) $id .= "/$lang";

                    // prefer shorter results for the same id
                    if(!isset($result[$id]) || strlen($result[$id]) > strlen($file)) {
                        $result[$id] = $file;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * get all available languages and their possible directories
     *
     * @return array
     * @todo maybe cache this
     */
    function getLanguages()
    {
        $languages = array();
        $languages[''] = array(Config::HTMLDIR);
        $dirs = glob(Config::HTMLDIR . '/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $base = basename($dir);
            if (substr($base, 0, 3) == 'man') continue;
            $base = preg_replace('/[\.@].*$/', '', $base);

            if (!isset($languages[$base])) $languages[$base] = array();
            $languages[$base][] = $dir;
        }
        return $languages;
    }

}