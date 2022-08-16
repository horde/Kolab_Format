<?php
/**
 * Test the XML envelope handler.
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

namespace Horde\Kolab\Format\Test\Unit\Xml;

use PHPUnit\Framework\TestCase;
use Horde_Kolab_Format_Factory;
use Horde_Kolab_Format_Exception;

/**
 * Test the XML envelope handler.
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
class EnvelopeTest extends TestCase
{
    public function testSave()
    {
        $this->assertStringContainsString(
            '<uid>test</uid>',
            $this->_getEnvelope()->save(
                array('uid' => 'test', 'type' => 'test')
            )
        );
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception
     */
    public function testMissingType()
    {
        $this->expectException(Horde_Kolab_Format_Exception::class);
        $this->assertStringContainsString(
            '<uid>test</uid>',
            $this->_getEnvelope()->save(array('uid' => 'test'))
        );
    }

    public function testType()
    {
        $this->assertStringContainsString(
            '<test version="1.0">',
            $this->_getEnvelope()->save(
                array('uid' => 'test', 'type' => 'test')
            )
        );
    }

    public function testXml()
    {
        $this->assertStringContainsString(
            '<testelement/>',
            $this->_getEnvelope()->save(
                array('uid' => 'test', 'type' => 'test', 'xml' => '<testelement/>')
            )
        );
    }

    private function _getEnvelope()
    {
        $factory = new Horde_Kolab_Format_Factory();
        return $factory->create('Xml', 'Envelope');
    }
}
