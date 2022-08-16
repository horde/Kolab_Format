<?php

namespace Horde\Kolab\Format\Test\Stub;

use Horde_Kolab_Format_Xml_Type_Boolean;
use Horde_Kolab_Format_Xml;

class BooleanDefault extends Horde_Kolab_Format_Xml_Type_Boolean
{
    protected $element = 'Horde_Kolab_Format_Xml_Type_String_MaybeMissing';
    protected $value = Horde_Kolab_Format_Xml::VALUE_DEFAULT;
    protected $default = true;
}
