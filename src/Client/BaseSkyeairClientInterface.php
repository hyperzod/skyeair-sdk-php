<?php

namespace Hyperzod\SkyeairSdkPhp\Client;

/**
 * Interface for a Skyeair client.
 */
interface BaseSkyeairClientInterface
{
   /**
    * Gets the organization ID used by the client to send requests.
    *
    * @return null|string the organization ID used by the client to send requests
    */
   public function getOrganizationId();

   /**
    * Gets the organization name used by the client to send requests.
    *
    * @return null|string the organization name used by the client to send requests
    */
   public function getOrganizationName();

   /**
    * Gets the API key used by the client to send requests.
    *
    * @return null|string the API key used by the client to send requests
    */
   public function getApiKey();

   /**
    * Gets the base URL for Skyeair's API.
    *
    * @return string the base URL for Skyeair's API
    */
   public function getApiBase();
}
   