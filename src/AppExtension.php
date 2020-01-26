<?php

namespace App;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Service\MonaieService;
class AppExtension extends AbstractExtension
{
    private $monaieService;
    public function __construct(MonaieService $monaieService)
    {
        $this->monaieService = $monaieService;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('currency_convert', [$this, 'formatPrice']),
        ];
    }

    public function formatPrice($number)
    {
        $price = $this->monaieService->convert($number);

        return $price;
    }
}
