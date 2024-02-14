<?php

use yii\helpers\Html;

$this->title = 'Help Center';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="help-index">

    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc sed purus quis dolor rutrum mattis a vitae lorem. Donec venenatis, libero et tristique ullamcorper, mauris mauris consequat turpis, eu luctus nisi nibh sit amet sapien. Morbi odio sem, laoreet non nunc nec, porttitor commodo nisi. Integer euismod lectus tortor, non interdum est volutpat eget. Sed pellentesque, sem et sollicitudin dapibus, leo dui tempor ante, eget malesuada erat est vel leo. Morbi condimentum elementum aliquet. Duis aliquet dictum sapien, eget congue risus pellentesque non.
    </p>

    <p>
        Suspendisse bibendum justo est, vitae iaculis velit viverra vitae. Mauris tristique turpis purus, vitae pellentesque elit varius non. Nunc blandit, urna quis posuere hendrerit, magna neque porta nibh, nec pulvinar dolor nisi id augue. Vestibulum feugiat lacus imperdiet, sodales leo in, tristique arcu. Etiam imperdiet lobortis purus eget faucibus. Suspendisse quis dapibus mauris. Ut nec imperdiet tortor. Phasellus scelerisque leo tincidunt leo sollicitudin, luctus lobortis augue consequat. Duis felis sem, condimentum et dignissim nec, feugiat quis elit. Aenean venenatis, nunc vitae volutpat aliquam, sem turpis ullamcorper nisl, sed scelerisque orci velit eu tellus. Donec in luctus mi. Nam id semper diam, vel ultricies metus. Morbi et nibh a nibh posuere feugiat quis quis dui. Etiam posuere, nunc in sollicitudin suscipit, augue dolor dignissim dui, ac convallis lectus magna ut sem. Morbi consectetur blandit feugiat.
    </p>

    <div>
        <?= Html::a('Account Settings', ['help/account-settings'])
        //a = metodo estatico(nome, url, caminho)
        //em div para deixar em coluna os links
        ?>
    </div>

    <div>
    <?= Html::a('Login and Security', ['help/login-and-security']) ?>
    </div>

    <div>
    <?= Html::a('Privacity', ['help/privacity']) ?>
    </div>


</div>