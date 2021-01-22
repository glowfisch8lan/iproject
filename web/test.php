<?php
$name = '\.~/.txt';
var_dump(mb_substr($name, 0, mb_strpos($name, ".")));
?>
