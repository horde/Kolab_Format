<?php
/**
 * Test the "application" setting for preferences.
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
use Horde_Kolab_Format_Exception_MissingValue;

/**
 * Test the "application" setting for preferences.
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
class PrefsApplicationTest extends TestCase
{
    public function testLoadApplication()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><application>TEST</application>c</kolab>',
            array('element' => 'application')
        );
        $this->assertEquals('TEST', $attributes['application']);
    }

    public function testLoadCategories()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><categories>TEST</categories>c</kolab>',
            array('element' => 'application')
        );
        $this->assertEquals('TEST', $attributes['application']);
    }

    public function testLoadStrangeApplication()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><application type="strange"><b/>STRANGE<a/></application>c</kolab>',
            array('element' => 'application')
        );
        $this->assertEquals('STRANGE', $attributes['application']);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testLoadEmptyApplication()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"><application></application></kolab>',
            array('element' => 'application')
        );
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testLoadMissingApplication()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array('element' => 'application')
        );
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testLoadNotEmpty()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array('element' => 'application')
        );
    }

    public function testLoadNotEmptyRelaxed()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
            array(
                'element' => 'application',
                'relaxed' => true,
            )
        );
        $this->assertFalse(isset($attributes['application']));
    }

    public function testSave()
    {
        $this->assertInstanceOf(
            'DOMNode',
            $this->saveToReturn(
                null,
                array('application' => 'TEST'),
                array('element' => 'application')
            )
        );
    }

    public function testSaveXml()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"><application>TEST</application></kolab>
',
            $this->saveToXml(
                null,
                array('application' => 'TEST'),
                array('element' => 'application')
            )
        );
    }

    public function testSaveOverwritesOldValue()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><application type="strange">NEW<b/><a/></application>c</kolab>
',
            $this->saveToXml(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><application type="strange"><b/>STRANGE<a/></application>c</kolab>',
                array('application' => 'NEW'),
                array('element' => 'application')
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
            array('element' => 'application')
        );
    }

    public function testSaveNotEmptyWithOldValue()
    {
        $this->assertInstanceOf(
            'DOMNode',
            $this->saveToReturn(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><application type="strange"><b/>STRANGE<a/></application>c</kolab>',
                array(),
                array('element' => 'application')
            )
        );
    }

    public function testDeleteCategories()
    {
        $this->assertEquals(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b">c<application>CAT</application></kolab>
',
            $this->saveToXml(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><categories type="strange"><b/>CAT<a/></categories>c</kolab>',
                array('application' => 'CAT'),
                array('element' => 'application')
            )
        );
    }

    public function testSaveNotEmptyRelaxed()
    {
        $this->assertInstanceOf(
            'DOMNode',
            $this->saveToReturn(
                '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0"/>',
                array(),
                array(
                    'element' => 'application',
                    'relaxed' => true,
                )
            )
        );
    }

    protected function getTypeClass()
    {
        return 'Horde_Kolab_Format_Xml_Type_PrefsApplication';
    }
}
