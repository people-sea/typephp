--TEST--
Folded random maxima retain the PHP integer ABI when cast to float on Windows
--FILE--
<?php
function main(): void
{
    var_dump((float) mt_getrandmax());
    var_dump((float) getrandmax());
    var_dump((float) mt_getrandmax() / (float) getrandmax());
    var_dump((float) mt_rand(0, 0) / (float) mt_getrandmax());
}
?>
--EXPECT--
float(2147483647)
float(2147483647)
float(1)
float(0)
