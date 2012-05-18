<?php defined('SYSPATH') or die('No direct script access.');
/**
 * For correct functioning of this controller you will have to make sure that
 * the properties, determining either the id's parameter name and the model's 
 * name, are set properly in your extending controller.
 *
 * [!!] By default the controller uses the parameter name 'id' and if no model
 *      is provided, it uses the name of the requested controller as the name
 *      for the model.
 *
 * @package   Backbone
 * @category  Controller
 * @author    T. Zengerink
 */
class Controller_Backbone extends Controller_REST {

	/**
	 * @var  string  id parameter name
	 */
	protected $_id_parameter_name = 'id';

	/**
	 * @var  array  input
	 */
	protected $_input;

	/**
	 * @var  Model_Backbone  model
	 */
	private $_model;

	/**
	 * @var  string  model name
	 */
	protected $_model_name = '';

	/**
	 * Checks if the model name is set and if the model extends Model_Backbone.
	 *
	 * @throws Kohana_Exception
	 */
	public function before()
	{
		// Execute parent's before method
		parent::before();

		// Set the model name to the controller name
		$this->_model_name = $this->_model_name ? $this->_model_name : $this->request->controller();

		// Check if the Model extends Model_Backbone
		if (get_parent_class(ORM::factory($this->_model_name)) !== 'Model_Backbone')
		{
			throw new Kohana_Exception(':class_name should extend Model_Backbone', array(
				':class_name'	=> get_class(ORM::factory($this->_model_name)),	
			));
		}

		// Set the input property
		$this->_input = (array) json_decode($this->request->body(), TRUE);

		// Set the model using the id parameter (and the id attribute if that is not given)
		$this->_model = ORM::factory($this->_model_name, $this->request->param($this->_id_parameter_name, Arr::get($this->_input, 'id')));
	}

	/**
	 * Creates a new model and returns it as JSON
	 */
	public function action_create()
	{
		try
		{
			// Create new model
			$this->_model->create_model($this->_input);
			
			// Return model as JSON
			$this->response->body(json_encode($this->_model->as_array()));
		}
		catch (Kohana_Exception $e)
		{
			// Return HTTP 400: Bad Request
			$this->response->status(400);
		}
	}

	/**
	 * Returns all models as JSON
	 */
	public function action_read()
	{
		try
		{
			// Read all models
			$this->response->body(json_encode($this->_model->read_all()));
		}
		catch (Kohana_Exception $e)
		{
			// Return HTTP 400: Bad Request
			$this->response->status(400);
		}
	}

	/**
	 * Updates an excisting model and returns it as JSON
	 */
	public function action_update()
	{
		try
		{
			// Update existing model
			$this->_model->update_model($this->_input);
			
			// Return model as JSON
			$this->response->body(json_encode($this->_model->as_array()));
		}
		catch (Kohana_Exception $e)
		{
			// Return HTTP 400: Bad Request
			$this->response->status(400);
		}
	}

	/**
	 * Deletes a model
	 */
	public function action_delete()
	{
		try
		{
			// Delete model
			$this->_model->delete();	
		}
		catch (Kohana_Exception $e)
		{
			// Return HTTP 400: Bad Request
			$this->response->status(400);
		}
	}

	/**
	 * Sets the content-type header to application/json
	 */
	public function after()
	{
		// Set headers to not cache anything
		$this->response->headers('content-type', 'application/json');

		// Execute parent's after method
		parent::after();
	}

} // End Controller Backbone
