<?php

require_once(dirname(__FILE__).'/nusoap.php');

class GoogleAPI {

    var $apikey      = 'NOT SET';
    var $soapclient  = null;
    var $soapoptions = 'urn:GoogleSearch';
    var $restrict    = ''; //set to restrict to certain page
    var $error       = '';

    /**
     * Constructor
     *
     * @param string $key  - Your Google API key
     * @param string $site - Optional domainname for restricting the search
     */
    function GoogleAPI($key,$site=''){
        $this->apikey = $key;
        $this->soapclient = new soapclient('http://api.google.com/search/beta2');
        $this->soapclient->soap_defencoding='UTF-8';
        $this->soapclient->decode_utf8=false;
        if($site){
            $this->restrict = 'site:'.$site;
        }
    }

    /**
     * Calls the Google API and retrieves the search results in $ret
     *
     * Note that we pass in an array of parameters into the Google search.
     * The parameters array has to be passed by reference.
     * The parameters are well documented in the developer's kit on the
     * Google site http://www.google.com/apis
     */
    function do_search( $q, $start ){

        $params = array(
                    'key'        => $this->apikey,
                    'q'          => $this->restrict.' '.$q,
                    'start'      => $start,
                    'maxResults' => 10,
                    'filter'     => false,
                    'restrict'   => '',
                    'safeSearch' => false,
                    'lr'         => '',
                    'ie'         => '',
                    'oe'         => ''
                  );

        // Here's where we actually call Google using SOAP.
        // doGoogleSearch is the name of the remote procedure call.

        $ret = $this->soapclient->call('doGoogleSearch', $params, $this->soapoptions);
        $err = $this->soapclient->getError();

        if ($err) {
            $this->error = $err;
            return false;
        }

        return $ret;
    }

    /**
     * Calls the Google API and retrieves the suggested spelling correction
     */
    function do_spell( $q, &$spell ){

        $params = array(
                'key' => $this->apikey,
                'phrase' => $q,
        );

        $spell = $soapclient->call('doSpellingSuggestion', $params, $this->soapoptions);

        $err = $soapclient->getError();

        if ($err){
            $this->error = $err;
            return false;
        }
        return $spell;
    }
}

