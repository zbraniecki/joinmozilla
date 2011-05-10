<?php

define('APPLICATION_ROOT', $_SERVER['DOCUMENT_ROOT']);

header('Content-type: text/html; charset=utf-8');
if (array_key_exists('locale', $_GET))
  $locale = $_GET['locale'];
else
  $locale = 'en-US';
putenv("LC_ALL=" . $locale);
setlocale(LC_ALL , $locale);

bindtextdomain('messages', APPLICATION_ROOT . '/locale');
bind_textdomain_codeset("messages", "UTF-8");
textdomain('messages');

require_once('header.php');
?>
<?= _('Thank You') ?>
<?= _("for protecting the world's largest public resource: the web") ?>

<?= _("Wear it!") ?>
<?= _("Now that you have a Mozilla T-shirt, put it on, get out there and tell the world that you think the web matters.") ?>

<?= _("Share it!") ?>
<?= _("As a supporter of Mozilla, you can do the web a solid by telling your family, friends and random strangers.") ?>

<?php /* back */?>

<?= _("Extra credit!") ?>
<?= _("Want to do more? Fill in the blanks and hand this to a friend. Tell them why you're supporting Mozilla and get them in on the fun.") ?>

<?= _("Dear") ?> 
<?= _("I just joined Mozilla!") ?>

<?= _("I think you should too, because") ?>
<?= _("(Pick one of the many awesome reasons).") ?>

<?= _("If you become a Mozilla supporter, I will") ?>
<?= _("(make a promise or a pledge - the more creative, the better).") ?>

<?= _("Sign up at mozilla.org/join") ?>
<?= _("hugs!") ?>

</div>
</section>

<?php
require_once('footer.php');
?>
