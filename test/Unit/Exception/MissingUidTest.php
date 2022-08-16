<?php
/**
 * Test the MissingUid exception.
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

namespace Horde\Kolab\Format\Test\Unit\Exception;

use PHPUnit\Framework\TestCase;
use Horde_Kolab_Format_Exception_MissingUid;

/**
 * Test the MissingUid exception.
 *
 * Copyright 2011-2017 Horde LLC (http://www.horde.org/)
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
class MissingUidTest extends TestCase
{
    public function testMissingUidTest()
    {
        $exception = new Horde_Kolab_Format_Exception_MissingUid();
        $this->assertEquals(
            'No UID in the Kolab XML object!',
            $exception->getMessage()
        );
    }
}
