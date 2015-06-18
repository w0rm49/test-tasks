<?php
/**
 * @var $model LoginForm
 * @var $i18n I18n
 */
use Models\LoginForm;
use Util\I18n;

$i18n = App::obj()->i18n;
$email = ($model === null) ? '' : htmlspecialchars($model->email, ENT_QUOTES | ENT_HTML5);
$error = '';
if ($model !== null) {
    $errors = array_merge($model->getErrors('email'), $model->getErrors('password'));
    $error = (count($errors) > 0) ? $i18n->m($errors[0]) : '';
}
?>

<h1 class="page--title"><?= $i18n->m('login'); ?></h1>

<form action="/login" method="post" class="login-form">

    <div class="form-row login-form--row">
        <label for="email" class="form-row--description"><?= $i18n->m('email'); ?></label>
        <input class="form-row--input--text need-validation" type="text" name="email" id="email"
               placeholder="user@example.com" value="<?= $email; ?>"
            />
    </div>

    <div class="form-row login-form--row">
        <label for="password" class="form-row--description"><?= $i18n->m('password'); ?></label>
        <input class="form-row--input--text need-validation" type="password" name="password" id="password" value="" />
    </div>

    <div class="form-row--error-message"><?= $error; ?>&nbsp;</div>

    <div class="form-row login-form--row--submit">
        <input type="submit" name="submit" id="submit" class="form-row--submit" value="<?= $i18n->m('login'); ?>"/>
    </div>

</form>