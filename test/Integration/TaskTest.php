<?php
/**
 * Test task handling within the Kolab format implementation.
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

namespace Horde\Kolab\Format\Test\Integration;

use Horde\Kolab\Format\Test\TestCase;

/**
 * Test task handling.
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
class TaskTest extends TestCase
{
    /**
     * Test basic task handling
     */
    public function testBasicTask()
    {
        $xml = $this->getFactory()->create('XML', 'task');

        // Load XML
        $task = file_get_contents(__DIR__ . '/../fixtures/task.xml');
        $result = $xml->load($task);
        // Check that the xml loads fine
        $this->assertEquals($result['body'], 'TEST');
    }
}
