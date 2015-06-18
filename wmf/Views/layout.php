<?php
/**
 * @var $content
 * @var $auth Util\Auth
 * @var $i18n Util\I18n
 */

$auth = App::obj()->auth;
$i18n = App::obj()->i18n;

$switchToLang = array_filter($i18n->getLanguages(), function($element) use ($i18n){
    return $element !== $i18n->currentLang;
});

?>
<!DOCTYPE html>
<html>
<head lang="<?= $i18n->currentLang; ?>">
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="/css/main.css"/>
</head>
<body class="page">

<header class="page--header">
    <nav class="header--navigation navigation">
        <?php if (!$auth->isLoggedIn()): ?>
            <a href="/login" class="navigation--link--left"><?= $i18n->m('login'); ?></a>
            <a href="/register" class="navigation--link--left"><?= $i18n->m('register'); ?></a>
        <?php else: ?>
            <a href="/logout" class="navigation--link--left"><?= $i18n->m('logout'); ?></a>
        <?php endif; ?>
        <?php foreach($switchToLang as $lang): ?>
            <a href="#" class="navigation--link--right switch-lang" data-lang="<?= $lang; ?>"><?= strtoupper($lang); ?></a>
        <?php endforeach; ?>
    </nav>
</header>

<section class="page--content">

    <?= $content; ?>

</section>

<footer class="page--footer">

</footer>

<script src="js/lib.js"></script>
<script src="js/common.js"></script>
<?php if(isset($params['js']) && is_array($params['js'])): ?>
    <?php foreach($params['js'] as $js): ?>
        <script src="<?= $js; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
</body>
</html>