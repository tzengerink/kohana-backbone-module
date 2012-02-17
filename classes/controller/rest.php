<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Simple REST controller that maps the different request methods to the
 * correct action. Before doing this the controller checks if the requested
 * method / action exists.
 *
 * @package   Backbone
 * @category  Controller
 * @author    T. Zengerink
 */
class Controller_REST extends Controller {

	/**
	 * @var  array  map of methods
	 */
	protected $_method_map = array
	(
		Request::POST   => 'create',
		Request::GET    => 'read',
		Request::PUT    => 'update',
		Request::DELETE => 'delete',
	);

	/**
	 * Checks if the requested method is in the method map and if the mapped
	 * action is also declared in the controller. Throws a HTTP Exception 405
	 * if not so.
	 *
	 * @throws  HTTP_Exception_405
	 */
	public function before()
	{
		// Execute parent's before method
		parent::before();

		// Check if request is allowed, otherwise throw a HTTP_Exception_405 
		$allowed = array_key_exists($this->request->method(), $this->_method_map);
		$exists	= method_exists($this, 'action_'.Arr::get($this->_method_map, $this->request->method()));
		if ( ! $allowed OR ! $exists)
		{
			throw new Http_Exception_405('Method :method not allowed.', array(
				':method' => $this->request->method())
			);
		}

		// Execute the correct CRUD action based on the requested method
		$this->request->action(Arr::get($this->_method_map, $this->request->method()));
	}

	/**
	 * Set the cache-control header, so the response will not be cached.
	 */
	public function after()
	{
		// Set headers to not cache anything
		$this->response->headers('cache-control', 'no-cache, no-store, max-age=0, must-revalidate');

		// Execute parent's after method
		parent::after();
	}

} // End Controller Rest
