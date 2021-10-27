<?php
function loanCharge($min, $charge, $loan) {
    $lowerBound = $min;
    $backetRange = 1;
    $upperBound = 0;

    while ($loan > $upperBound){
        $lowerBound = $min * pow(2, ($backetRange - 1));
        $backetRange += 1;
        $upperBound = $min * pow(2, ($backetRange - 1));
    }

    return $lowerBound * ($charge / 100);
}
