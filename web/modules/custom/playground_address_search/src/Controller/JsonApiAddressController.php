<?php

namespace Drupal\playground_address_search\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Entity\Element\EntityAutocomplete;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class JsonApiAddressController {

  /**
   * @return JsonResponse
   */
  public function handleAutocomplete(Request $request) {
    $results = [];
    $input = $request->query->get('q');
    if (!$input) {
      return new JsonResponse($results);
    }
    if (strlen($input) <= 6) {
      return new JsonResponse($results);
    }

    $input = Xss::filter($input);

    // Use Address Search Service.
    $service = \Drupal::service('playground_address_search.connection');
    $addresses = $service->getAddresses($input);
    $isError = gettype($addresses) === 'array' ?? TRUE;

    if ($isError && array_key_exists('error', $addresses)) {
      return new JsonResponse($results);
    }
    else {
      $list = json_decode($addresses, FALSE);
      foreach ($list as $item) {
        $results[] = [
          'value' => $item->sla,
          'label' => $item->sla,
        ];
      }
      return new JsonResponse($results);
    }
  }

}
