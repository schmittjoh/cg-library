<?php

namespace CG\Model;

interface DocblockInterface {
	
	/**
	 * @param Docblock|string $doc
	 * @return $this
	 */
	public function setDocblock($doc);

	/**
	 * @return Docblock|string
	 */
	public function getDocblock();
}