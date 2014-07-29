<?php namespace ThibaudDauce\SQLView;

class Blueprint {

  /**
   * The view the blueprint describes.
   *
   * @var string
   */
  protected $view;

  /**
   * Create a new view blueprint.
   *
   * @param  string   $view
   * @param  \Closure $callback
   * @return void
   */
  public function __construct($view, Closure $callback = null)
  {
    $this->view = $view;

    if ( ! is_null($callback)) $callback($this);
  }
}
