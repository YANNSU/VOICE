<?php

$db = mysqli_connect('localhost', 'root', '', 'opendata') or
die(mysqli_connect_error());
mysqli_set_charset($db, 'utf8');

?>