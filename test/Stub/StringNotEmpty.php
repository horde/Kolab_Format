<?php

namespace Horde\Kolab\Format\Test\Stub;

use Horde_Kolab_Format_Xml_Type_String;
use Horde_Kolab_Format_Xml;
use Horde_Kolab_Format_Xml_Type_String_MaybeMissing;

class StringNotEmpty extends Horde_Kolab_Format_Xml_Type_String
{
    protected $element = Horde_Kolab_Format_Xml_Type_String_MaybeMissing::class;
    protected $value = Horde_Kolab_Format_Xml::VALUE_NOT_EMPTY;
}
