<?php
/**
 * @var $model RegisterForm|null
 * @var $i18n Util\I18n
 */
use Models\RegisterForm;

$i18n = App::obj()->i18n;

$val = function($field) use ($model) {
    if ($model === null) {
        return '';
    }
    return htmlspecialchars($model->$field, ENT_QUOTES | ENT_HTML5);
};

$errorClass = function($field, $class) use ($model) {
    if ($model === null) {
        return false;
    }
    return ($model->getErrors($field) !== []) ? $class : '';
};

$getError = function($field) use ($model, $i18n) {
    if ($model === null) {
        return '';
    }
    $errors = $model->getErrors($field);
    return (count($errors) > 0) ? $i18n->m($errors[0]) : '';
};

$clientValidator = function($field) use ($model){
    if ($model === null) {
        $model = new RegisterForm();
    }
    $rule = $model->fields()[$field];
    $converted = array_map(function($val) {
        return [
            'filter' => $val[0],
            'parameters' => $val[1],
            'errorMessage' => App::obj()->i18n->m($val[2])
        ];
    }, $rule);
    return htmlspecialchars(json_encode($converted), ENT_HTML5 | ENT_QUOTES);
};

$passMessages = htmlspecialchars(json_encode([
    'weak' => $i18n->m('weak_pass'),
    'medium' => $i18n->m('medium_pass'),
    'strong' => $i18n->m('strong_pass')
]), ENT_HTML5 | ENT_QUOTES);

$this->params['js'][] = "js/validator.js";
$this->params['js'][] = "js/register.js";

?>

<h1 class="page--title"><?= $i18n->m('register'); ?></h1>

<form action="/register" method="post" enctype="multipart/form-data" class="register-form">

    <div class="form-row login-form--row">
        <label for="name" class="form-row--description"><?= $i18n->m('name'); ?></label>
        <input class="form-row--input--text need-validation <?= $errorClass('name', 'form-row--input--error'); ?>"
               type="text" name="name" id="name" value="<?= $val('name'); ?>" placeholder="<?= $i18n->m('ex_name');?>"
               data-validator='<?= $clientValidator('name'); ?>'
            />
        <div class="form-row--error-message"><?= $getError('name'); ?></div>
    </div>

    <div class="form-row login-form--row">
        <label for="surname" class="form-row--description"><?= $i18n->m('surname'); ?></label>
        <input class="form-row--input--text need-validation <?= $errorClass('surname', 'form-row--input--error'); ?>"
               type="text" name="surname" id="surname" value="<?= $val('surname'); ?>" placeholder="<?= $i18n->m('ex_surname');?>"
               data-validator='<?= $clientValidator('surname'); ?>'
            />
        <div class="form-row--error-message"><?= $getError('surname'); ?></div>
    </div>

    <div class="form-row login-form--row">
        <label for="email" class="form-row--description"><?= $i18n->m('email'); ?></label>
        <input class="form-row--input--text need-validation <?= $errorClass('email', 'form-row--input--error'); ?>"
               type="text" name="email" id="email" placeholder="user@example.com" value="<?= $val('email'); ?>"
               data-validator='<?= $clientValidator('email'); ?>'
            />
        <div class="form-row--error-message"><?= $getError('email'); ?></div>
    </div>

    <div class="form-row login-form--row">
        <label for="password" class="form-row--description"><?= $i18n->m('password'); ?>
            <span class="pass-strength" data-messages="<?= $passMessages; ?>"></span>
        </label>
        <input class="form-row--input--text need-validation <?= $errorClass('password', 'form-row--input--error'); ?>"
               type="password" name="password" id="password" value=""
               data-validator='<?= $clientValidator('password'); ?>'
            />
        <div class="form-row--error-message password-error"><?= $getError('password'); ?></div>
    </div>

    <div class="form-row login-form--row">
        <label for="password2" class="form-row--description"><?= $i18n->m('password2'); ?></label>
        <input class="form-row--input--text" type="password" name="password2" id="password2" value="" />
    </div>


    <div class="form-row login-form--row">
        <label class="form-row--description"><?= $i18n->m('gender'); ?></label>
        <div class="radio-group">
            <input class="radio-group--input" type="radio" name="gender" id="gender-male"
                   value="male" <?= ($val('gender') === 'male') ? 'checked' : ''; ?>
                />
            <label for="gender-male" class="radio-group--label"><?= $i18n->m('male'); ?></label>
            <input class="radio-group--input" type="radio" name="gender" id="gender-female"
                   value="female" <?= ($val('gender') !== 'male') ? 'checked' : ''; ?>
                />
            <label for="gender-female" class="radio-group--label"><?= $i18n->m('female'); ?></label>
        </div>
        <div class="form-row--error-message"><?= $getError('gender'); ?></div>
    </div>

    <div class="form-row login-form--row date-row">
        <input class="form-row--input--text need-validation date-value <?= $errorClass('birth', 'form-row--input--error'); ?>"
               type="hidden" name="birth" value="<?= $val('birth'); ?>" />
        <label class="form-row--description" id="birth-date"><?= $i18n->m('birth'); ?></label>
        <div class="select-group">
            <label for="birth-date" class="select-group--label"><?= $i18n->m('day'); ?></label>
            <select class="select-group--input date-day" name="birth-date" id="birth-date">
                <?php for($i = 1; $i<=31 ; $i++): ?>
                    <option value="<?= $i; ?>"><?= $i; ?></option>
                <?php endfor; ?>
            </select>

            <label for="birth-month" class="select-group--label"><?= $i18n->m('month'); ?></label>
            <select class="select-group--input date-month" name="birth-month" id="birth-month">
                <option value="1"><?= $i18n->m('january');?></option>
                <option value="2"><?= $i18n->m('february');?></option>
                <option value="3"><?= $i18n->m('march');?></option>
                <option value="4"><?= $i18n->m('april');?></option>
                <option value="5"><?= $i18n->m('may');?></option>
                <option value="6"><?= $i18n->m('june');?></option>
                <option value="7"><?= $i18n->m('july');?></option>
                <option value="8"><?= $i18n->m('august');?></option>
                <option value="9"><?= $i18n->m('september');?></option>
                <option value="10"><?= $i18n->m('october');?></option>
                <option value="11"><?= $i18n->m('november');?></option>
                <option value="12"><?= $i18n->m('december');?></option>
            </select>

            <label for="birth-year" class="select-group--label"><?= $i18n->m('year'); ?></label>
            <select class="select-group--input date-year" name="birth-year" id="birth-year">
                <?php for($i = 2015; $i >= 1900 ; $i--): ?>
                    <option value="<?= $i; ?>"><?= $i; ?></option>
                <?php endfor; ?>
            </select>

        </div>
        <div class="form-row--error-message"><?= $getError('birth'); ?></div>
    </div>

    <div class="form-row login-form--row file-row file-block">
        <label class="form-row--description"><?= $i18n->m('photo'); ?></label>
        <input class="file-block--input need-validation photo-upload"
               type="file" name="photo" id="photo"
               data-validator="<?= $clientValidator('photo'); ?>"
            />
        <label for="photo" class="file-block--label"><?= $i18n->m('choose_file'); ?></label>
        <a href="#" class="file-block--clear-file photo-clear"><?= $i18n->m('clear')?></a>
        <span class="file-block--filename photo-filename"></span>
        <br>
        <img src="" class="file-block--image file-block--image-hidden" />
        <div class="form-row--error-message"><?= $getError('photo'); ?></div>
    </div>

    <div class="form-row login-form--row--submit">
        <input type="submit" name="submit" id="submit" class="form-row--submit" value="<?= $i18n->m('register'); ?>"/>
    </div>

</form>
