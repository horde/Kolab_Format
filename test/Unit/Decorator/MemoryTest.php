<?php
/**
 * Test the decorator for memory measurements.
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
use Horde_Kolab_Format_Decorator_Memory;
use Horde_Support_Memory;
use Horde_Kolab_Format_Exception;

/**
 * Test the decorator for memory measurements.
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
class MemoryTest extends TestCase
{
    public function testConstructor()
    {
        $obj = $this->getFactory()->create(
            'XML',
            'contact',
            array('memlog' => true)
        );
        $this->assertInstanceOf('Horde_Kolab_Format_Decorator_Memory', $obj);
    }

    public function testLogLoad()
    {
        $timed = $this->_getMemoryMock();
        $a = '';
        $timed->load($a);
        $this->assertStringContainsString(
            'Kolab Format data parsing complete. Memory usage:',
            array_pop($this->logger->log)
        );
    }

    public function testLogSave()
    {
        $timed = $this->_getMemoryMock();
        $a = array();
        $timed->save($a);
        $this->assertStringContainsString(
            'Kolab Format data generation complete. Memory usage:',
            array_pop($this->logger->log)
        );
    }

    private function _getMemoryMock()
    {
        $this->logger = new StubLog();
        return new Horde_Kolab_Format_Decorator_Memory(
            $this->createMock('Horde_Kolab_Format'),
            new Horde_Support_Memory(),
            $this->logger
        );
    }
}
