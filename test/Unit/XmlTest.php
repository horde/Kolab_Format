<?php
/**
 * Test the DOM based XML handler.
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

namespace Horde\Kolab\Format\Test\Unit;

use PHPUnit\Framework\TestCase;
use Horde_Kolab_Format_Factory;
use Horde_Kolab_Format_Exception_MissingUid;

/**
 * Test the DOM based XML handler.
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
class XmlTest extends TestCase
{
    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingUid
     */
    public function testMissingUid()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingUid::class);
        $factory = new Horde_Kolab_Format_Factory();
        $note = $factory->create('Xml', 'Note');
        $note->save(array());
    }

    public function testSave()
    {
        $factory = new Horde_Kolab_Format_Factory();
        $note = $factory->create('Xml', 'Note');
        $this->assertStringContainsString(
            '<note version="1.0">',
            $note->save(array('uid' => 'test'))
        );
    }

    public function testVersion()
    {
        $factory = new Horde_Kolab_Format_Factory();
        $note = $factory->create('Xml', 'Note');
        $this->assertEquals(2, $note->getVersion());
    }
}
