<?php

require_once 'Human.php';

$bob = new Human('Bob');
$banana = new Food('banana');
$bob->eat($banana);
