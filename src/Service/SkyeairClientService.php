<?php

namespace Hyperzod\SkyeairSdkPhp\Service;

use Hyperzod\SkyeairSdkPhp\Enums\HttpMethodEnum;

class SkyeairClientService extends AbstractService
{
   /**
    * Create manifestation details for a client on Skyeair
    *
    * @param array $params
    *
    * @throws \Hyperzod\SkyeairSdkPhp\Exception\ApiErrorException if the request fails
    *
    */
   public function createManifestationDetails(array $params)
   {
      return $this->request(HttpMethodEnum::POST, 'client/createmanifestationdetails', $params);
   }
}
