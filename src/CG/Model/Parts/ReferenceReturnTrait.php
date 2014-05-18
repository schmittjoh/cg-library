<?php

namespace CG\Model\Parts;

trait ReferenceReturnTrait {
	
	private $referenceReturned = false;

	/**
	 * Set true if a reference is returned of false if not
	 * 
	 * @param boolean $bool
	 * @return $this
	 */
	public function setReferenceReturned($bool) {
		$this->referenceReturned = (Boolean) $bool;
	
		return $this;
	}
	
	/**
	 * Returns whether a reference is returned
	 * 
	 * @return boolean
	 */
	public function isReferenceReturned() {
		return $this->referenceReturned;
	}

}