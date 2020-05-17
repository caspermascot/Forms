<?php


use Tests\TestLoginForm;

require(__DIR__ . '/vendor/autoload.php');
//die(phpinfo());
$form = new TestLoginForm();

print_r($form->asHtml());
//print_r(json_encode($form->asJson()));
//$form->name->setData('fer');
//$form->setData([
//    'ages'=>55,
//    'name'=>'frs',
//    'email'=>'jj@ghjk.vyi.buni',
//    'url'=>'http://foo.bar?q=Spaces should be encoded',
//    'phone'=>'00417493289',
//    'password'=>'Password1-',
//    'date'=>'2020-12-31',
//    'selects'=>'male,female'
//]);
//print_r($_POST);
$form->setData($_POST);
//print_r($_POST);die();
$form->isValid();
print_r(json_encode($form->getCleanedData()));
//
print_r(json_encode($form->getErrors()));

print_r($form->asHtml());

//echo '<html>
//    <head></head>
//    <body>
//    <form action="http://localhost/forms/" method="post">
//    Red<input type="checkbox" name="color[]" id="color" value="red">
//    Green<input type="checkbox" name="color[]" id="color" value="green">
//    Blue<input type="checkbox" name="color[]" id="color" value="blue">
//    Cyan<input type="checkbox" name="color[]" id="color" value="cyan">
//    Magenta<input type="checkbox" name="color[]" id="color" value="Magenta">
//    Yellow<input type="checkbox" name="color[]" id="color" value="yellow">
//    Black<input type="checkbox" name="color[]" id="color" value="black">
//    <input type="submit" value="submit">
//    </form>
//    <body>
//    </html>';

