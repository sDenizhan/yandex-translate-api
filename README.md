yandex-translate-api
====================

Yandex Translate API Servisi İçin Yazdığım Class'tır.


Kurulum:

1. İlk önce http://api.yandex.com/key/form.xml?service=trnsl adresinden kendinize bir API Anahtarı alınız.
2. Aldığınız Bu Anahtarı, YandexTranslate.php Dosyasında gerekli yere yazınız.

Methodlar:

set_return_type($return_type):
Sorgulama sonrasında servisten dönecek olan verinin yapısını belirler. Json yada XML veri olarak döndürebilirsiniz. Json için 'json', XML için 'xml' giriniz. Default olarak JSON ayarlanmıştır.

Örn:
$yandex->set_retun_type('json');

api_translate_langs($lang):
Yandex API'nin desteklediği dilleri gösterir. Arayüz olarak 4 dil destekler. Rusya için 'ru', Türkçe için 'tr', İngilizce için 'en' yada 'uk' giriniz. Varsayılan olarak Türkçe'ye ayarlanmıştır.

Örn:
$yandex->api_translate_langs('tr');

translate($source, $target, $word, $send_type);
Yandex API'nin çevirme fonksiyonudur. 4 farklı değişken alır.
$source ile kaynak dil, $target ile çevrilecek dil, $word ile çevirilecek metin, $send_type ile 'post' yada 'get' gönderimi ayarlanır.

Örn:
GET ile Çeviri => $yandex->translate('en', 'tr', 'hello world');
POST ile Çeviri => $yandex->translate('en', 'tr', 'hello world', 'post');

