<?php

use luya\bootstrap4\ActiveForm;
use luya\helpers\Html;

$form = ActiveForm::begin();
?>
<div class="container content pt-5 mb-5">
    <h1>Login</h1>
    <?= $form->field($model, 'password')->passwordInput(); ?>
    <?= Html::submitButton('Login', ['class' => 'btn btn-primary']); ?>
</div>
<?php $form::end(); ?>