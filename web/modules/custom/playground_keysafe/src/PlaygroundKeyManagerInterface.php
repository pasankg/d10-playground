<?php

namespace Drupal\playground_keysafe;

use Drupal\Core\Database\StatementInterface;

/**
 * Provides an interface to define a KeySafe manager.
 */
interface PlaygroundKeyManagerInterface {
  /**
   * Check Key exists.
   *
   * @param $key
   *    The unique Key to be checked.
   * @return boolean
   *    Returns TRUE if Key exists and FALSE if not.
   */
  public function keyExists($key): bool;

  /**
   * Get stored Key Value pair.
   * @param $key
   *    Unique Key.
   * @return StatementInterface
   *    The result of the database query.
   *
   */
  public function getKey($key): StatementInterface;

  /**
   * Fetch all stored key pairs from database.
   *
   * @return array
   */
  public function fetchAll(): array;

  /**
   * @param $key
   *    Unique Key.
   * @param $value
   *    Value of the unique key.
   * @return int|null|string;
   *    The result of the database query.
   *
   */
  public function setKey($key, $value): int|null|string;

  /**
   * @param $key
   *    Unique Key.
   *
   * @return int|string|null
   *    The result from the database query.
   */
  public function deleteKey($key): int|null|string;

  /**
   * Finds a Keysafe Key by its ID.
   *
   * @param int $keysafe_id
   *   The ID for a Keysafe key.
   *
   * @return string|false
   *   Either the Keysafe key or FALSE if none exist with that ID.
   */
  public function findById(int $keysafe_id): false|string;
}
