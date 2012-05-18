<?php defined('SYSPATH') or die('No direct script access.');
/**
 * The Backbone attributes array should contain all attributes that are used
 * on the JavaScript side of the application. Only these properties of the
 * model will be created, read and updated.
 *
 * @package   Backbone
 * @category  Model
 * @author    T. Zengerink
 */
class Model_Backbone extends ORM {

	/**
	 * @return  array  Attributes of the model
	 */
	public function backbone_attributes()
	{
		return array();
	}

	/**
	 * @param   array  input values
	 * @throws  ORM_Validation_Exception
	 */
	public function create_model($values)
	{
		return $this->values($values, $this->backbone_attributes())->create();
	}

	/**
	 * Returns an array with all models, each model only containing the 
	 * Backbone attributes
	 *
	 * @return  array  containing all models
	 */
	public function read_all()
	{
		$data = array();
		foreach ($this->find_all() as $model)
		{
			$data[] = $model->as_array();
		}
		return $data;
	}

	/**
	 * @param   array  input values
	 * @throws  ORM_Validation_Exception
	 */
	public function update_model($values)
	{
		return $this->values($values, $this->backbone_attributes())->update();
	}

	/**
	 * @return  array  containing the Backbone attributes
	 */
	public function as_array()
	{
		$data = array();
		foreach ($this->backbone_attributes() as $attribute)
		{
			$data[$attribute] = $this->$attribute;	
		}
		return $data;
	}

} // End Model Backbone
