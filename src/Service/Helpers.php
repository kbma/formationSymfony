<?php

namespace App\Service;

class Helpers
{
    public function __construct(private $lang)
    {
    }
    public function sayHello()
    {
        return "Hello";
    }
}
