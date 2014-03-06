<?php
/**
 * Yandex TRANSLATE API
 *
 * Yandex Servislerine Erişim Sağlayarak Çeviri Yapmanıza Yardımcı Olur.
 * Bu Sistemin Çalışması İçin ilk önce Yandex Translate Api Key'ine İhtiyaç Vardır.
 *
 * Yandex Translate Api Key Almak İçin Aşağıdaki Adresi Ziyaret Ediniz.
 *
 *
 */

class YandexTranslate
{
    /**
     * Yandex Translate Api Key'ini Buraya Giriniz..!
     */
    const api_key = 'trnsl.1.1.20140218T113820Z.d46b0be5ae38b8b1.d73436a7db6c8c3a3134bb02381b83487503975e';

    /**
     * Sorgulama Sonucu Dönecek Olan Verinin Türü
     */
    protected $return_type = 'json';


    /**
     * Construct Method
     */
    function __construct(){}

    /**
     * Sorgulama Sonrasında Dönecek Veri Türünü Set Eder...
     *
     * @param $tur
     */
    function set_verituru($tur)
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
    function get_verituru()
    {
        return $this->return_type;
    }

    /**
     * Verilen adrese bağlantı sağlar..
     *
     * @param string $url
     * @param string $tur
     *
     * @return string
     */
    function apiye_git($url = '', $tur = 'curl')
    {
        if (empty($url))
        {
            return 'Api Bağlantı Hatası: '. __FUNCTION__ .' için url değişkeni girilmemiş';
        }

        if ($tur == 'curl')
        {
            return $this->api_curl($url);
        }
        else
        {
            return $this->api_fgc($url);
        }

    }

    /**
     * URL'ye file_get_contents ile bağlantı sağlar...
     *
     * @param $url
     *
     * @return string
     */

    function api_fgc($url)
    {
        if (empty($url))
        {
            return 'Api Bağlantı Hatası: '. __FUNCTION__ .' için url değişkeni girilmemiş';
        }

        return file_get_contents($url);
    }

    /**
     * Yandex Translate Desteklenen Dilleri Sorgular...
     *
     * @param string $ui
     *
     * @return string
     */

    function api_tum_diller($ui = 'tr')
    {
        //https://translate.yandex.net/api/v1.5/tr/getLangs?key=trnsl.1.1.20140218T113820Z.d46b0be5ae38b8b1.d73436a7db6c8c3a3134bb02381b83487503975e&ui=en
        //destekleyen diller
        $destekleyen_diller = array('en', 'tr', 'uk', 'ru');

        //desteklenmeyen bir arayüz dili sunulursa türkçe yapalım...
        if ( empty($ui) || false == in_array($ui, $destekleyen_diller))
            $ui = 'tr';

        //veri türü
        $veri_turu = $this->get_verituru();

        $api_url = 'https://translate.yandex.net/api/v1.5/'. $veri_turu .'/getLangs?key='. self::api_key .'&ui'. $ui;

        $api_desteklenen_diller = $this->apiye_git($api_url, 'fgc');

        return $api_desteklenen_diller;
    }

}