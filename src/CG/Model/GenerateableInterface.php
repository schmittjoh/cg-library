<?php

namespace CG\Model;

interface GenerateableInterface {
	
	/**
	 * Generates docblock based on provided information
	 * 
	 * @return Docblock
	 */
	public function generateDocblock();
}