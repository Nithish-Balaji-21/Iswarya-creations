<?php

namespace App\Support;

class IndianCurrency
{
    /**
     * Format a number with Indian currency grouping (e.g. ₹42,500 or ₹1,45,000).
     */
    public static function format(?float $amount, string $symbol = '₹'): string
    {
        if ($amount === null) {
            return 'Price on request';
        }

        $isNegative = $amount < 0;
        $amount = abs($amount);

        // Separate integer and decimal parts
        $rounded = number_format($amount, 2, '.', '');
        [$integerPart, $decimalPart] = explode('.', $rounded);

        // Indian digit grouping: last 3 digits, then chunks of 2
        if (strlen($integerPart) > 3) {
            $lastThree = substr($integerPart, -3);
            $remaining = substr($integerPart, 0, -3);
            $formattedRemaining = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $remaining);
            $formatted = $formattedRemaining.','.$lastThree;
        } else {
            $formatted = $integerPart;
        }

        // Only show decimals if not .00
        $decimalString = $decimalPart !== '00' ? '.'.$decimalPart : '';

        return ($isNegative ? '-' : '').$symbol.$formatted.$decimalString;
    }
}
