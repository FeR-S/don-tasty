<?php
require("Simple_html_dom.php");
require('Curl.php');

class Parse
{
    const
        NOTE_SAVE_NAME = 0,
        SAVE_NAME = 1,
        SAVE_TYPE = 2,
        REG_ALL = 0,
        REG_MATCH = 1;

    protected
        $curl = NULL,
        $charset = 'utf-8';

    public function __construct()
    {
        $this->curlInit();
    }

    public function get($link, $tags = NULL)
    {
        if ($html = $this->curl->BrowserOpen($link)) {
            if (is_null($tags))
                return $this->is_utf8() ? $html : $this->decode_to_utf8($html);
            else
                return $this->parseHtml($html, $tags);
        };
        return false;
    }

    public function decode_cp1251_to_utf8($string)
    {
        return iconv("windows-1251", "utf-8", $string);
    }

    public function post($link, $post, $tags = NULL)
    {
        $html = $this->curl->BrowserOpen($link, $post);
        if (is_null($tags))
            return $this->is_utf8() ? $html : $this->decode_to_utf8($html);
        else
            return $this->parseHtml($html, $tags);
    }

    public function saveImage($host, $filepath, $type = self::NOTE_SAVE_NAME)
    {
        if ($type == self::SAVE_NAME) {
            $filepath = $this->doReg($host, '/([0-9a-zA-Z\.\-]+)$/');
        } elseif ($type == self::SAVE_TYPE) {
            $filetype = $this->doReg($host, '/.{3,4}$/');
            $filepath = $filepath . $filetype;
        }
        $this->curl->loadImage($host, $filepath);
    }

    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    public function encode_array($args)
    {
        if (!is_array($args)) return false;
        $c = 0;
        $out = '';
        foreach ($args as $name => $value) {
            if ($c++ != 0) $out .= '&';
            $out .= urlencode("$name") . '=';
            if (is_array($value)) {
                $out .= urlencode(serialize($value));
            } else {
                $out .= urlencode("$value");
            }
        }
        return $out . "\n";
    }

    protected function parseHtml($parsehtml, array $tags)
    {
        $data = [];

        // берем страницу
        $html = new simple_html_dom();
        $html->load($parsehtml);
        //$html->load_file($html_dom);
        if ($html == "") {
            throw new Exception("Пустая страница");
            return;
        }

        $html = $html->find('body', 0); // убираем лишнее из html

        // берем нужные куски с сайта
        foreach ($tags as $name => $value) {

            // номер элемента, если нужно
            if (isset($value['num'])) $num = $value['num'];
            if (!isset($value['attr'])) {
                $data[$name] = 'Не указан attr';
                continue;
            } elseif (!isset($value['tag'])) {
                $data[$name] = 'Не указан tag';
                continue;
            } else
                if (isset($value['attr'])) $attr = $value['attr'];

            if (is_array($value['tag'])) {
                $elements = $html;
                foreach ($value['tag'] as $num => $tag) {
                    if (!is_string($num)) {
                        $elements = $elements->find($tag, $num);
                        $get_all_elements = false;
                    } else {
                        $elements = $elements->find($tag);
                        $get_all_elements = true;
                    }
                }
            } else {
                $elements = $html->find($value['tag']);
                $get_all_elements = true;
            }

            if ($get_all_elements) {
                if (isset($num)) {
                    $data[$name] = $this->getData($elements[$num]->{$attr}, isset($value['regExp']) ? $value['regExp'] : false);
                } else {
                    foreach ($elements as $element) {
                        if (is_array($attr)) {
                            $qwe = [];
                            foreach ($attr as $_attr) {
                                $qwe[$_attr] = $this->getData($element->{$_attr}, isset($value['regExp']) ? $value['regExp'] : false);
                            }
                            $data[$name][] = $qwe;
                        } else {
                            $data[$name][] = $this->getData($element->{$attr}, isset($value['regExp']) ? $value['regExp'] : false);
                        }
                    }
                }
            } else {
                $data[$name] = $this->getData($elements->{$attr}, isset($value['regExp']) ? $value['regExp'] : false);
            }

        }
        return $data;

        $html->clear();
        unset($html);
    }

    protected function getData($text, $reg)
    {
        if ($reg)
            return $this->doReg($this->is_utf8() ? $text : $this->decode_to_utf8($text), $reg);
        else
            return $this->is_utf8() ? $text : $this->decode_to_utf8($text);
    }

    public function doReg($text, $reg, $type = self::REG_ALL)
    {
        $result = "";
        switch ($reg) {
            case 'href':
                preg_match('/http:\/\/(.*)/', $text, $matches);
                break;
            default:
                preg_match($reg, $text, $matches);
                break;
        }
        if (isset($matches[0])) {
            if ($type == self::REG_ALL) $result = $matches[0];
            elseif ($type == self::REG_MATCH) $result = $matches[1];
        } else $result = "";

        return $result;
    }

    public function is_redirect($link)
    {

        $test = $this->get($link);
        //var_dump($test);die;
        //todo проверять редиректы не по http а по ответу
        if (substr($test, 0, 4) != "http") return false;
        return true;
    }

    protected function is_utf8()
    {
        return $this->charset == 'utf-8';
    }

    protected function decode_to_utf8($string)
    {
        return iconv($this->charset, "utf-8", $string);
    }

    protected function curlInit()
    {
        if (is_null($this->curl)) $this->curl = new Curl;
    }

    public function loadFile($url, $name, $limit)
    {
        $fp = fopen($name, 'w+');
        //Here is the file we are downloading, replace spaces with %20
        $ch = curl_init(str_replace(" ", "%20", $url));
        curl_setopt($ch, CURLOPT_TIMEOUT, $limit);
        //give curl the file pointer so that it can write to it
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $data = curl_exec($ch);//get curl response
        curl_close($ch);
        return $data;
        //done
    }

    public function getStrHtml($str, $tag, $num = null)
    {
        $html = new simple_html_dom();
        $html->load($str);
        $html->find($tag, $num); // убираем лишнее из html
        $data[$name] = $this->getData($elements->{$attr}, isset($value['regExp']) ? $value['regExp'] : false);
    }


}

?>