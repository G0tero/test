<?php
// the message
$msg = "First line of text\nSecond line of text";
//asdfsadfasdfasdf
// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("sorin.a@layershift.me","My subject",$msg);
?>
