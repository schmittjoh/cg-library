<?php

namespace CG\Model;

use CG\Utils\Writer;
use phpDocumentor\Reflection\DocBlock\Tag\AuthorTag;
use phpDocumentor\Reflection\DocBlock\Tag\SeeTag;
use phpDocumentor\Reflection\DocBlock\Tag\ParamTag;
use phpDocumentor\Reflection\DocBlock\Tag\ThrowsTag;
use phpDocumentor\Reflection\DocBlock\Tag\ReturnTag;
use phpDocumentor\Reflection\DocBlock\Tag\VarTag;
use phpDocumentor\Reflection\DocBlock\Context;
use phpDocumentor\Reflection\DocBlock\Location;

/**
 * Represents a Docblock
 * 
 * @author Thomas Gossmann
 */
class Docblock extends \phpDocumentor\Reflection\DocBlock {

	public function __construct($docblock = null, Context $context = null, Location $location = null) {
		if ($docblock === null) {
			$docblock = '';
		}
		parent::__construct($docblock, $context, $location);
	}
	
	public function build() {
		$writer = new Writer();
		$writer->writeln('/**');
		$hasTags = false;
		
		// text
		$text = trim($this->getText());
		if (!empty($text)) {
			$chunks = explode("\n", wordwrap($text));
			foreach ($chunks as $line) {
				$writer->writeln(' * ' . $line);
			}
		} else {
			$hasTags = true;
		}
		
		// authors
		if ($this->hasTag('author')) {
			$this->prependBlankLine($writer, $hasTags);
			
			/* @var $author AuthorTag */
			foreach ($this->getTagsByName('author') as $author) {
				$writer->write(' * @author ' . $author->getAuthorName());
				$email = $author->getAuthorEmail();
				if (!empty($email)) {
					$writer->write(' <' . $email . '>');
				}
				$writer->writeln();
			}
		}

		// sees
		if ($this->hasTag('see')) {
			$this->prependBlankLine($writer, $hasTags);
			
			/* @var $author SeeTag */
			foreach ($this->getTagsByName('see') as $see) {
				$writer->write(' * @see');

				$reference = $see->getReference();
				if (!empty($reference)) {
					$writer->write(' ' . $reference);
				}
				
				$description = $see->getDescription();
				if (!empty($description)) {
					$writer->write(' ' . $description);
				}
				
				$writer->writeln();
			}
		}
		
		// params
		if ($this->hasTag('param')) {
			$this->prependBlankLine($writer, $hasTags);
			
			/* @var $param ParamTag */
			foreach ($this->getTagsByName('param') as $param) {
				$writer->write(' * @param');
				
				$type = $param->getType();
				if (!empty($type)) {
					$writer->write(' ' . $type);
				}
				
				$writer->write(' ' . $param->getVariableName());
				
				$description = $param->getDescription();
				if (!empty($description)) {
					$writer->write(' ' . $description);
				}
				
				$writer->writeln();
			}
		}
		
		// throws
		if ($this->hasTag('throws')) {
			$this->prependBlankLine($writer, $hasTags);
			
			/* @var $throw ThrowsTag */
			foreach ($this->getTagsByName('throws') as $throw) {
				$writer->write(' * @throw');
				
				$type = $throw->getType();
				if (!empty($type)) {
					$writer->write(' ' . $type);
				}
				
				$description = $throw->getDescription();
				if (!empty($description)) {
					$writer->write(' ' . $description);
				}
				
				$writer->writeln();
			}
		}
		
		// return
		if ($this->hasTag('return')) {
			$this->prependBlankLine($writer, $hasTags);
			
			/* @var $return ReturnTag */
			$return = $this->getTagsByName('return')[0];
			$writer->write(' * @return');
			
			$writer->write(' ' . $return->getType());
			
			$description = $return->getDescription();
			if (!empty($description)) {
				$writer->write(' ' . $description);
			}
			$writer->writeln();
		}
		
		// var
		if ($this->hasTag('var')) {
			$this->prependBlankLine($writer, $hasTags);
			
			/* @var $var VarTag */
			$var = $this->getTagsByName('var')[0];
			$writer->write(' * @var');
			$writer->write(' ' . $var->getType());
			
			$name = $var->getVariableName();
			if (!empty($name)) {
				$writer->write(' ' . $name);
			}
				
			$description = $var->getDescription();
			if (!empty($description)) {
				$writer->write(' ' . $description);
			}
			$writer->writeln();
		}
		
		$writer->write(' */');
		
		return $writer->getContent();
	}
	
	private function prependBlankLine(&$writer, &$flag) {
		if (!$flag) {
			$writer->writeln(' * ');
			$flag = true;
		}
	}
	
	public function __toString() {
		return $this->build();
	}
}