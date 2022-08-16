<?php

namespace Horde\Kolab\Format\Test\Stub;

use Horde_Kolab_Format_Xml_Type_Integer;
use Horde_Kolab_Format_Xml;
use Horde_Kolab_Format_Xml_Type_String_MaybeMissing;

class IntegerNotEmpty extends Horde_Kolab_Format_Xml_Type_Integer
{
    protected $element = Horde_Kolab_Format_Xml_Type_String_MaybeMissing::class;
    protected $value = Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY;
}
