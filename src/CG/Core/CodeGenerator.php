<?php

namespace CG\Core;

use CG\Model\GenerateableInterface;

class CodeGenerator extends AbstractCodeGenerator {
	
	private $generateDocblock = true;
	
	/**
	 * Returns whether docblocks should be generated prior to code generation
	 * @return boolean
	 */
	public function getGenerateDocblock() {
		return $this->generateDocblock;
	}

	/**
	 * Sets whether docblocks should be generated prior to code generation
	 * @param boolean $generateDocblock
	 * @return CodeGenerator $this
	 */
	public function setGenerateDocblock($generateDocblock) {
		$this->generateDocblock = $generateDocblock;
		return $this;
	}

	public function generateCode(GenerateableInterface $model) {

		if ($this->generateDocblock) {
			$model->generateDocblock();
		}
		
		return $this->doGenerateCode($model);
	}
	
}