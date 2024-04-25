<?php

namespace Drupal\playground_address_search\Services;

use Drupal;
use Drupal\Component\Utility\Xss;
use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Url;

class Connection {

  /**
   * Rapid API Hostname.
   */
  protected string $rapid_api_host;

  /**
   * Rapid API Key.
   */
  protected string $rapid_api_key;

  public function __construct(ConfigFactory $configFactory) {
    $config = $configFactory->getEditable('playground_address_search.settings');
    $this->rapid_api_host = $config->get('rapid_api_host') ?? NULL;
    $this->rapid_api_key = $config->get('rapid_api_key') ?? NULL;
  }

  /**
   * Returns a list of addresses matching the search string.
   *
   * @param string $query
   *    Address query to search.
   *
   * @return bool|string|true[]
   */
  public function getAddresses(string $query) {
    $curl = curl_init();

    if (!$this->checkSettings()) {
      \Drupal::logger('playground_address_search')
        ->error('Check settings; %message', [
          '%message' => Url::fromRoute('playground_address_search.settings')
            ->toString(),
        ]);
      // return \Drupal::messenger()->addError(t('Please Enter Address Manually.'));
      return ['error' => TRUE];
    }

    curl_setopt_array($curl, [
      CURLOPT_URL => "https://" . $this->rapid_api_host . "/addresses?q=" . Xss::filter($query),
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_POST => FALSE,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => [
        "X-RapidAPI-Host: " . $this->rapid_api_host,
        "X-RapidAPI-Key: " . $this->rapid_api_key,
      ],
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    $headers = curl_getinfo($curl);
    $http_code = $headers['http_code'];
    curl_close($curl);

    if ($err || $http_code !== 200) {
      \Drupal::logger('playground_address_search')
        ->error('Curl Error; %message', ['%message' => print_r($headers, TRUE)]);

      // Drupal::messenger()->addError(t('cURL Error: :s :d', [':s' => $err, ':d' => $http_code]));
      return ['error' => TRUE];
    }
    else {
      return $response;
    }
  }

  function checkSettings() {
    return $this->rapid_api_host && $this->rapid_api_key ?? TRUE;
  }

}
