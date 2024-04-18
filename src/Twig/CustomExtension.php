<?php
// src/Twig/CustomExtension.php
namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
class CustomExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('removeSpecialCharsFilter', [$this, 'removeSpecialCharsFilter']),
        ];
    }
    public function removeSpecialCharsFilter($string) {
        return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
    }   
}
