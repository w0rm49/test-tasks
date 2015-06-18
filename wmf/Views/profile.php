<?php
/**
 * @var $user \Models\User
 * @var $i18n \Util\I18n
 */

$i18n = App::obj()->i18n;
?>
<h1 class="page--title"><?= $i18n->m('profile'); ?></h1>
<div class="user-block">
    <?php if($user->photo): ?>
        <img src="<?= $user->photo; ?>" class="user-photo" alt=""/>
    <?php endif; ?>

    <dl class="user-profile">
        <dt class="user-profile--term"><?= $i18n->m('name'); ?></dt><dd class="user-profile--value"><?= $user->name; ?></dd>
        <dt class="user-profile--term"><?= $i18n->m('surname'); ?></dt><dd class="user-profile--value"><?= $user->surname; ?></dd>
        <dt class="user-profile--term"><?= $i18n->m('email'); ?></dt><dd class="user-profile--value"><?= $user->email; ?></dd>
        <dt class="user-profile--term"><?= $i18n->m('gender'); ?></dt><dd class="user-profile--value"><?= $i18n->m($user->gender ? 'female' : 'male'); ?></dd>
        <dt class="user-profile--term"><?= $i18n->m('birth'); ?></dt><dd class="user-profile--value"><?= $user->birth; ?></dd>
    </dl>
</div>