<?php

namespace Merophp\Reflection;

use Reflector;

class ReflectionService
{
    /**
     * @var ?DocCommentParser Holds an instance of the doc comment parser for this class
     */
    protected ?DocCommentParser $docCommentParser = null;

    public function __construct()
    {
        $this->docCommentParser = new DocCommentParser();
    }

    /**
     * @param DocCommentParser $docCommentParser
     */
    public function injectDocCommentParser(DocCommentParser $docCommentParser)
    {
        $this->docCommentParser = $docCommentParser;
    }

    /**
     * Checks if the doc comment of this method is tagged with
     * the specified tag
     *
     * @param string $tag Tag name to check for
     * @return bool TRUE if such a tag has been defined, otherwise FALSE
     */
    public function isTaggedWith(Reflector $reflection, string $tag): bool
    {
        return $this->docCommentParser->parseDocComment($reflection->getDocComment())->isTaggedWith($tag);
    }

    /**
     * Returns an array of tags and their values
     *
     * @return array Tags and values
     */
    public function getTagsValues(Reflector $reflection): array
    {
        return $this->docCommentParser->parseDocComment($reflection->getDocComment())->getTagsValues();
    }

    /**
     * Returns the values of the specified tag
     *
     * @param Reflector $reflection
     * @param string $tag
     * @return array Values of the given tag
     */
    public function getTagValues(Reflector $reflection, string $tag): array
    {
        return $this->docCommentParser->parseDocComment($reflection->getDocComment())->getTagValues($tag);
    }
}
