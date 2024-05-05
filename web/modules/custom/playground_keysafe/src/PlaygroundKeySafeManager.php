<?php

namespace Drupal\playground_keysafe;

use Drupal\Core\Database\Connection;
use Drupal\Core\Database\StatementInterface;

/**
 * Defines an object that holds information about Keysafe Manager.
 */

class PlaygroundKeySafeManager implements PlaygroundKeyManagerInterface {
  /**
   * The database connection used to check the keys against.
   *
   * @var Connection
   */
  protected Connection $connection;

  public function __construct($connection) {
    $this->connection = $connection;
  }

  /**
   * @inheritDoc
   */
  public function keyExists($key): bool {
    return (boolean)$this->connection->query('SELECT * FROM {playground_keysafe} WHERE [keysafe_key] = :key', [":key" => $key])->fetchField();
  }

  /**
   * @inheritDoc
   */
  public function getKey($key): StatementInterface {
    return $this->connection->query('SELECT * FROM {playground_keysafe} WHERE [keysafe_key] = :key', [":key" => $key])->fetchField();
  }

  /**
   * @inheritDoc
   */
  public function fetchAll(): array {
    return $this->connection->query('SELECT * FROM {playground_keysafe}')->fetchAll();
  }

  /**
   * @inheritDoc
   */
  public function setKey($key, $value): int|string|null {
    return $this->connection->insert('playground_keysafe')->fields(['keysafe_key' => $key, 'keysafe_value' => $value])->execute();
  }

  /**
   * @inheritDoc
   */
  public function deleteKey($key): int|null|string {
    return $this->connection->delete('playground_keysafe')->condition('keysafe_key', $key)->execute();
  }

  /**
   * @inheritDoc
   */
  public function findById(int $keysafe_id): false|string {
    return $this->connection->query('SELECT [keysafe_key] FROM {playground_keysafe} WHERE [iid] = :iid', [':iid' => $keysafe_id])->fetchField();
  }


}
