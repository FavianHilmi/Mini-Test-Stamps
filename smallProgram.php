<?php
function primeNumber($n)
{
    if ($n < 2)
        return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i === 0)
            return false;
    }
    return true;
}

for ($i = 100; $i >= 1; $i--) {

    if (primeNumber($i)) continue;

    if ($i % 3 == 0 && $i % 5 == 0) {
        echo "FooBar";
    } elseif ($i % 3 == 0) {
        echo "Foo" . ", ";
    } elseif ($i % 5 == 0) {
        echo "Bar" . ", ";
    } else {
        echo $i . ", ";
    }
}