<?php namespace ThibaudDauce\SQLView;

use Closure;
use Illuminate\Support\Fluent;
use Illuminate\Database\Connection;
use Illuminate\Database\Schema\Grammars\Grammar;
use ThibaudDauce\SQLView\Grammars\Grammar as ViewGrammar;
use ThibaudDauce\SQLView\Exceptions\BlueprintNotReadyException;
use ThibaudDauce\SQLView\Exceptions\InvalidQueryException;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Blueprint {

  /**
   * The view the blueprint describes.
   *
   * @var string
   */
  protected $view;

  /**
   * The connection.
   *
   * @var string
   */
  protected $connection;

  /**
   * View's SQL statements.
   *
   * @var \Illuminate\Database\Query\Builder
   */
  protected $query;

  /**
   * The commands that should be run for the view.
   *
   * @var array
   */
  protected $commands = array();

  /**
   * Create a new view blueprint.
   *
   * @param  string   $view
   * @param  \Closure $callback
   * @return void
   */
  public function __construct($view, Connection $connection, Closure $callback = null)
  {
    $this->view = $view;
    $this->connection = $connection;

    if ( ! is_null($callback)) $callback($this);
  }

  /**
   * Get a new query builder instance for the connection.
   *
   * @return \Illuminate\Database\Query\Builder
   */
  protected function newBaseQueryBuilder(Connection $connection)
  {
    $grammar = $connection->getQueryGrammar();

    return new QueryBuilder($connection, $grammar, $connection->getPostProcessor());
  }

  /**
   * Get the view's SQL statement.
   *
   * @return string
   */
  public function getView()
  {
    return $this->view;
  }

  /**
   * Get the query the blueprint describes.
   *
   * @return \Illuminate\Database\Query\Builder
   */
  public function getQuery()
  {
    if (!isset($this->query))
      throw new InvalidQueryException("Call first setQuery method.");

    return $this->query;
  }

  /**
   * Set the query the blueprint describes.
   *
   * @param \Illuminate\Database\Query\Builder
   */
  public function setQuery($query)
  {
    $this->query = $query;
  }

  /**
   * Create a new query.
   *
   * @param string
   * @return \Illuminate\Database\Query\Builder
   */
  public function newQuery($table)
  {
    return $this->newBaseQueryBuilder($this->connection)->from($table);
  }

  /**
   * Execute the blueprint against the database.
   *
   * @param  \Illuminate\Database\Connection        $connection
   * @param  \ThibaudDauce\SQLView\Grammars\Grammar $grammar
   * @return void
   */
  public function build(Connection $connection, ViewGrammar $grammar)
  {
    foreach ($this->toSql($connection, $grammar) as $statement)
    {
      $connection->statement($statement);
    }
  }

  /**
   * Get the raw SQL statements for the blueprint.
   *
   * @param  \Illuminate\Database\Connection         $connection
   * @param  \ThibaudDauce\SQLView\Grammars\Grammar  $grammar
   * @return array
   */
  public function toSql(Connection $connection, ViewGrammar $grammar)
  {
    $statements = array();

    // Each type of command has a corresponding compiler function on the schema
    // grammar which is used to build the necessary SQL statements to build
    // the blueprint element, so we'll just call that compilers function.
    foreach ($this->commands as $command)
    {
      $method = 'compile'.ucfirst($command->name);

      if (method_exists($grammar, $method))
      {
        if ( ! is_null($sql = $grammar->$method($this, $command, $connection)))
        {
          $statements = array_merge($statements, (array) $sql);
        }
      }
    }

    return $statements;
  }

  /**
   * Indicate that the view needs to be created.
   *
   * @return \Illuminate\Support\Fluent
   */
  public function create()
  {
    return $this->addCommand('create');
  }

  /**
   * Indicate that the view should be dropped.
   *
   * @return \Illuminate\Support\Fluent
   */
  public function drop()
  {
    return $this->addCommand('drop');
  }

  /**
   * Add a new command to the blueprint.
   *
   * @param  string $name
   * @param  array  $parameters
   * @return \Illuminate\Support\Fluent
   */
  protected function addCommand($name, array $parameters = array())
  {
    $this->commands[] = $command = $this->createCommand($name, $parameters);

    return $command;
  }

  /**
   * Create a new Fluent command.
   *
   * @param  string  $name
   * @param  array   $parameters
   * @return \Illuminate\Support\Fluent
   */
  protected function createCommand($name, array $parameters = array())
  {
    return new Fluent(array_merge(compact('name'), $parameters));
  }


  /**
   * Determine if the blueprint has a create command.
   *
   * @return bool
   */
  protected function creating()
  {
    foreach ($this->commands as $command)
    {
      if ($command->name == 'create') return true;
    }

    return false;
  }
}
