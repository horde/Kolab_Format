<?php
/**
 * Test the integer attribute handler.
 *
 * PHP version 5
 *
 * @category   Kolab
 * @package    Kolab_Format
 * @subpackage UnitTests
 * @author     Gunnar Wrobel <wrobel@pardus.de>
 * @license    http://www.horde.org/licenses/lgpl21 LGPL
 * @link       http://www.horde.org/libraries/Horde_Kolab_Format
 */

namespace Horde\Kolab\Format\Test\Unit\Xml\Type;

use Horde\Kolab\Format\Test\TestCase;
use Horde\Kolab\Format\Test\Stub\IntegerNotEmpty;
use Horde\Kolab\Format\Test\Stub\IntegerDefault;
use Horde_Kolab_Format_Exception_MissingValue;
use Horde_Kolab_Format_Exception;

/**
 * Test the integer attribute handler.
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
 * @license    http://www.horde.org/licenses/lgpl21 LGPL
 * @link       http://www.horde.org/libraries/Horde_Kolab_Format
 */
class IntegerTest extends TestCase
{
    public function testLoadInteger()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><integer>1</integer>c</kolab>'
        );
        $this->assertSame(1, $attributes['integer']);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception
     */
    public function testLoadStrangeInteger()
    {
        $this->expectException(Horde_Kolab_Format_Exception::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><integer type="strange"><b/>false<a/></integer>c</kolab>'
        );
    }

    public function testLoadMissingInteger()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>'
        );
        $this->assertFalse(isset($attributes['integer']));
    }

    public function testLoadDefault()
    {
        $attributes = $this->loadWithClass(
            IntegerDefault::class
        );
        $this->assertSame(10, $attributes['integer']);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testLoadNotEmpty()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $this->loadWithClass(IntegerNotEmpty::class);
    }

    public function testLoadNotEmptyRelaxed()
    {
        $attributes = $this->loadWithClass(
            IntegerNotEmpty::class,
            null,
            array('relaxed' => true)
        );
        $this->assertFalse(isset($attributes['integer']));
    }

    public function testSave()
    {
        $this->assertInstanceOf(
            'DOMNode',
            $this->saveToReturn(
                null,
                array('integer' => 1)
            )
        );
    }

    public function testSaveInteger()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"><integer>7</integer></kolab>
',
            $this->saveToXml(
                null,
                array('integer' => 7)
            )
        );
    }

    public function testSaveOverwritesOldValue()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><integer type="strange">7<b/><a/></integer>c</kolab>
',
            $this->saveToXml(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><integer type="strange"><b/>STRANGE<a/></integer>c</kolab>',
                array('integer' => 7)
            )
        );
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testSaveNotEmpty()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $this->saveWithClass(IntegerNotEmpty::class);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception
     */
    public function testSaveInvalidInteger()
    {
        $this->expectException(Horde_Kolab_Format_Exception::class);
        $this->saveWithClass(
            IntegerNotEmpty::class,
            null,
            array(),
            array('integer' => 'INVALID')
        );
    }

    public function testSaveNotEmptyWithOldValue()
    {
        $this->assertInstanceOf(
            'DOMNode',
            $this->saveWithClass(
                IntegerNotEmpty::class,
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><integer type="strange"><b/>STRANGE<a/></integer>c</kolab>'
            )
        );
    }

    public function testSaveNotEmptyRelaxed()
    {
        $this->assertFalse(
            $this->saveWithClass(
                IntegerNotEmpty::class,
                null,
                array('relaxed' => true)
            )
        );
    }

    protected function getTypeClass()
    {
        return 'Horde_Kolab_Format_Xml_Type_Integer';
    }
}
