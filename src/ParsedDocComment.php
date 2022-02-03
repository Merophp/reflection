<?php
namespace Merophp\Reflection;

use RuntimeException;

/**
 * Class which holds doc comment information
 */
class ParsedDocComment
{

	/**
	 * @var string The description as found in the doc comment
	 */
	protected string $description = '';

	/**
	 * @var array An array of tag names and their values (multiple values are possible)
	 */
	protected array $tags = [];

    /**
	 * Returns the tags which have been previously parsed
	 *
	 * @return array Array of tag names and their (multiple) values
	 */
	public function getTagsValues(): array
    {
		return $this->tags;
	}

	/**
	 * Returns the values of the specified tag. The doc comment
	 * must be parsed with parseDocComment() before tags are
	 * available.
	 *
	 * @param string $tagName The tag name to retrieve the values for
	 * @return array The tag's values
	 * @throws RuntimeException
	 */
	public function getTagValues(string $tagName): array
    {
		if (!$this->isTaggedWith($tagName)) {
			throw new RuntimeException('Tag "' . $tagName . '" does not exist.', 1169128255);
		}
		return $this->tags[$tagName];
	}

	/**
	 * Checks if a tag with the given name exists
	 *
	 * @param string $tagName The tag name to check for
	 * @return boolean TRUE the tag exists, otherwise FALSE
	 */
	public function isTaggedWith(string $tagName): bool
    {
		return isset($this->tags[$tagName]);
	}

	/**
	 * Returns the description which has been previously parsed
	 *
	 * @return string The description which has been parsed
	 */
	public function getDescription(): string
    {
		return $this->description;
	}

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
}
