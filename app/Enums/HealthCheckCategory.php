<?php

namespace Otomaties\HealthCheck\Enums;

class HealthCheckCategory
{
    public const SECURITY = 'Security';
    public const PERFORMANCE = 'Performance';
    public const APPEARANCE = 'Appearance';
    public const SEO = 'Seo';
    public const SYSTEM = 'System';

    public static function all() : array
    {
        return [
            self::SECURITY,
            self::PERFORMANCE,
            self::APPEARANCE,
            self::SEO,
            self::SYSTEM,
        ];
    }

    public static function default() : string
    {
        return self::SYSTEM;
    }
}
