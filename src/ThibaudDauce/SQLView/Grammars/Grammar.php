<?php namespace ThibaudDauce\SQLView\Grammars;

use Illuminate\Support\Fluent;
use Illuminate\Database\Connection;
use ThibaudDauce\SQLView\Blueprint;
use Illuminate\Database\Grammar as BaseGrammar;

class Grammar extends BaseGrammar {

	/**
	* Wrap a view in keyword identifiers.
	*
	* @param  string  $view
	* @return string
	*/
	public function wrapView($view)
	{
		if ($table instanceof Blueprint) $view = $view->getView();

		if ($this->isExpression($view)) return $this->getValue($view);

		return $this->wrap($this->tablePrefix.$view);
	}

	/**
	* Compile a create view command.
	*
	* @param  \ThibaudDauce\SQLView\Blueprint  $blueprint
	* @param  \Illuminate\Support\Fluent       $command
	* @param  \Illuminate\Database\Connection  $connection
	* @return string
	*/
	public function compileCreate(Blueprint $blueprint, Fluent $command, Connection $connection)
	{
		$sql = 'create view '.$this->wrapView($blueprint).' as ';

		return $sql;
	}

}
