<?php
/**
 * Test the decorator for time measurements.
 *
 * PHP version 5
 *
 * @category   Kolab
 * @package    Kolab_Format
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link       http://www.horde.org/libraries/Horde_Kolab_Format
 */

namespace Horde\Kolab\Format\Test\Unit\Decorator;

use Horde\Kolab\Format\Test\TestCase;
use Horde\Kolab\Format\Test\Stub\Log as StubLog;
use Horde_Kolab_Format_Decorator_Timed;
use Horde_Support_Timer;
use Horde_Kolab_Format;

/**
 * Test the decorator for time measurements.
 *
 * Copyright 2010-2017 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category   Kolab
 * @package    Kolab_Format
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link       http://www.horde.org/libraries/Horde_Kolab_Format
 */
class TimedTest extends TestCase
{
    public function testConstructor()
    {
        $obj = $this->getFactory()->create(
            'XML',
            'contact',
            array('timelog' => true)
        );
        $this->assertInstanceOf(Horde_Kolab_Format_Decorator_Timed::class, $obj);
    }

    public function testTimeSpent()
    {
        $timed = $this->_getTimedMock();
        $a = '';
        $timed->load($a);
        $this->assertIsFloat(
            $timed->timeSpent()
        );
    }

    public function testTimeSpentIncreases()
    {
        $timed = $this->_getTimedMock();
        $a = '';
        $timed->load($a);
        $t_one = $timed->timeSpent();
        $timed->save(array());
        $this->assertTrue(
            $t_one < $timed->timeSpent()
        );
    }

    public function testLogLoad()
    {
        $timed = $this->_getTimedMock();
        $a = '';
        $timed->load($a);
        $this->assertStringContainsString(
            'Kolab Format data parsing complete. Time spent:',
            array_pop($this->logger->log)
        );
    }

    public function testLogSave()
    {
        $timed = $this->_getTimedMock();
        $a = array();
        $timed->save($a);
        $this->assertStringContainsString(
            'Kolab Format data generation complete. Time spent:',
            array_pop($this->logger->log)
        );
    }

    public function testNoLog()
    {
        // What exactly is tested here?
        $this->markTestIncomplete('testNoLog\'s assertion needs to be defined');
        $mock = $this->createMock(Horde_Kolab_Format::class);
        $timed = new Horde_Kolab_Format_Decorator_Timed(
            $mock,
            new Horde_Support_Timer(),
            true
        );
        $a = array();
        $timed->save($a);
    }

    private function _getTimedMock()
    {
        $this->logger = new StubLog();
        return new Horde_Kolab_Format_Decorator_Timed(
            $this->createMock('Horde_Kolab_Format'),
            new Horde_Support_Timer(),
            $this->logger
        );
    }
}
