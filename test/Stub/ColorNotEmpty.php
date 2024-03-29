<?php

namespace Horde\Kolab\Format\Test\Stub;

use Horde_Kolab_Format_Xml_Type_Color;
use Horde_Kolab_Format_Xml;

class ColorNotEmpty extends Horde_Kolab_Format_Xml_Type_Color
{
    protected $element = 'Horde_Kolab_Format_Xml_Type_String_MaybeMissing';
    protected $value = Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY;
}
