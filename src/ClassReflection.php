<?php
namespace Merophp\Reflection;

use ReflectionClass;
use ReflectionException;

/**
 * Extended version of the ReflectionClass
 */
class ClassReflection extends ReflectionClass
{

	/**
	 * @var ?DocCommentParser Holds an instance of the doc comment parser for this class
	 */
	protected ?DocCommentParser $docCommentParser = null;

	/**
	 * The constructor - initializes the class
	 *
	 * @param string $className Name of the class Reflection to reflect
	 */
	public function __construct($className)
    {
		parent::__construct($className);
	}

	/**
	 * Replacement for the original getMethods() method which makes sure
	 * that MethodReflection objects are returned instead of the
	 * orginal ReflectionMethod instances.
	 *
	 * @param integer|NULL $filter A filter mask
	 * @return array Method reflection objects of the methods in this class
	 */
	public function getMethods($filter = NULL): array
    {
		$extendedMethods = array();
		$methods = $filter === NULL ? parent::getMethods() : parent::getMethods($filter);
		foreach ($methods as $method) {
			$extendedMethods[] = new MethodReflection($this->getName(), $method->getName());
		}
		return $extendedMethods;
	}

    /**
     * Replacement for the original getMethod() method which makes sure
     * that MethodReflection objects are returned instead of the
     * original ReflectionMethod instances.
     *
     * @param string $name
     * @return ?MethodReflection Method reflection object of the named method
     * @throws ReflectionException
     */
	public function getMethod($name): ?MethodReflection
    {
		$parentMethod = parent::getMethod($name);
		if (!is_object($parentMethod)) {
			return null;
		}
		return new MethodReflection($this->getName(), $parentMethod->getName());
	}

	/**
	 * Replacement for the original getConstructor() method which makes sure
	 * that MethodReflection objects are returned instead of the
	 * original ReflectionMethod instances.
	 *
	 * @return MethodReflection Method reflection object of the constructor method
	 */
	public function getConstructor(): ?MethodReflection
    {
		$parentConstructor = parent::getConstructor();
		if (!is_object($parentConstructor)) {
			return null;
		}
		return new MethodReflection($this->getName(), $parentConstructor->getName());
	}

	/**
	 * Replacement for the original getInterfaces() method which makes sure
	 * that ClassReflection objects are returned instead of the
	 * original ReflectionClass instances.
	 *
	 * @return array of ClassReflection Class reflection objects of the properties in this class
	 */
	public function getInterfaces(): array
    {
		$extendedInterfaces = [];
		$interfaces = parent::getInterfaces();
		foreach ($interfaces as $interface) {
			$extendedInterfaces[] = new ClassReflection($interface->getName());
		}
		return $extendedInterfaces;
	}

	/**
	 * Replacement for the original getParentClass() method which makes sure
	 * that a ClassReflection object is returned instead of the
	 * original ReflectionClass instance.
	 *
	 * @return ?ClassReflection Reflection of the parent class - if any
	 */
	public function getParentClass(): ?ClassReflection
    {
		$parentClass = parent::getParentClass();
		return $parentClass === FALSE ? NULL : new ClassReflection($parentClass->getName());
	}

	/**
	 * Checks if the doc comment of this method is tagged with
	 * the specified tag
	 *
	 * @param string $tag Tag name to check for
	 * @return boolean TRUE if such a tag has been defined, otherwise FALSE
	 */
	public function isTaggedWith(string $tag): bool
    {
        return $this->getDocCommentParser()->isTaggedWith($tag);
	}

	/**
	 * Returns an array of tags and their values
	 *
	 * @return array Tags and values
	 */
	public function getTagsValues(): array
    {
		return $this->getDocCommentParser()->getTagsValues();
	}

	/**
	 * Returns the values of the specified tag
	 *
	 * @param string $tag
	 * @return array Values of the given tag
	 */
	public function getTagValues(string $tag): array
    {
		return $this->getDocCommentParser()->getTagValues($tag);
	}

	/**
	 * Returns an instance of the doc comment parser and
	 * runs the parse() method.
	 *
	 * @return DocCommentParser
	 */
	protected function getDocCommentParser(): DocCommentParser
    {
		if (!is_object($this->docCommentParser)) {
			$this->docCommentParser = new DocCommentParser();
			$this->docCommentParser->parseDocComment($this->getDocComment());
		}
		return $this->docCommentParser;
	}
}
