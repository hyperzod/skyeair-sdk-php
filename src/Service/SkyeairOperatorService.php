<?php

namespace Hyperzod\SkyeairSdkPhp\Service;

use Hyperzod\SkyeairSdkPhp\Enums\HttpMethodEnum;

class SkyeairOperatorService extends AbstractService
{
   /**
    * Get zip codes for an operator
    *
    * @param array $params
    *
    * @throws \Hyperzod\SkyeairSdkPhp\Exception\ApiErrorException if the request fails
    *
    */
   public function getZipCodes(array $params)
   {
      return $this->request(HttpMethodEnum::GET, 'operator/get-zipcodes', $params);
   }
}
