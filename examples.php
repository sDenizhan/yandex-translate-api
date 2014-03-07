<?php
//YandexTranslate Sınıfımızı Include Edelim...
include 'YandexTranslate.php';

//yandex classımız..
$yandex = new YandexTranslate();

//Exception Kullanıldığı İçin try-catch bloğu kullanın...
try
{
    /**
     * Dönecek olan verinin türünü ayarlıyoruz ...
     *
     * XML için xml, JSON için json
     */
    $yandex->set_return_type('xml');

    /**
     * Desteklenen Dilleri Gösterelim ...
     */
    echo $yandex->api_translate_langs('tr');

    /**
     * Get ile bir kelime translate edelim...
     *
     */
    echo $yandex->translate('en','tr','Hello World');

    /**
     * Post ile bir kelime translate edelim...
     */
    echo $yandex->translate('en','tr','Hello World', 'post');
}
catch (Exception $e)
{
    echo $e->getMessage();
}