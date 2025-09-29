<?php

namespace Hyperzod\SkyeairSdkPhp\Client;

/**
 * Interface for a Skyeair client.
 */
interface SkyeairClientInterface extends BaseSkyeairClientInterface
{
   /**
    * Sends a request to Skyeair's API.
    *
    * @param string $method the HTTP method
    * @param string $path the path of the request
    * @param array $params the parameters of the request
    */
   public function request($method, $path, $params);
}
