<?php
 
class allfuncs {
public function generatePasskey($length = 15) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $res = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $res .= mb_substr($chars, $index, 1);
    }

    return $res;
}
}
?>