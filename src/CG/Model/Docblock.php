<?php

namespace CG\Model;

use CG\Utils\Writer;
class Docblock {
	
	/**
	 * 
	 * @var string
	 */
	private $description;
	
	/**
	 * 
	 * @var string
	 */
	private $longDescription;
	
	/**
	 * 
	 * @var array;
	 */
	private $params = [];
	
	/**
	 * 
	 * @var string
	 */
	private $returnType;
	
	/**
	 * 
	 * @var string
	 */
	private $returnDescription;
	
	/**
	 * 
	 * @var array
	 */
	private $sees = [];
	
	/**
	 * 
	 * @var array
	 */
	private $authors = [];
	
	/**
	 * 
	 * @var array
	 */
	private $throws = [];
	
	
	/**
	 * 
	 * @var string
	 */
	private $varType;
	
	/**
	 * 
	 * @var string
	 */
	private $varDescription;
	
	public function __construct() {
		
	}
	
	/**
	 * @return Docblock
	 */
	public static function create() {
		return new static();
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 *
	 * @param string $description        	
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getLongDescription() {
		return $this->longDescription;
	}
	
	/**
	 *
	 * @param string $longDescription        	
	 */
	public function setLongDescription($longDescription) {
		$this->longDescription = $longDescription;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getReturnType() {
		return $this->returnType;
	}
	
	/**
	 *
	 * @param string $returnType        	
	 */
	public function setReturnType($returnType) {
		$this->returnType = $returnType;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getReturnDescription() {
		return $this->returnDescription;
	}
	
	/**
	 *
	 * @param string $returnDescription        	
	 */
	public function setReturnDescription($returnDescription) {
		$this->returnDescription = $returnDescription;
		return $this;
	}
	
	public function setReturn($type, $description) {
		$this->setReturnType($type);
		$this->setReturnDescription($description);
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getVarType() {
		return $this->varType;
	}
	
	/**
	 *
	 * @param $varType
	 */
	public function setVarType($varType) {
		$this->varType = $varType;
		return $this;
	}
	
	/**
	 *
	 * @return string
	 */
	public function getVarDescription() {
		return $this->varDescription;
	}
	
	/**
	 *
	 * @param $varDescription
	 */
	public function setVarDescription($varDescription) {
		$this->varDescription = $varDescription;
		return $this;
	}
	
	public function setVar($type, $description) {
		$this->setVarType($type);
		$this->setVarDescription($description);
		return $this;
	}
	
	public function addAuthor($name, $email = '') {
		$this->authors[] = [
			'name' => $name,
			'email' => $email
		];
		return $this;
	}
	
	public function addSee($location, $description) {
		$this->sees[] = [
			'location' => $location,
			'description' => $description
		];
		return $this;
	}
	
	public function addParam($var, $type, $description) {
		$this->params[] = [
			'var' => $var,
			'type' => $type,
			'description' => $description
		];
		return $this;
	}
	
	public function addThrow($type, $description) {
		$this->throws[] = [
			'type' => $type,
			'description' => $description
		];
		return $this;
	}
	
	public function toString() {
		$writer = new Writer();
		$writer->writeln('/**');
		
		// description
		if ($this->description) {
			$chunks = explode("\n", wordwrap($this->description));
			foreach ($chunks as $line) {
				$writer->writeln(' * ' . $line);
			}
		}
		
		// long description
		if ($this->longDescription) {
			if ($this->description) {
				$writer->writeln(' * ');
			}
			
			$chunks = explode("\n", wordwrap($this->longDescription));
			foreach ($chunks as $line) {
				$writer->writeln(' * ' . $line);
			}
		}
		
		// authors
		if (count($this->authors)) {
			$writer->writeln(' * ');
			
			foreach ($this->authors as $author) {
				$writer->write(' * @author ' . $author['name']);
				if (!empty($author['email'])) {
					$writer->write(' <' . $author['email'] . '>');
				}
				$writer->writeln();
			}
		}
		
		// sees
		if (count($this->sees)) {
			$writer->writeln(' * ');
			
			foreach ($this->sees as $see) {
				$writer->write(' * @see');

				if (!empty($see['location'])) {
					$writer->write(' ' . $see['location']);
				}
				
				if (!empty($see['description'])) {
					$writer->write(' ' . $see['description']);
				}
				
				$writer->writeln();
			}
		}
		
		// params
		if (count($this->params)) {
			$writer->writeln(' * ');
			
			foreach ($this->params as $param) {
				$writer->write(' * @param');
				
				if (!empty($param['type'])) {
					$writer->write(' ' . $param['type']);
				}
				
				$writer->write(' ' . $param['name']);
				
				if (!empty($param['description'])) {
					$writer->write(' ' . $param['description']);
				}
			}
		}
		
		// throws
		if (count($this->throws)) {
			$writer->writeln(' * ');
			
			foreach ($this->throws as $throw) {
				$writer->write(' * @throw');
				
				if (!empty($throw['type'])) {
					$writer->write(' ' . $throw['type']);
				}
				
				if (!empty($throw['description'])) {
					$writer->write(' ' . $throw['description']);
				}
				
				$writer->writeln();
			}
		}
		
		// return
		if ($this->returnType || $this->returnDescription) {
			$writer->writeln(' * ');
			$writer->write(' * @return');
			
			if ($this->returnType) {
				$writer->write(' ' . $this->returnType);
			}
			
			if ($this->returnDescription) {
				$writer->write(' ' . $this->returnDescription);
			}
		}
		
		// var
		if ($this->varType || $this->varDescription) {
			$writer->writeln(' * ');
			$writer->write(' * @var');
				
			if ($this->varType) {
				$writer->write(' ' . $this->varType);
			}
				
			if ($this->varDescription) {
				$writer->write(' ' . $this->varDescription);
			}
		}
		
		return $writer->getContent();
	}
}