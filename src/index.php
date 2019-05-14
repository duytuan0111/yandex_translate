<?php 
namespace Yandex\Translate;
require('Translator.php');
require('Exception.php');
require('Translation.php');
$text_translate = '你好高兴见到你';
$translator = new Translator('trnsl.1.1.20190514T061140Z.ec7456ae3769d3ab.ecef6235f1083ce1142b0d261a6532111a7f4fde');
$translation = $translator->translate($text_translate, 'zh-vi');
echo $translation; 
// echo $translation->getSource(); // nội dung dịch
// echo $translation->getSourceLanguage(); // zh
// echo $translation->getResultLanguage(); // vi
?>