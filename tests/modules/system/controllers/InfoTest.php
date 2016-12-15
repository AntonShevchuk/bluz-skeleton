<?php
/**
 * @copyright Bluz PHP Team
 * @link https://github.com/bluzphp/skeleton
 */

/**
 * @namespace
 */
namespace Application\Tests\System;

use Application\Tests\ControllerTestCase;

/**
 * @package  Application\Tests\System
 * @author   Anton Shevchuk
 * @created  04.08.11 19:52
 */
class InfoTest extends ControllerTestCase
{
    /**
     * Dispatch module/controller
     */
    public function testBookmarksPage()
    {
        self::setupSuperUserIdentity();

        $this->dispatch('/system/info/');
        self::assertModule('system');
        self::assertController('info');
        self::assertOk();
        self::assertQueryCount('iframe', 1);
    }
}
