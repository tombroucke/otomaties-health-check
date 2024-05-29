<?php

namespace Otomaties\HealthCheck\Modules\HealthTests\Contracts;

interface HealthTest
{
    public function name() : string;

    public function category() : string;

    public function type() : string;

    public function passes() : bool;

    public function passedResponse() : array;

    public function failedResponse() : array;
    
    public function respond() : array;
}
