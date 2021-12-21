<?php
namespace Merophp\Reflection;

use ReflectionParameter;

/**
 * Extended version of the ReflectionParameter
 */
class ParameterReflection extends ReflectionParameter
{

	/**
	 * The constructor, initializes the reflection parameter
	 *
	 * @param string|array $function
	 * @param string $parameterName
	 */
	public function __construct($function, string $parameterName)
    {
		parent::__construct($function, $parameterName);
	}

	/**
	 * Returns the parameter class
	 *
	 * @return ClassReflection The parameter class
	 */
	public function getClass(): ?ClassReflection
    {
        return parent::getType() && !parent::getType()->isBuiltin()
            ? new ClassReflection(parent::getType()->getName())
            : null;
	}
}
