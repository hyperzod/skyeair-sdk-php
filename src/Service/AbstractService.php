<?php

namespace Hyperzod\SkyeairSdkPhp\Service;

/**
 * Abstract base class for all services.
 */
abstract class AbstractService
{
   /**
    * @var \Hyperzod\SkyeairSdkPhp\Client\SkyeairClientInterface
    */
   protected $client;

   /**
    * Initializes a new instance of the {@link AbstractService} class.
    *
    * @param \Hyperzod\SkyeairSdkPhp\Client\SkyeairClientInterface $client
    */
   public function __construct($client)
   {
      $this->client = $client;
   }

   /**
    * Gets the client used by this service to send requests.
    *
    * @return \Hyperzod\SkyeairSdkPhp\Client\SkyeairClientInterface
    */
   public function getClient()
   {
      return $this->client;
   }

   protected function request($method, $path, $params)
   {
      return $this->getClient()->request($method, $path, $params);
   }
}
