<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class MonaieService
{
    private $currencys_rates;
    private $session;
    private $current_currency;
    private $propertyAccesor;
    public function __construct(SessionInterface $session)
    {
       $this->propertyAccesor = PropertyAccess::createPropertyAccessor();

        $this->currencys_rates = json_decode(file_get_contents('https://api.exchangeratesapi.io/latest'))->rates;
        $this->session = $session;

        if (!$session->get('current_currency')){
            $session->set('current_currency','EUR');
        }
        $this->current_currency = $session->get('current_currency');

    }

    public function convert($price)
    {
        if ($this->current_currency === 'EUR') return $price;
        $rate = $this->propertyAccesor->getValue($this->currencys_rates, $this->current_currency);
        $converted_price = $price * $rate;
        return $converted_price ;
    }

    public function setCurrentCurrency($currency)
    {
        $this->session->set('current_currency', $currency);
    }
}
