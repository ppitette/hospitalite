<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('phone', [$this, 'phoneFilter']),
        ];
    }

    public function phoneFilter(?string $phone, string $separator = '.'): string
    {
        $format_phone = ' ';

        if ($phone) {
            if (preg_match('#^0[1-9]{1}\d{8}$#', $phone)) {
                $format_phone = wordwrap($phone, 2, $separator, true);
            }
        }

        return $format_phone;
    }
}
