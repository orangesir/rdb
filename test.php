<?php

$sql = "select * from a_table where a=? and b=? and c=?";

echo str_replace(array_pad(array(), 3, "?"), array(1, 2, 3), $sql);