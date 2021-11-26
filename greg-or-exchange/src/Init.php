<?php

namespace GregOrPlugin\GregOrExchange;

class Init
{
    public static function getServices(): array
    {
        return [
            PostTypes\ExchangeRate::class,
            Pages\Admin::class,
            Pages\SettingsLinks::class,
            Base\Activation::class,
            Base\Deactivation::class,
            Base\Enqueuer::class,
        ];
    }

    public static function registerServices(): void
    {
        foreach (self::getServices() as $class) {
            $service = self::instantiate($class);
            if (\method_exists($service, 'register')) {
                $service->register();
            }
        }
    }

    private static function instantiate(string $className)
    {
        $service = new $className();

        return $service;
    }
}
