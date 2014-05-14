<?php

namespace CG\Core;

use CG\Model\GenerateableInterface;

class CodeGenerator extends AbstractCodeGenerator {
	
	private $generateDocblock = true;
	
	public function getGenerateDocblock() {
		return $this->generateDocblock;
	}
	
	public function setGenerateDocblock($generateDocblock) {
		$this->generateDocblock = $generateDocblock;
		return $this;
	}
		
	/* (non-PHPdoc)
	 * @see \CG\Core\CodeGeneratorInterface::generateCode()
	 */
	public function generateCode(GenerateableInterface $model) {

		if ($this->generateDocblock) {
			$model->generateDocblock();
		}
		
		return $this->doGenerateCode($model);
	}
	
}