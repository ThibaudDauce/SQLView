<?php namespace ThibaudDauce\SQLView;

use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder {

  public $connection;

  /**
   * Create a new database View manager.
   *
   * @return void
   */
  public function __construct()
  {
    $this->connection = \DB::connection();
  }


  /**
   * Get the database connection.
   *
   * @return \Illuminate\Database\Connection
   */
  protected function getConnection()
  {
    return $this->connection;
  }

  /**
   * Get a new query builder instance for the connection.
   *
   * @return \Illuminate\Database\Query\Builder
   */
  protected function newBaseQueryBuilder()
  {
    $connection = $this->getConnection();

    $grammar = $connection->getQueryGrammar();

    return new QueryBuilder($connection, $grammar, $connection->getPostProcessor());
  }
}
