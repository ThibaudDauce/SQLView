<?php

use ThibaudDauce\SQLView\Blueprint;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;

class BlueprintTest extends PHPUnit_Framework_TestCase {

  public $connection;

  public function setUp()
  {
    parent::setUp();

    $this->connection = $this->getMockConnection();
    $this->connection->setQueryGrammar(new Grammar);
    $this->connection->setPostProcessor(new Processor);
  }

  public function tearDown()
  {
    //
  }

  public function testConstruct()
  {
    $blueprint = new Blueprint('view', $this->connection);

    $this->assertEquals('view', $blueprint->getView());
  }

  protected function getMockConnection($methods = array(), $pdo = null)
  {
    $pdo = $pdo ?: new DatabaseConnectionTestMockPDO;
    $defaults = array('getDefaultQueryGrammar', 'getDefaultPostProcessor', 'getDefaultSchemaGrammar');
    return $this->getMock('Illuminate\Database\Connection', array_merge($defaults, $methods), array($pdo));
  }

}

class DatabaseConnectionTestMockPDO extends PDO { public function __construct() {} }
