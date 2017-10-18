<?php
/**
 * PHP Composter Regular Expression Check.
 *
 * @package   PHPComposter\RegularExpression
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.brightnucleus.com/
 * @copyright 2017 Alain Schlesser, Bright Nucleus
 */

namespace PHPComposter\RegularExpression;

use PHPComposter\PHPComposter\BaseAction;
use PHPComposter\PHPComposter\Hook;
use PHPComposter\PHPComposter\Paths;

/**
 * Class Check.
 *
 * @since   0.1.0
 *
 * @package PHPComposter\RegularExpression
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
final class Check extends BaseAction
{

    const KEY_CONFIGURATION = 'php-composter-regular-expression';
    const KEY_COMMIT_MESSAGE = 'commit-message';

    const ELEMENT_SUBJECT = 'subject';
    const ELEMENT_BODY = 'body';

    const HOOK_HANDLERS = [
        Hook::COMMIT_MSG => 'commitMessage',
    ];

    const CONTENT_RETRIEVERS = [
        self::ELEMENT_SUBJECT => 'getSubject',
        self::ELEMENT_BODY    => 'getBody',
    ];

    const FILTER_DISPLAY_LENGTH = 40;

    /**
     * Dispatch the regular expression checks.
     *
     * @since 0.1.0
     *
     * @param array ...$args Array of arguments.
     */
    public function dispatch(...$args)
    {
        $config = $this->getExtraKey(self::KEY_CONFIGURATION);
        $check  = self::HOOK_HANDLERS[$this->hook];
        $this->$check($config, ...$args);
    }

    /**
     * Check a commit message.
     *
     * @since 0.1.0
     *
     * @param array $config Configuration array.
     */
    protected function commitMessage(array $config, $messageFile)
    {
        $messageFile = Paths::getPath('pwd') . $messageFile;

        if (! is_readable($messageFile)
            || false === $message = file_get_contents($messageFile)) {
            $this->error("Commit message is not readable from file \"{$messageFile}\"", 1);
        }

        $checks = array_key_exists(self::KEY_COMMIT_MESSAGE, $config)
            ? $config[self::KEY_COMMIT_MESSAGE]
            : [];

        foreach ($checks as $element => $rules) {
            $contentRetriever = self::CONTENT_RETRIEVERS[$element];
            $content          = $this->$contentRetriever($message);
            $this->checkRules($element, $content, $rules);
        }
    }

    /**
     * Get the subject line of the commit message.
     *
     * @since 0.1.0
     *
     * @param string $message Commit message.
     *
     * @return string
     */
    protected function getSubject($message)
    {
        $message = $this->stripComments($message);

        return strstr($message, "\n", true);
    }

    /**
     * Get the body of the commit message.
     *
     * @since 0.1.0
     *
     * @param string $message Commit message.
     *
     * @return string
     */
    protected function getBody($message)
    {
        $message = $this->stripComments($message);

        return preg_replace('/^.+\n/', '', $message);
    }

    /**
     * Strip the comments of a commit message.
     *
     * @since 0.1.0
     *
     * @param string $message Message to the strip the comments from.
     *
     * @return string Commit message without comments.
     */
    protected function stripComments($message)
    {
        return preg_replace('/^\#.*$\n?/m', '', $message);
    }

    /**
     * Check the rules against a given piece of content.
     *
     * @since 0.1.0
     *
     * @param string $element Element that is being checked.
     * @param string $content Content to check the rules against.
     * @param array  $rules   Rules to check.
     */
    protected function checkRules($element, $content, $rules)
    {
        foreach ($rules as $condition => $filter) {
            $matches   = [];
            $filter    = chr(1) . $filter . chr(1);
            $condition = $this->normalizeCondition($condition);
            $result    = preg_match($filter, $content, $matches);
            $passed    = (bool)$this->$condition($result, $matches);

            $filterDisplay = $this->truncateFilter($filter);
            $message       = "<comment>{$element}</comment> \"{$condition}\" \"<comment>{$filterDisplay}</comment>\".";

            if (! $passed) {
                $this->error(
                    "Failed to verify that {$message}",
                    1
                );
            }

            $this->success(
                $message,
                false
            );
        }
    }

    /**
     * Truncate the filter for display.
     *
     * @since 0.1.0
     *
     * @param string $filter Filter to truncate.
     *
     * @return string Truncated filter.
     */
    protected function truncateFilter($filter)
    {
        if (strlen($filter) < static::FILTER_DISPLAY_LENGTH) {
            return $filter;
        }

        return substr($filter, 0, static::FILTER_DISPLAY_LENGTH - 3) . '...';
    }

    /**
     * Normalizes the condition to be used as a method name.
     *
     * @since 0.1.0
     *
     * @param string $condition Condition to normalize.
     *
     * @return string Normalized condition that can be used as a method name.
     */
    protected function normalizeCondition($condition)
    {
        return str_replace('-', '_', $condition);
    }

    /**
     * Whether the regular expression has a match.
     *
     * This method can be used as a condition in the `composer.json` file.
     *
     * @since 0.1.0
     *
     * @param mixed $result  Result of the preg_match call.
     * @param array $matches Array of found matches.
     *
     * @return bool Whether the regular expression has a match.
     */
    protected function has($result, $matches)
    {
        return $result === 1;
    }

    /**
     * Whether the regular expression does not have a match.
     *
     * This method can be used as a condition in the `composer.json` file.
     *
     * @since 0.1.0
     *
     * @param mixed $result  Result of the preg_match call.
     * @param array $matches Array of found matches.
     *
     * @return bool Whether the regular expression does not have a match.
     */
    protected function has_not($result, $matches)
    {
        return $result !== 1;
    }
}
