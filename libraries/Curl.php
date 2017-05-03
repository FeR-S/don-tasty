<?php
//http://fstrange.ru
//update 08.07.2011

/**
 * (PHP 4, PHP 5)<br/>
 * получить страницу методом Get или Post с автоматическим сохранением кук
 * @author fStrange http://fstrange.ru
 * @param string  $su url
 * @param string  $suReferrer  Referrer
 * @param array  $aPost  POST data, array or uldecoded string
 * @param bool  $bSaveCookie
 * @return string $sHtml
 */
class Curl
{
    const
        BrowserCookiePath = 'cookies/BrowserCookie.txt',
        BrowserTimeOut = 20,
        BrowserFileTimeOut = 300;

    protected
        $aBrowser;

    public function __construct()
    {
        $this->setABrowser();
    }

    public function BrowserOpen($su, $aPost = array(), $bSaveCookie = TRUE)
    {

        $this->aBrowser['su'] = $su;
        if (!isset($this->aBrowser['suReferrer'])) $this->aBrowser['suReferrer'] = $su;

        $rCurl = curl_init($su);
        curl_setopt($rCurl, CURLOPT_URL, $su);
        curl_setopt($rCurl, CURLOPT_RETURNTRANSFER, TRUE);   //no print
        //curl_setopt($rCurl, CURLOPT_FOLLOWLOCATION, FALSE);   //no print
        curl_setopt($rCurl, CURLOPT_USERAGENT, $this->aBrowser['sUa']);
        curl_setopt($rCurl, CURLOPT_HEADER, true);
        curl_setopt($rCurl, CURLOPT_REFERER,  $this->aBrowser['suReferrer']);

        //curl_setopt($rCurl, CURLOPT_HTTPHEADER, $this->aBrowser['aHttp']); //our query

        if($aPost){
            curl_setopt($rCurl, CURLOPT_POST, 1);
            curl_setopt($rCurl, CURLOPT_POSTFIELDS, $aPost);  // array or url_encoded str
            // curl_setopt($rCurl, CURLOPT_HTTPHEADER, array("Content-type: application/x-www-form-urlencoded"));
        }
        //if($this->aBrowser['suReferrer']) curl_setopt($rCurl, CURLOPT_REFERER, $this->aBrowser['suReferrer']);
        // не проверять сертификат

        curl_setopt($rCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($rCurl, CURLOPT_SSL_VERIFYHOST, 0);

        //curl_setopt($rCurl, CURLOPT_COOKIESESSION, TRUE);
        //curl_setopt($rCurl, CURLOPT_VERBOSE, true);

        // для каждого хоста отдельный домен

        $www = explode('/', $su);
        curl_setopt($rCurl, CURLOPT_COOKIEJAR, dirname(__FILE__)."/../cookies/".$www[2].".txt");
        curl_setopt($rCurl,CURLOPT_COOKIEFILE, dirname(__FILE__)."/../cookies/".$www[2].".txt");

        curl_setopt($rCurl, CURLOPT_HTTPHEADER, array("Origin: ".$www[0]."//".$www[2]));
        curl_setopt($rCurl, CURLOPT_HTTPHEADER, array("Host: ".$www[2]));

        //send HTTP Query
        $sHtml = curl_exec($rCurl);
        if(strpos($sHtml, '"\r\n\r\n"')) list($this->aBrowser['sHeader'], $sHtml) = explode("\r\n\r\n", $sHtml, 2);
        else $this->aBrowser['sHeader'] = $sHtml;
        //$this->BrowserCookie($bSaveCookie);

        curl_close($rCurl);

        /*
        while ( $this->BrowserRedirect() &&  $this->aBrowser['iLocation']<5){
            $sHtml = $this->BrowserOpen($this->aBrowser['suLocation']);
        }*/
        // если в ответ идет редирект, то получаем ссылку на редирект и переходим руками (так предсказуемее)
        $location = $this->BrowserRedirect();
        if ($location) $sHtml = $location;

        $this->aBrowser['suReferrer'] = $su ;

        return $sHtml;
    }

    public function setReferer($referer)
    {
        $this->aBrowser['suReferrer'] = $referer;
    }

    protected function BrowserCookie($bSaveCookie)
    {
        if(!$this->aBrowser['sHeader'] || !strpos($this->aBrowser['sHeader'], 'Set-Cookie')) return FALSE;
        if(!$bSaveCookie) return FALSE ;

        preg_match_all("/Set-Cookie: (.*?)=(.*?);/i", $this->aBrowser['sHeader'], $a);
        if(isset($a[1])){
            foreach ($a[1] as $k => $v) $a0[$v] = $a[2][$k];
            if(!empty($a0)) $this->BrowserCookieSave($a0, $this->aBrowser['sfBrowserCookie']);
        }

        return TRUE;
    }

    protected function BrowserRedirect()
    {
        if(!$this->aBrowser['sHeader'] && (!strpos($this->aBrowser['sHeader'], 'Location:') || !strpos($this->aBrowser['sHeader'], 'location:'))) return FALSE;

        preg_match("#Location: (.*?)[\s\r\n]#is", $this->aBrowser['sHeader'], $a);
        //print_r($this->aBrowser['sHeader']);die;
        if(isset($a[1]))
        {
            $location = $a[1];
            if (substr($location, 0, 4) == 'http') $this->aBrowser['suLocation'] = $location;
            elseif (substr($location, 0, 1) == '/')
            {
                $host = explode('/', $this->aBrowser['su']);
                $this->aBrowser['suLocation'] =  $host[0] . '//' . $host[2] . $location;
            }
            else {
                $host = explode('/', $this->aBrowser['su']);
                $this->aBrowser['suLocation'] =  $host[0] . '//' . $host[2] . '/' . $location;
            }
            //print_r($this->aBrowser['suLocation']);die;
        }
        else
            $this->aBrowser['suLocation'] = '';

        return  $this->aBrowser['suLocation'];
    }


    /*public function BrowserCookieSave($a)
    {
        $s = '';
        foreach($a as $k=>$v) $s .= "$k=$v; ";
        $this->aBrowser['sCookie'] = $s;
        file_put_contents($this->aBrowser['sfBrowserCookie'], $s);
    }

    public function BrowserCookieDel($i=0)
    {
        $this->aBrowser['sCookie'] = '';
        if(1 == $i)
            if(file_exists($this->aBrowser['sfBrowserCookie'])) unlink($this->aBrowser['sfBrowserCookie']);
    }*/

    public function loadImage($host, $filename)
    {
        $complete = true;
        $ch = curl_init($host);
        $fp = fopen($filename, "w+");

        // устанавливаем URL и другие функции.
        $options = array(CURLOPT_FILE => $fp,
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_TIMEOUT => 2); // Таймаут в 1 секунду должен быть достаточен

        curl_setopt_array($ch, $options);
        curl_exec($ch);
        if (curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200) $complete = false;
        curl_close($ch);
        fclose($fp);
        return $complete;
    }

    protected function setABrowser()
    {
        /*
         * Browser Options $aBrowser[option]
         * options:
         * aHttp = Http param
         * sUa = UserAgent
         *
         * after using the function Browse()
         * options:
         * su = last visited url
         * suReferrer = lat referrer
         */
        $a['firefox'] = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2062.120 Safari/537.36';
        /*$a['ie'] = 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0)';
        $a['opera'] = 'Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.6.30 Version/10.61';
        $a['nokia'] = 'Mozilla/4.0 (compatible; MSIE 6.0; Symbian OS; Nokia 6630/4.03.38; 6937) Opera 8.50';
        $a['alexa'] = 'ia_archiver';
        $a['baidu'] = 'Baiduspider (+http://www.baidu.com/search/spider.htm)';
        $a['google'] = 'Mozilla/5.0 (compatible; googlebot/2.1; +http://www.google.com/bot.html)'; */

        $this->aBrowser['aHttp'] = array(
            'Accept'=>'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-encoding'=>'gzip,deflate',
            'Accept-language'=>'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
            'Cache-control'=>'max-age=0',
            'connection'=>'keep-alive'
        );
        //Accept-Encoding: \n  need for nogzip http

        $this->aBrowser['sUa'] = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.8) Gecko/20100722 Firefox/3.8.11';
        $this->aBrowser['iBrowserTimeOut'] = self::BrowserTimeOut;
        $this->aBrowser['iBrowserFileTimeOut'] = self::BrowserFileTimeOut;
        //$this->aBrowser['sCookie'] = '';
        $this->aBrowser['iLocation'] = 0;
        $this->aBrowser['suLocation'] = '';
    }
}