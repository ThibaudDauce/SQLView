<?php

use ThibaudDauce\SQLView\Blueprint;
use Illuminate\Database\Query\Grammars\Grammar;
use ThibaudDauce\SQLView\Grammars\Grammar as ViewGrammar;
use Illuminate\Database\Query\Processors\Processor;

class BlueprintTest extends PHPUnit_Framework_TestCase {

  public $connection;
  public $blueprint;

  public function setUp()
  {
    parent::setUp();

    $this->connection = $this->getMockConnection();
    $this->connection->setQueryGrammar(new Grammar);
    $this->connection->setPostProcessor(new Processor);

    $this->grammar = new ViewGrammar;

    $this->blueprint = new Blueprint('view', $this->connection);
  }

  public function tearDown()
  {
    //
  }

  public function testConstruct()
  {
    $this->assertEquals('view', $this->blueprint->getView());
  }

  /**
   * @expectedException \ThibaudDauce\SQLView\Exceptions\InvalidQueryException
  */
  public function testGetNoQuery() {

    $query = $this->blueprint->getQuery();
  }

  public function testAddQuery() {

    $query = $this->blueprint->newQuery('test');
    $this->blueprint->setQuery($query);
    $this->assertEquals($query, $this->blueprint->getQuery());
  }

  public function testNoCommandBuild() {

    $this->blueprint->setQuery($this->blueprint->newQuery('test'));
    $statements = $this->blueprint->build($this->connection, $this->grammar);
    $this->assertEmpty($statements);

  }

  public function testSimpleSelect() {

    $this->blueprint->setQuery($this->blueprint->newQuery('test'));
    $this->blueprint->create();
    $statements = $this->blueprint->toSql($this->connection, $this->grammar);
    $this->assertEquals(array('create view "view" as select * from "test"'), $statements);
  }

  protected function getMockConnection($methods = array(), $pdo = null)
  {
    $pdo = $pdo ?: new DatabaseConnectionTestMockPDO;
    $defaults = array('getDefaultQueryGrammar', 'getDefaultPostProcessor', 'getDefaultSchemaGrammar');
    return $this->getMock('Illuminate\Database\Connection', array_merge($defaults, $methods), array($pdo));
  }

}

class DatabaseConnectionTestMockPDO extends PDO { public function __construct() {} }
