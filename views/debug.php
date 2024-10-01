<?php

declare(strict_types=1);

/** @var \app\Routing\ParsedRoute $route */
?>

<hr>
<h2>debug</h2>

<pre>
ParsedRoute:
<?php
var_dump($route); ?>

_SERVER:
<?php
var_dump($_SERVER); ?>

_ENV:
<?php
var_dump($_ENV); ?>

_GET:
<?php
var_dump($_GET); ?>

_POST:
<?php
var_dump($_POST); ?>
</pre>
