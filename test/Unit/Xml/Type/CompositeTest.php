<?php
/**
 * Test the handler for attributes with composite values.
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
use Horde\Kolab\Format\Test\Stub\Composite;
use Horde_Kolab_Format_Xml;
use Horde_Kolab_Format_Exception;
use Horde_Kolab_Format_Exception_MissingValue;
use Horde_Kolab_Format_Xml_Type_String_Empty;

/**
 * Test the handler for attributes with composite values.
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
class CompositeTest extends TestCase
{
    public function testLoadComposite()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"><composite><uid>a&amp;</uid><test>TEST</test></composite></kolab>',
            array(
                'array' => array(
                    'uid' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                    'test' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                ),
                'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
            )
        );
        $this->assertEquals(array('test' => 'TEST', 'uid' => 'a&'), $attributes['composite']);
    }

    public function testLoadDefault()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array(
                'array' => array(),
                'value' => Horde_Kolab_Format_Xml::VALUE_DEFAULT,
                'default' => array('X' => 'Y'),
            )
        );
        $this->assertEquals(array('X' => 'Y'), $attributes['composite']);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception
     */
    public function testLoadNotEmpty()
    {
        $this->expectException(Horde_Kolab_Format_Exception::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array(
                'array' => array(),
                'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
            )
        );
    }

    public function testLoadNotEmptyRelaxed()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array(
                'array' => array(
                    'type' => Horde_Kolab_Format_Xml::TYPE_STRING,
                ),
                'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
                'relaxed' => true
            )
        );
        $this->assertFalse(isset($attributes['composite']));
    }

    public function testSaveComposite()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"><composite><uid></uid><test></test></composite></kolab>
',
            $this->saveToXml(
                null,
                array('composite' => array()),
                array(
                    'array' => array(
                        'uid' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                        'test' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                    ),
                    'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
                )
            )
        );
    }

    public function testSaveMaybeMissing()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"><composite><uid>1</uid><test>&amp;</test></composite></kolab>
',
            $this->saveToXml(
                null,
                array('composite' => array('uid' => 1, 'test' => '&')),
                array(
                    'array' => array(
                        'uid' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                        'test' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                    ),
                    'value' => Horde_Kolab_Format_Xml::VALUE_MAYBE_MISSING,
                )
            )
        );
    }

    public function testSaveModifiesOldValue()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><composite type="strange"><b/>STRANGE<a/><uid>1</uid><test>&amp;</test></composite>c</kolab>
',
            $this->saveToXml(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><composite type="strange"><b/>STRANGE<a/></composite>c</kolab>',
                array('composite' => array('uid' => 1, 'test' => '&')),
                array(
                    'array' => array(
                        'uid' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                        'test' => 'Horde_Kolab_Format_Xml_Type_String_Empty',
                    ),
                    'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
                )
            )
        );
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testSaveNotEmpty()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $this->saveToXml(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array(),
            array(
                'array' => array(),
                'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
            )
        );
    }

    public function testSaveNotEmptyWithOldValue()
    {
        $this->assertInstanceOf(
            'DOMNode',
            $this->saveToReturn(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><composite type="strange"><b/>STRANGE<a/></composite>c</kolab>',
                array(),
                array(
                    'array' => array(),
                    'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
                )
            )
        );
    }

    public function testSaveNotEmptyRelaxed()
    {
        $this->assertFalse(
            $this->saveToReturn(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
                array(),
                array(
                    'array' => array(),
                    'value' => Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY,
                    'relaxed' => true,
                )
            )
        );
    }

    public function testDeleteComposite()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b">c<y/></kolab>
',
            $this->saveToXml(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><composite type="strange"><b/>STRANGE<a/></composite>c<y/><composite>a</composite></kolab>',
                array(),
                array(
                    'array' => array(),
                    'value' => Horde_Kolab_Format_Xml::VALUE_MAYBE_MISSING,
                )
            )
        );
    }

    protected function getTypeClass()
    {
        return Composite::class;
    }
}
