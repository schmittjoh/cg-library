<?php
namespace CG\Model\Parts;

use gossi\docblock\DocBlock;

trait DocblockTrait {
	
	/**
	 * @var DocBlock
	 */
	private $docblock;

	/**
	 * @param DocBlock|string $doc
	 * @return $this
	 */
	public function setDocblock($doc) {
		if (is_string($doc)) {
			$doc = trim($doc);
			$doc = new DocBlock($doc);
		}
		$this->docblock = $doc;
	
		return $this;
	}
	
	/**
	 * 
	 * @return DocBlock
	 */
	public function getDocblock() {
		return $this->docblock;
	}
}