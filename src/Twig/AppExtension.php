<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('phoneNumber', [$this, 'formatPhoneNumber']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function doSomething($value)
    {
        // ...
    }

    public function formatPhoneNumber($number)
    {
        if (strlen($number) === 6) {
            return implode('-', str_split($number, 2));
        }

        if (strlen($number) === 11) {
            $countryCode                   = substr($number, 0, 1);
            [$areaCode, $phoneNumberA]     = str_split(substr($number, 1, 6), 3);
            [$phoneNumberB, $phoneNumberC] = str_split(substr($number, 7, 4), 2);

            return "$countryCode ($areaCode) $phoneNumberA-$phoneNumberB-$phoneNumberC";
        }

        return $number;
    }
}
