<?php
/**
 * A dummy object type.
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

namespace Horde\Kolab\Format\Test\Stub;

use Horde_Kolab_Format_Xml_Type_DateTime;
use Horde_Kolab_Format_Xml;
use DOMNode;

/**
 * A dummy object type.
 *
 * Copyright 2007-2017 Horde LLC (http://www.horde.org/)
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
class Dummy extends Horde_Kolab_Format_Xml
{
    /**
     * Save the object creation date.
     *
     * @param DOMNode $node    The parent node to attach the child
     *                         to.
     * @param string  $name    The name of the node.
     * @param mixed   $value   The value to store.
     * @param boolean $missing Has the value been missing?
     *
     * @return DOMNode The new child node.
     */
    public function _saveValue($node, $name, $value, $missing)
    {
        $result  ='';
        $result .= $name . ': ';
        $result .= $value;
        if ($missing) {
            $result .= ', missing';
        }

        return $this->_saveDefault(
            $node,
            $name,
            $result,
            array('type' => self::TYPE_STRING)
        );
    }
}
