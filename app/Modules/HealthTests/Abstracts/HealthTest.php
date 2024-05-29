<?php

namespace Otomaties\HealthCheck\Modules\HealthTests\Abstracts;

use Illuminate\Support\Str;

abstract class HealthTest
{
    protected array $defaultResponse = [];

    public function __construct() {
        $this->defaultResponse = [
            'status' => 'good',
            'badge' => [
                'label' => $this->category(),
                'color' => 'blue',
            ],
            'test' => $this->name(),
        ];
    }

    abstract public function name() : string;

    abstract public function category() : string;

    abstract public function passes() : bool;

    abstract public function passedResponse() : array;

    abstract public function failedResponse() : array;

    public function respond() : array
    {
        $passes = $this->passes();
        if (!$passes) {
            $response = $this->failedResponse();
            if ('good' === $response['status']) {
                if ($this->category() === 'Security') {
                    $response['status'] = 'critical';
                } else {
                   	$response['status'] = 'recommended';
                }
            }
            if ('critical' === $response['status']) {
                $response['badge']['color'] = 'red';
            }
        } else {
            $response = $this->passedResponse();
        }

        return $response;
    }

    public function active() : bool
    {
        $constant = 'OTOMATIES_HEALTH_CHECK_' . strtoupper(Str::snake($this->name())) . '_ACTIVE';
        $constantValue = $this->findVariable($constant);
        ray($constant);
        if ($constantValue !== null) {
            return filter_var($constantValue, FILTER_VALIDATE_BOOLEAN);
        }
        return true;
    }

    protected function findVariable(string $variableName) : ?string
    {
        if (defined($variableName)) {
            return constant($variableName);
        }
        if (isset($_SERVER[$variableName])) {
            return $_SERVER[$variableName];
        }
        if (isset($_ENV[$variableName])) {
            return $_ENV[$variableName];
        }
        
        return null;
    }
}
