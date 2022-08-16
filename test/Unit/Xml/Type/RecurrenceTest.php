<?php
/**
 * Test the recurrence handler.
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
use Horde_Kolab_Format_Exception_ParseError;
use Horde_Kolab_Format_Exception_MissingValue;

/**
 * Test the recurrence handler.
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
class RecurrenceTest extends TestCase
{
    /**
     * @expectedException Horde_Kolab_Format_Exception_MissingValue
     */
    public function testEmptyInterval()
    {
        $this->expectException(Horde_Kolab_Format_Exception_MissingValue::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><recurrence>TEST</recurrence>c</kolab>'
        );
        $this->assertEquals(array(), $attributes['recurrence']);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_ParseError
     */
    public function testIntervalBelowZero()
    {
        $this->expectException(Horde_Kolab_Format_Exception_ParseError::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><recurrence><interval>-1</interval>TEST</recurrence>c</kolab>'
        );
        $this->assertEquals(array(), $attributes['recurrence']);
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_ParseError
     */
    public function testMissingCycle()
    {
        $this->expectException(Horde_Kolab_Format_Exception_ParseError::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><recurrence><interval>2</interval>TEST</recurrence>c</kolab>'
        );
    }

    /**
     * @expectedException Horde_Kolab_Format_Exception_ParseError
     */
    public function testMissingWeekday()
    {
        $this->expectException(Horde_Kolab_Format_Exception_ParseError::class);
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><recurrence cycle="weekly"><interval>1</interval>TEST</recurrence>c</kolab>'
        );
        $this->assertEquals(array(), $attributes['recurrence']);
    }

    public function testWeekly()
    {
        $attributes = $this->load(
            '<?xml version="1.0" encoding="UTF-8"?>
<kolab version="1.0" a="b"><recurrence cycle="weekly"><interval>1</interval><day>1</day>TEST</recurrence>c</kolab>'
        );
        $this->assertEquals(
            array(
                'complete' => '',
                'cycle' => 'weekly',
                'day' => array(1),
                'exclusion' => '',
                'interval' => '1',
                'type' => ''
            ),
            $attributes['recurrence']
        );
    }

    protected function getTypeClass()
    {
        return 'Horde_Kolab_Format_Xml_Type_Composite_Recurrence';
    }
}
