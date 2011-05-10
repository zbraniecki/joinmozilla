<?php

define('APPLICATION_ROOT', $_SERVER['DOCUMENT_ROOT']);

$locales = array(
    'en' => 'en_US',
    'de' => 'de_DE',
    'es' => 'es_ES',
    'pt-BR' => 'pt_BR'
);

$pages = array(
    'home',
    'thankyou',
    'faq',
    'card',
    'certificate',
    'hangtags',
);

header('Content-type: text/html; charset=utf-8');
if (array_key_exists('locale', $_GET))
    $locale = $_GET['locale'];
else
    $locale = 'en_US';
putenv("LC_ALL=" . $locale);
setlocale(LC_ALL , $locale);

?>

<!doctype html>
<html>
  <head>
  </head>

  <body>
    <form action="." method="GET">
      Select the locale to test: 
      <select name="locale" onchange="this.form.submit();">
      <?php foreach ($locales as $short => $long) { ?>
        <option value="<?= $long ?>" <?= ($long == $locale) ? 'selected="selected"' : ''?>><?= $short ?></option>
      <?php } ?>
      </select>
    </form>

    <ul>
      <?php foreach ($pages as $page) { ?>
        <li><a href="<?= $page ?>.php?locale=<?= $locale ?>"><?= $page ?></a></li> 
      <?php } ?>
    </ul>

  </body>
</html>

<?php /* vim: set ft=php et ts=4 sw=4: */ ?>
