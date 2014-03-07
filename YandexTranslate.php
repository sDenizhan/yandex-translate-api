<?php
/**
 * Yandex TRANSLATE API
 *
 * Yandex Servislerine Erişim Sağlayarak Çeviri Yapmanıza Yardımcı Olur.
 * Bu Sistemin Çalışması İçin ilk önce Yandex Translate Api Key'ine İhtiyaç Vardır.
 *
 * Yandex Translate Api Key Almak İçin Aşağıdaki Adresi Ziyaret Ediniz.
 *
 * Coder    : Süleyman DENİZHAN
 * Website  : http://www.sdenizhan.com
 * 
 */

class YandexTranslate
{
    /**
     * Yandex Translate Api Key'ini Buraya Giriniz..!
     */
    const api_key = 'TRANSLATE API ANAHTARINIZI BURAYA GIRIN';

    /**
     * Sorgulama Sonucu Dönecek Olan Verinin Türü
     */
    protected $return_type = 'tr.json';


    /**
     * Construct Method
     */
    function __construct(){}

    /**
     * Sorgulama Sonrasında Dönecek Veri Türünü Set Eder...
     *
     * @param $tur
     */
    public function set_return_type($tur)
    {
        if ($tur == 'xml')
        {
            $this->return_type = 'tr';
        }
        else
        {
            $this->return_type = 'tr.json';
        }
    }

    /**
     * Sorgulama Sonrasında Dönecek Veri Türünü Get Eder...
     *
     * @return string
     */
    public function get_return_type()
    {
        return $this->return_type;
    }

    /**
     * Bağlantı İçin Curl Fonksiyonu..
     *
     * @param string $url
     * @param string $send_type
     * @param array  $post
     *
     * @return bool
     * @throws Exception
     */

    private function curl($url, $send_type = 'get', $post = array())
    {
        if (empty($url))
        {
            throw new Exception('Api Bağlantı Hatası: '. __FUNCTION__ .' için url değişkeni girilmemiş');
        }

        if ($send_type != 'get' && count($post) == 0)
            throw new Exception('Curl Fonksiyonu İçin POST Değerleri Bulunamadı..!');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        if ($send_type != 'get')
        {
            curl_setopt($curl, CURLOPT_POST, TRUE);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        }
        $result = curl_exec($curl);

        if ($result)
        {
            return $this->is_error($result);
        }
        else
        {
            throw new Exception('Curl Bağlantısı Bir Hata ile Karşılaştı. HATA: ' . curl_error($curl));
        }
    }

    /**
     * Yandex Translate Desteklenen Dilleri Sorgular...
     *
     * @param string $ui
     *
     * @return string
     */

    public function api_translate_langs($ui = 'tr')
    {
        //destekleyen diller
        $supported_langs = array('en', 'tr', 'uk', 'ru');

        //desteklenmeyen bir arayüz dili sunulursa türkçe yapalım...
        if ( empty($ui) || false == in_array($ui, $supported_langs))
            $ui = 'tr';

        //veri türü
        $return = $this->get_return_type();

        $api_url = 'https://translate.yandex.net/api/v1.5/'. $return .'/getLangs?key='. self::api_key .'&ui='. $ui;

        $langs = $this->curl($api_url);

        return $langs;
    }

    /**
     * Yandex Translate Fonksiyonu
     *
     * @param        $source
     * @param        $target
     * @param        $word
     * @param string $send_type
     *
     * @return bool
     * @throws Exception
     */

    public function translate($source, $target, $word, $send_type = 'get')
    {
        if (empty($source))
            throw new Exception('Kaynak Dil Belirtilmedi..!');

        if (empty($target))
            throw new Exception('Hedef Dil Belirtilmedi..!');

        if (empty($word) || count($word) == 0)
            throw new Exception('Çevirilecek Kelime Bulunamadı..!');

        //array gelen ifadeyi stringe çevirelim...
        if (is_array($word))
        {
            $text = $this->array2string($word);
        }
        else
        {
            $text = $word;
        }

        //veri türünü alalım..
        $return = $this->get_return_type();

        //send turu get ise..
        if ($send_type == 'get'){
            $api_url = 'https://translate.yandex.net/api/v1.5/'. $return .'/translate?key='. self::api_key .'&lang='. $source .'-'. $target .'&text='. $text .'';

            return $this->curl($api_url);
        }
        else
        {
            $api_url = 'https://translate.yandex.net/api/v1.5/'. $return .'/translate';

            return $this->curl($api_url, 'post', array('key' => self::api_key, 'lang' => $source .'-'. $target, 'text' => $text));
        }
    }

    /**
     * Array'ı String ifadeye çevirir..
     *
     * @param array $array
     *
     * @return string
     * @throws Exception
     */
    private function array2string($array=array())
    {
        if (count($array) == 0)
            throw new Exception(__FUNCTION__ .' Fonksiyonuna Boş Array Değişkeni Gönderemezsiniz..!');

        $string = '';

        foreach($array as $key => $val)
        {
            $string .= $val;
        }

        return $string;
    }

    /**
     * Hata Olup Olmadığına Bakar...
     *
     * @param $veri
     *
     * @return bool
     * @throws Exception
     */

    private function is_error($veri)
    {
        //gelen verinin türü
        $return = $this->get_return_type();

        //json veri ise
        if ($return == 'tr.json')
        {
            //veriyi diziye çevirelim
            $json = json_decode($veri, TRUE);

            //code var mı bakalım
            if ( false == array_key_exists('code', $json))
            {
                return $veri;
            }
            else
            {
                $code = $json['code'];

                if ($json['code'] != '200')
                {
                    throw new Exception('Bir Hata Oluştu. Hata Kodu : ' . $code . ' Hata Açıklaması : '. $this->error($code) );
                }
                else
                {
                    return $veri;
                }
            }
        }
        else
        {
            //xml hatası denetimi...
            if (stripos($veri, '<Error') > 0)
            {
                @preg_match_all('/<Error code="(.*?)" message="(.*?)"\/>/i', $veri, $output);

                $code = $output[1][0];

                throw new Exception('Bir Hata Oluştu. Hata Kodu : ' . $code . ' Hata Açıklaması : '. $this->error($code) );

            }
            else
            {
                return $veri;
            }
        }

    }

    /**
     * API HATALARININ TÜRKÇESİ
     * @param $err_no
     *
     * @return mixed
     */

    private function error($err_no)
    {
        $error_list = array(
            '401' => 'Yanlış API Anahtarı Girdiniz..!',
            '402' => 'API Anahtarı Engellenmiş..!',
            '403' => 'Günlük Sorgulama Sınırını Geçtiniz..!',
            '404' => 'Günlük Sorgulama Sınırını Geçtiniz..!',
            '413' => 'Çevirelecek Kelime Sınırı Aşıldı..! Hatırlatma : Max. 1000 Karakter',
            '422' => 'Çevrilemeyen Kelime',
            '501' => 'Desteklenmeyen Dil'
        );

        return $error_list[$err_no];
    }

}
