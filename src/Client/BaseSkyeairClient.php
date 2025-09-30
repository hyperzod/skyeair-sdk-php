<?php

namespace Hyperzod\SkyeairSdkPhp\Client;

use Exception;
use GuzzleHttp\Client;
use Hyperzod\SkyeairSdkPhp\Exception\InvalidArgumentException;

class BaseSkyeairClient implements SkyeairClientInterface
{

   /** @var array<string, mixed> */
   private $config;

   /**
    * Initializes a new instance of the {@link BaseSkyeairClient} class.
    *
    * The constructor takes two arguments.
    * @param string $api_key the API key of the client
    * @param string $api_base the base URL for Skyeair's API
    */

   public function __construct($organization_id, $organization_name, $api_key, $api_base)
   {
      $config = $this->validateConfig(array(
         "organization_id" => $organization_id,
         "organization_name" => $organization_name,
         "api_key" => $api_key,
         "api_base" => $api_base
      ));

      $this->config = $config;
   }

   /**
    * Gets the API key used by the client to send requests.
    *
    * @return null|string the API key used by the client to send requests
    */
   public function getApiKey()
   {
      return $this->config['api_key'];
   }

   /**
    * Gets the base URL for Skyeair's API.
    *
    * @return string the base URL for Skyeair's API
    */
   public function getApiBase()
   {
      return $this->config['api_base'];
   }

   /**
    * Gets the organization ID used by the client to send requests.
    *
    * @return null|string the organization ID used by the client to send requests
    */
   public function getOrganizationId()
   {
      return $this->config['organization_id'];
   }

   /**
    * Gets the organization name used by the client to send requests.
    *
    * @return null|string the organization name used by the client to send requests
    */
   public function getOrganizationName()
   {
      return $this->config['organization_name'];
   }

   /**
    * Sends a request to Skyeair's API.
    *
    * @param string $method the HTTP method
    * @param string $path the path of the request
    * @param array $params the parameters of the request
    */

   public function request($method, $path, $params)
   {
      $api_key = $this->getApiKey();
      $organization_id = $this->getOrganizationId();
      $organization_name = $this->getOrganizationName();
      $api_base = $this->getApiBase();

      $client = new Client([
         'headers' => [
            'content-type' => 'application/json',
            'Authorization' => 'Basic ' . $api_key
         ],
         'query' => [
            'OrganizationId' => $organization_id,
            'OrganizationName' => $organization_name
         ]
      ]);

      $api = $api_base . $path;

      $response = $client->request($method, $api, [
         'http_errors' => true,
         'body' => json_encode($params)
      ]);

      return $this->validateResponse($response);
   }

   /**
    * @param array<string, mixed> $config
    *
    * @throws InvalidArgumentException
    */
   private function validateConfig($config)
   {
      if (!isset($config['organization_id'])) {
         throw new InvalidArgumentException('organization_id field is required');
      }

      if (!isset($config['organization_name'])) {
         throw new InvalidArgumentException('organization_name field is required');
      }

      if (!is_string($config['organization_id'])) {
         throw new InvalidArgumentException('organization_id must be a string');
      }

      if ('' === $config['organization_id']) {
         throw new InvalidArgumentException('organization_id cannot be an empty string');
      }

      if (!is_string($config['organization_name'])) {
         throw new InvalidArgumentException('organization_name must be a string');
      }

      if ('' === $config['organization_name']) {
         throw new InvalidArgumentException('organization_name cannot be an empty string');
      }
      // api_key
      if (!isset($config['api_key'])) {
         throw new InvalidArgumentException('api_key field is required');
      }

      if (!is_string($config['api_key'])) {
         throw new InvalidArgumentException('api_key must be a string');
      }

      if ('' === $config['api_key']) {
         throw new InvalidArgumentException('api_key cannot be an empty string');
      }

      if (preg_match('/\s/', $config['api_key'])) {
         throw new InvalidArgumentException('api_key cannot contain whitespace');
      }

      if (!isset($config['api_base'])) {
         throw new InvalidArgumentException('api_base field is required');
      }

      if (!is_string($config['api_base'])) {
         throw new InvalidArgumentException('api_base must be a string');
      }

      if ('' === $config['api_base']) {
         throw new InvalidArgumentException('api_base cannot be an empty string');
      }

      return [
         "organization_id" => $config['organization_id'],
         "organization_name" => $config['organization_name'],
         "api_key" => $config['api_key'],
         "api_base" => $config['api_base'],
      ];
   }

   private function validateResponse($response)
   {
      $status_code = $response->getStatusCode();

      $body = json_decode($response->getBody(), true);

      if ($status_code >= 200 && $status_code < 300) {
         // Check for new error response structure with errorType and errorMessage
         if (isset($body['errorType']) && isset($body['errorMessage'])) {
            $errorDetails = $this->parseErrorMessage($body['errorMessage']);
            throw new Exception($errorDetails['message'] ?? $body['errorMessage']);
         }

         // Check for new response structure with statusCode and error fields
         if (isset($body['statusCode']) && $body['statusCode'] === 200 && $body['error'] === null) {
            return $body['Response'] ?? $body;
         }

         // Check for new response structure with error
         if (isset($body['error']) && $body['error'] !== null) {
            throw new Exception($body['error'] ?? 'Unknown error');
         }

         // Legacy support: check for old structure with type field
         if (isset($body['type']) && $body['type'] === 'success') {
            return $body;
         }

         // Legacy support: check for old errors structure
         if (isset($body['errors']) && is_array($body['errors']) && count($body['errors']) > 0) {
            throw new Exception($body['errors'][0]['message'] ?? 'Unknown error');
         }

         throw new Exception("Unknown error or unexpected response structure");
      } else {
         // Handle HTTP error status codes
         if (isset($body['errorType']) && isset($body['errorMessage'])) {
            $errorDetails = $this->parseErrorMessage($body['errorMessage']);
            throw new Exception($errorDetails['message'] ?? $body['errorMessage']);
         }

         if (isset($body['error']) && $body['error'] !== null) {
            throw new Exception($body['error'] ?? 'Unknown error');
         }

         if (isset($body['errors']) && is_array($body['errors']) && count($body['errors']) > 0) {
            throw new Exception($body['errors'][0]['message'] ?? 'Unknown error');
         }

         throw new Exception("Errors node not set in server response");
      }
   }

   /**
    * Parse the error message JSON string to extract error details
    *
    * @param string $errorMessage JSON string containing error details
    * @return array Parsed error details
    */
   private function parseErrorMessage($errorMessage)
   {
      $decoded = json_decode($errorMessage, true);

      if (json_last_error() !== JSON_ERROR_NONE) {
         return ['message' => $errorMessage];
      }

      if (isset($decoded['error']['message'])) {
         return ['message' => $decoded['error']['message']];
      }

      return ['message' => $errorMessage];
   }
}
