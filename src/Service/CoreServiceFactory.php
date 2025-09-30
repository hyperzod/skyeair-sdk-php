<?php

namespace Hyperzod\SkyeairSdkPhp\Service;

/**
 * Service factory class for API resources in the root namespace.
 * @property SkyeairClientService $clientService
 * @property SkyeairOperatorService $operatorService
 */
class CoreServiceFactory extends AbstractServiceFactory
{
    /**
     * @var array<string, string>
     */
    private static $classMap = [
        'skyeairclient' => SkyeairClientService::class,
        'operator' => SkyeairOperatorService::class,
    ];

    protected function getServiceClass($name)
    {
        return \array_key_exists($name, self::$classMap) ? self::$classMap[$name] : null;
    }
}
