<?php
namespace Merophp\Reflection;

/**
 * A little parser which creates tag objects from doc comments
 */
class DocCommentParser
{
	/**
	 * Parses the given doc comment and saves the result (description and
	 * tags) in the parser's object. They can be retrieved by the
	 * getTags() getTagValues() and getDescription() methods.
	 *
	 * @param string $docComment A doc comment as returned by the reflection getDocComment() method
	 * @return ParsedDocComment
     */
	public function parseDocComment(string $docComment): ParsedDocComment
    {
        $parsedDocComment = new ParsedDocComment;
		$description = '';
		$tags = [];
		$lines = explode(chr(10), $docComment);
		foreach ($lines as $line) {
			if (strlen($line) > 0 && strpos($line, '@') !== FALSE) {
                $tags = array_merge($tags, $this->parseTag(substr($line, strpos($line, '@'))));
			} else{
				$description .= preg_replace('/\\s*\\/?[\\\\*]*(.*)$/', '$1', $line) . chr(10);
			}
		}
        $parsedDocComment->setTags($tags);
        $parsedDocComment->setDescription(trim($description));
        return $parsedDocComment;
	}

	/**
	 * Parses a line of a doc comment for a tag and its value.
	 *
	 * @param string $line A line of a doc comment which starts with an @-sign
	 * @return array
	 */
	protected function parseTag(string $line):array
    {
		$tagAndValue = preg_split('/\\s/', $line, 2);
		$tag = substr($tagAndValue[0], 1);
		if (count($tagAndValue) > 1) {
		    return [
                $tag => [
                    trim($tagAndValue[1])
                ]
            ];
		} else {
            return [
                $tag => []
            ];
		}
	}
}
