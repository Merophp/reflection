<?php
namespace Merophp\Reflection;

use ReflectionMethod;

/**
 * Extended version of the ReflectionMethod
 */
class MethodReflection extends ReflectionMethod
{

	/**
	 * @var ?DocCommentParser
	 */
	protected ?DocCommentParser $docCommentParser = null;

	/**
	 * The constructor, initializes the reflection class
	 *
	 * @param string $className Name of the method's class
	 * @param string $methodName Name of the method to reflect
	 */
	public function __construct($className, $methodName)
    {
		parent::__construct($className, $methodName);
	}

	/**
	 * Replacement for the original getParameters() method which makes sure
	 * that ParameterReflection objects are returned instead of the
	 * original ReflectionParameter instances.
	 *
	 * @return array of ParameterReflection Parameter reflection objects of the parameters of this method
	 */
	public function getParameters(): array
    {
		$extendedParameters = array();
		foreach (parent::getParameters() as $parameter) {
			$extendedParameters[] = new ParameterReflection([$this->getDeclaringClass()->getName(), $this->getName()], $parameter->getName());
		}
		return $extendedParameters;
	}

	/**
	 * Replacement for the original getParameter() method which makes sure
	 * that ParameterReflection objects are returned instead of the
	 * original ReflectionParameter instances.
     *
	 * @param string $parameterName
	 * @return ParameterReflection Parameter reflection objects of the parameters of this method
	 */
	public function getParameter(string $parameterName): ParameterReflection
    {
		return new ParameterReflection([$this->getDeclaringClass()->getName(), $this->getName()], $parameterName);
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
	 * @param string $tag Tag name to check for
	 * @return array Values of the given tag
	 */
	public function getTagValues(string $tag): array
    {
		return $this->getDocCommentParser()->getTagValues($tag);
	}

	/**
	 * Returns the description part of the doc comment
	 *
	 * @return string Doc comment description
	 */
	public function getDescription(): string
    {
		return $this->getDocCommentParser()->getDescription();
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
