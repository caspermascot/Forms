<?php


use Tests\LoginForm;

require(__DIR__ . '/vendor/autoload.php');
//die(phpinfo());
$form = new LoginForm();
//$form->name->setData('fer');
$form->setData([
    'age'=>55,
    'name'=>'frs',
    'email'=>'jj@ghjk.vyi.buni',
    'url'=>'http://foo.bar?q=Spaces should be encoded',
    'phone'=>'00417493289',
    'password'=>'Password1-',
    'date'=>'2020-12-31'
]);

$form->isValid();
print_r(json_encode($form->getCleanedData()));

print_r(json_encode($form->getErrors()));