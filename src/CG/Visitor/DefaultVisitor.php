<?php

/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace CG\Visitor;

use CG\Utils\Writer;
use CG\Model\PhpClass;
use CG\Model\PhpConstant;
use CG\Model\PhpProperty;
use CG\Model\PhpMethod;
use CG\Model\PhpFunction;
use CG\Model\AbstractPhpStruct;
use CG\Model\PhpTrait;
use CG\Model\NamespaceInterface;
use CG\Model\DocblockInterface;
use CG\Model\Docblock;
use CG\Model\TraitsInterface;
use CG\Model\PhpInterface;

/**
 * The default code generation visitor.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 */
class DefaultVisitor implements GeneratorVisitorInterface
{
    protected $writer;

    public function __construct()
    {
        $this->writer = new Writer();
    }

    public function reset()
    {
        $this->writer->reset();
    }
    
    private function ensureBlankLine() {
    	if (!$this->writer->endsWith("\n\n") && strlen($this->writer->rtrim()->getContent()) > 0) {
    		$this->writer->writeln();
    	}
    }
    
    private function visitNamespace(NamespaceInterface $model) {
    	if ($namespace = $model->getNamespace()) {
    		$this->writer->writeln('namespace '.$namespace.';');
    	}
    }
    
    private function visitRequiredFiles(AbstractPhpStruct $struct) {
    	if ($files = $struct->getRequiredFiles()) {
    		$this->ensureBlankLine();
    		foreach ($files as $file) {
    			$this->writer->writeln('require_once '.var_export($file, true).';');
    		}
    	}
    }
    
    private function visitUseStatements(AbstractPhpStruct $struct) {
    	if ($useStatements = $struct->getUseStatements()) {
    		$this->ensureBlankLine();
    		foreach ($useStatements as $alias => $namespace) {
    			$this->writer->write('use '.$namespace);
    	
    			if (substr($namespace, strrpos($namespace, '\\') + 1) !== $alias) {
    				$this->writer->write(' as '.$alias);
    			}
    	
    			$this->writer->write(";\n");
    		}
    	}
    }
    
    private function visitDocblock(DocblockInterface $model) {
    	if ($docblock = $model->getDocblock()) {
    		if ($docblock instanceof Docblock) {
    			$docblock = $docblock->build();
    		}
    		
    		if (!empty($docblock)) {
    			$this->ensureBlankLine();
    			$this->writer->writeln($docblock);
    		}
    	}
    }
    
    private function visitTraits(TraitsInterface $struct) {
    	foreach ($struct->getTraits() as $trait) {
    		$this->writer->write('uses ');
    		$this->writer->writeln($trait);
    	}
    }

	public function startVisitingClass(PhpClass $class) {
		$this->visitNamespace($class);
		$this->visitRequiredFiles($class);
		$this->visitUseStatements($class);
		$this->visitDocblock($class);

		// signature
		if ($class->isAbstract()) {
			$this->writer->write('abstract ');
		}

		if ($class->isFinal()) {
			$this->writer->write('final ');
		}

		$this->writer->write('class ');
		$this->writer->write($class->getName());

		if ($parentClassName = $class->getParentClassName()) {
			$this->writer->write(' extends ' . $parentClassName);
		}

		if ($class->hasInterfaces()) {
			$this->writer->write(' implements ');
			$this->writer->write(implode(', ', $class->getInterfaces()));
		}

		// body
		$this->writer->write("\n{\n")->indent();
		
		$this->visitTraits($class);
	}
	
	public function startVisitingInterface(PhpInterface $interface) {
		$this->visitNamespace($interface);
		$this->visitRequiredFiles($interface);
		$this->visitUseStatements($interface);
		$this->visitDocblock($interface);
	
		// signature
		$this->writer->write('interface ');
		$this->writer->write($interface->getName());
	
		if ($interface->hasInterfaces()) {
			$this->writer->write(' extends ');
			$this->writer->write(implode(', ', $interface->getInterfaces()));
		}
	
		// body
		$this->writer->write("\n{\n")->indent();
	}
	
	public function startVisitingTrait(PhpTrait $trait) {
		$this->visitNamespace($trait);
		$this->visitRequiredFiles($trait);
		$this->visitUseStatements($trait);
		$this->visitDocblock($trait);
	
		// signature
		$this->writer->write('trait ');
		$this->writer->write($trait->getName());

		// body
		$this->writer->write("\n{\n")->indent();
	
		$this->visitTraits($trait);
	}

    public function startVisitingStructConstants()
    {
    }

    public function visitStructConstant(PhpConstant $constant)
    {
        $this->writer->writeln('const '.$constant->getName().' = '.var_export($constant->getValue(), true).';');
    }

    public function endVisitingStructConstants()
    {
        $this->writer->write("\n");
    }

    public function startVisitingProperties()
    {
    }

    public function visitProperty(PhpProperty $property)
    {
    	$this->visitDocblock($property);

        $this->writer->write($property->getVisibility().' '.($property->isStatic()? 'static ' : '').'$'.$property->getName());

        if ($property->hasDefaultValue()) {
            $this->writer->write(' = '.var_export($property->getDefaultValue(), true));
        }

        $this->writer->writeln(';');
    }

    public function endVisitingProperties()
    {
        $this->writer->writeln();
    }

    public function startVisitingMethods()
    {
    }

    public function visitMethod(PhpMethod $method)
    {
        if ($docblock = $method->getDocblock()) {
            $this->writer->writeln($docblock)->rtrim();
        }

        if ($method->isAbstract()) {
            $this->writer->write('abstract ');
        }

        $this->writer->write($method->getVisibility().' ');

        if ($method->isStatic()) {
            $this->writer->write('static ');
        }

        $this->writer->write('function ');

        if ($method->isReferenceReturned()) {
            $this->writer->write('& ');
        }

        $this->writer->write($method->getName().'(');

        $this->writeParameters($method->getParameters());

        if ($method->isAbstract() || $method->getParent() instanceof PhpInterface) {
            $this->writer->write(");\n\n");

            return;
        }

        $this->writer
            ->writeln(")")
            ->writeln('{')
            ->indent()
            ->writeln($method->getBody())
            ->outdent()
            ->rtrim()
            ->write("}\n\n")
        ;
    }

    public function endVisitingMethods()
    {
    }

	private function endVisitingStruct(AbstractPhpStruct $struct) {
		$this->writer
			->outdent()
			->rtrim()
			->write('}')
		;
	}
	
	public function endVisitingClass(PhpClass $class) {
		$this->endVisitingStruct($class);
	}
	
	public function endVisitingInterface(PhpInterface $interface) {
		$this->endVisitingStruct($interface);
	}
	
	public function endVisitingTrait(PhpTrait $trait) {
		$this->endVisitingStruct($trait);
	}

    public function visitFunction(PhpFunction $function)
    {
        if ($namespace = $function->getNamespace()) {
            $this->writer->write("namespace $namespace;\n\n");
        }

        if ($docblock = $function->getDocblock()) {
            $this->writer->write($docblock)->rtrim();
        }

        $this->writer->write("function {$function->getName()}(");
        $this->writeParameters($function->getParameters());
        $this->writer
            ->write(")\n{\n")
            ->indent()
            ->writeln($function->getBody())
            ->outdent()
            ->rtrim()
            ->write('}')
        ;
    }

    public function getContent()
    {
        return $this->writer->getContent();
    }

    private function writeParameters(array $parameters)
    {
        $first = true;
        foreach ($parameters as $parameter) {
            if (!$first) {
                $this->writer->write(', ');
            }
            $first = false;

            if ($type = $parameter->getType()) {
                if ('array' === $type || 'callable' === $type) {
                    $this->writer->write($type . ' ');
                } else {
                    $this->writer->write(('\\' === $type[0] ? $type : '\\'. $type) . ' ');
                }
            }

            if ($parameter->isPassedByReference()) {
                $this->writer->write('&');
            }

            $this->writer->write('$'.$parameter->getName());

            if ($parameter->hasDefaultValue()) {
                $this->writer->write(' = ');
                $defaultValue = $parameter->getDefaultValue();

                if (is_array($defaultValue) && empty($defaultValue)) {
                    $this->writer->write('array()');
                } else {
                    $this->writer->write(var_export($defaultValue, true));
                }
            }
        }
    }
}
