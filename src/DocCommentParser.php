<?php
namespace Merophp\Reflection;

use RuntimeException;

/**
 * A little parser which creates tag objects from doc comments
 */
class DocCommentParser
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
	 * Parses the given doc comment and saves the result (description and
	 * tags) in the parser's object. They can be retrieved by the
	 * getTags() getTagValues() and getDescription() methods.
	 *
	 * @param string $docComment A doc comment as returned by the reflection getDocComment() method
	 * @return void
	 */
	public function parseDocComment(string $docComment)
    {
		$this->description = '';
		$this->tags = array();
		$lines = explode(chr(10), $docComment);
		foreach ($lines as $line) {
			if (strlen($line) > 0 && strpos($line, '@') !== FALSE) {
				$this->parseTag(substr($line, strpos($line, '@')));
			} elseif (count($this->tags) === 0) {
				$this->description .= preg_replace('/\\s*\\/?[\\\\*]*(.*)$/', '$1', $line) . chr(10);
			}
		}
		$this->description = trim($this->description);
	}

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
	 * Parses a line of a doc comment for a tag and its value.
	 * The result is stored in the interal tags array.
	 *
	 * @param string $line A line of a doc comment which starts with an @-sign
	 * @return void
	 */
	protected function parseTag(string $line)
    {
		$tagAndValue = preg_split('/\\s/', $line, 2);
		$tag = substr($tagAndValue[0], 1);
		if (count($tagAndValue) > 1) {
			$this->tags[$tag][] = trim($tagAndValue[1]);
		} else {
			$this->tags[$tag] = array();
		}
	}
}
