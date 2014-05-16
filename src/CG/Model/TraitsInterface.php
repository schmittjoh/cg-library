<?php

namespace CG\Model;

interface TraitsInterface {
	/**
	 * Adds a trait. If the trait is passed as PhpTrait object,
	 * the trait is also added as use statement.
	 *
	 * @param PhpTrait|string $trait trait or qualified name
	 */
	public function addTrait($trait);
	
	public function getTraits();
	
	/**
	 * Adds a trait. If the trait is passed as PhpTrait object,
	 * the trait is also removed from use statements.
	 *
	 * @param PhpTrait|string $trait trait or qualified name
	 */
	public function removeTrait($trait);
	
	public function setTraits(array $traits);
}