<?php


class FieldValidationExcept extends Exception {
	protected $_field, $parameters;
	public function __construct($message="", $code=0 , Exception $previous=NULL, $field = NULL)
	{
		$this->_field = $field;
		parent::__construct($message, $code, $previous);
	}

	public function getField()
	{
		return $this->_field;
	}

	public function setParams(... $parameters) {
		$this->parameters = (array) $parameters;
	}

	public function getParams() {
		return $this->parameters;
	}
}