<?php

namespace Hyperzod\SkyeairSdkPhp\Client;

use Hyperzod\SkyeairSdkPhp\Service\CoreServiceFactory;

class SkyeairClient extends BaseSkyeairClient
{
    /**
     * @var CoreServiceFactory
     */
    private $coreServiceFactory;

    public function __get($name)
    {
        if (null === $this->coreServiceFactory) {
            $this->coreServiceFactory = new CoreServiceFactory($this);
        }

        return $this->coreServiceFactory->__get($name);
    }
}
