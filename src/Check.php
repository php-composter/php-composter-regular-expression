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

    const KEY_CONFIGURATION  = 'php-composter-regular-expression';
    const KEY_COMMIT_MESSAGE = 'commit-message';

    const ELEMENT_SUBJECT = 'subject';
    const ELEMENT_BODY    = 'body';

    const HOOK_HANDLERS = [
        Hook::COMMIT_MSG => 'commitMessage',
    ];

    const CONTENT_RETRIEVERS = [
        self::ELEMENT_SUBJECT => 'getSubject',
        self::ELEMENT_BODY    => 'getBody',
    ];

    /**
     * Dispatch the regular expression checks.
     *
     * @since 0.1.0
     */
    public function dispatch()
    {
        $config = $this->getExtraKey(self::KEY_CONFIGURATION);
        $check  = self::HOOK_HANDLERS[$this->hook];
        $this->$check($config);
    }

    protected function commitMessage($config)
    {
        $checks = array_key_exists(self::KEY_COMMIT_MESSAGE, $config)
            ? $config[self::KEY_COMMIT_MESSAGE]
            : [];

        foreach ($checks as $element => $rules) {
            $contentRetriever = self::CONTENT_RETRIEVERS[$element];
            $content          = $this->$contentRetriever();
            $this->checkRules($content, $rules);
        }

        $this->error('Aborting for now!', 1);
    }

    protected function getSubject()
    {
    }

    protected function getBody()
    {
    }

    protected function checkRules($content, $rules)
    {
        foreach ($rules as $condition => $filter) {

        }
    }
}
