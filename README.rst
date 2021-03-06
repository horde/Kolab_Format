====================
 Horde_Kolab_Format
====================

The Horde_Kolab_Format package allows you to easily read and write the Kolab format using PHP.

--------------
 Installation
--------------

The package is being distributed as a standard `PEAR`_ package by the Horde project. As long as you have `PEAR`_ installed, installation should be straight forward.

.. _`PEAR`: http://pear.php.net
.. _`PEAR`: http://pear.php.net

::

  pear channel-discover pear.horde.org
  pear install horde/Horde_Kolab_Format

-------------------
 Using the package
-------------------

This section will present a short example script to demonstrate reading/writing an event in the Kolab XML format. The following assumes you have a working **PSR-0** compliant autoloader setup.
This will automatically pull in all required classes. You can of course use the default Horde setup:

::

  require_once 'Horde/Autoloader/Default.php';

Make sure you have the **Horde_Autoloader** package installed (**pear install horde/Horde_Autoloader**).

The API provided by the central **Horde_Kolab_Format** interface is very simple: it only provides a **load()** and a **save()** function.

In order to have access to these methods it is necessary to create a parser implementing the **Horde_Kolab_Format** interface. The **Horde_Kolab_Format_Factory** object is the helper that will generate a parser for you. The required calls look like this:

::

  $factory = new Horde_Kolab_Format_Factory();
  $format = $factory->create('Xml', 'event', array('version' => 1));

The first argument indicates the **format type**: Currently only **Xml** is supported here. The second argument specifies the desired type of object that should be read or written. The package currently implements **contact**, **distributionlist**, **event**, **note**, **task** and **hprefs**. The third argument holds a set of optional parameters for the parser. Here we specify that we expect the parser to adhere to the internal data API version 1.

The **$format** variable created above now provides the means to save and load events in Kolab XML format. In order to save an event we need to prepare an array with all relevant information about this event:

::

  $object = array(
      'uid' => 1,
      'summary' => 'test event',
      'start-date' => time(),
      'end-date' => time() + 24 * 60 * 60,
  );

This is an event that has the **UID** of **1** and carries the title **test event**. It starts right now (**time()**) and ends in a day (**time() + 24 * 60 * 60**).

This event can now be saved using the **save()** function of the format handler:

::

  $xml = $format->save($object);

The function returns the Kolab XML format as a result. This string can be fed back into the **load()** function:

::

  $read_object = $format->load($xml);

If we dump the contents of the two variables **$xml** and **$read_object** this will be the result:

::

  var_dump($xml);
  string(438) "<?xml version="1.0"?>
  <event version="1.0">
    <uid>1</uid>
    <body></body>
    <categories></categories>
    <creation-date>2008-07-10T12:51:51Z</creation-date>
    <last-modification-date>2008-07-10T12:51:51Z</last-modification-date>
    <sensitivity>public</sensitivity>
    <product-id>Horde::Kolab</product-id>
    <summary>test event</summary>
    <start-date>2008-07-10T12:51:51Z</start-date>
    <end-date>2008-07-11T12:51:51Z</end-date>
  </event>
  "
  
  var_dump($read_object);
  array(11) {
    ["uid"]=>
    string(1) "1"
    ["body"]=>
    string(0) ""
    ["categories"]=>
    string(0) ""
    ["creation-date"]=>
    int(1215694311)
    ["last-modification-date"]=>
    int(1215694311)
    ["sensitivity"]=>
    string(6) "public"
    ["product-id"]=>
    string(12) "Horde::Kolab"
    ["summary"]=>
    string(10) "test event"
    ["start-date"]=>
    int(1215694311)
    ["attendee"]=>
    array(0) {
    }
    ["end-date"]=>
    int(1215780711)
  }

We see that the format stores a lot more information than we originally provided. The resulting XML string does not only contain the **uid**, **summary**, **start-date**, and **end-date**. Several additional attributes have been added. These were either calculated or set to a default value.

* **body**: holds the event description. We did not specify an event description so this value has been set to an empty string.

* **sensitivity**: events may be **public** or **private** - with **public** being the default

* **categories**: Any Kolab object may be member of different categories. As we didn't specify a category this value is also empty.

* **creation-date**: The time stamp of the moment the object was created.

* **last-modification-date**: The time stamp of the moment the object was last modified.

* **product-id**: The ID of the product that last touched this object. If we use the **Horde_Kolab_Format** package it will always be **Horde::Kolab**.

If we read the XML data back into an array all these new informations are available within that array.

------------------------------------
 Creating your own Kolab XML format
------------------------------------

Currently the **Horde_Kolab_Format** implements the object types **contact**, **distributionslist**, **event**, **note**, **task** as they are defined within the [[Kolab Format]] specification. In addition the Horde specific **hprefs** type is available. It is used for storing Horde user preferences in the IMAP store provided by the Kolab server.

Depending on the web application you might wish to connect with the Kolab server these object types may not be enough. Do not hesitate to define your own new type in that case. If you want it to find wider distribution you should of course discuss it on the `Kolab Format mailing list`_ to get some feedback on the new type.

.. _`Kolab Format mailing list`: http://kolab.org/pipermail/kolab-format/

The **Horde_Kolab_Format** packages makes the definition of a new object type rather straight forward. The following will explain the creation of a very simple new object that only saves a single string value.

This time it will be necessary to load the XML format definition, too. Any new object type will extend this XML definition:

::

  require_once 'Horde/Kolab/Format.php';
  require_once 'Horde/Kolab/Format/XML.php';

A new object type is represented by a class that extends **Horde_Kolab_Format_XML**:

::

  class Horde_Kolab_Format_XML_string extends Horde_Kolab_Format_XML {
  
      var $_fields_specific;
  
      function Horde_Kolab_Format_XML_string()
      {
          $this->_root_name = 'string';
   
          /** Specific fields of this object type                           
           */
          $this->_fields_specific = array(
              'string' => array(
                  'type' => HORDE_KOLAB_XML_TYPE_STRING,
                  'value' => HORDE_KOLAB_XML_VALUE_MAYBE_MISSING,
              ),
          );
           
          parent::Horde_Kolab_Format_XML();
      }
  }

The class needs to end with the name of the object type. Here it is just **string**.

The declaration **var $_fields_specific;** indicates that the new object type has attributes beyond the basic set required for any Kolab object. So this part may not be missing for a declaration of a new type.

The function creating the class (**Horde_Kolab_Format_XML_string()**) needs to do three things:

* Declaring the XML root name which will be **string** here. It should always match the type name.

* Declaring the specific attributes of the object. This part populates the **_fields_specific** variable with an array describing the possible object attributes. This will be described in more detail [[Horde_Kolab_Format#Allowed fields|further below]].

* Calling the parent constructor using **parent::Horde_Kolab_Format_XML()**.

The new format can now be used as demonstrated in the initial event example:

::

  $format = Horde_Kolab_Format::factory('XML', 'string');
  $object = array(
      'uid' => 1,
      'string' => 'test string',
  );
  $xml = $format->save($object);
  $read_object = $format->load($xml);
  var_dump($xml);
  var_dump($read_object);

The result looks like this:

::

  string(347) "<?xml version="1.0"?>
  <string version="1.0">
    <uid>1</uid>
    <body></body>
    <categories></categories>
    <creation-date>2008-07-10T13:28:36Z</creation-date>
    <last-modification-date>2008-07-10T13:28:36Z</last-modification-date>
    <sensitivity>public</sensitivity>
    <product-id>Horde::Kolab</product-id>
    <string>test string</string>
  </string>
  "
  
  array(8) {
    ["uid"]=>
    string(1) "1"
    ["body"]=>
    string(0) ""
    ["categories"]=>
    string(0) ""
    ["creation-date"]=>
    int(1215696516)
    ["last-modification-date"]=>
    int(1215696516)
    ["sensitivity"]=>
    string(6) "public"
    ["product-id"]=>
    string(12) "Horde::Kolab"
    ["string"]=>
    string(11) "test string"
  }

----------------
 Allowed fields
----------------

There are only a number of valid entries available to specify the attributes a new object type may contain.

Each entry in the field list will look like this

::

  'attribute_name' => array(
      'type' => HORDE_KOLAB_XML_TYPE_*,
      'value' => HORDE_KOLAB_XML_VALUE_*,
  ),

**attribute_name** should be a short name describing the value that should be stored. '**type**' must be set to one of the following **HORDE_KOLAB_XML_TYPE_*** type values:

* **HORDE_KOLAB_XML_TYPE_STRING**: A string.

* **HORDE_KOLAB_XML_TYPE_INTEGER**: A number

* **HORDE_KOLAB_XML_TYPE_BOOLEAN**: True or false.

* **HORDE_KOLAB_XML_TYPE_DATE**: A date (e.g. 2008/08/08)

* **HORDE_KOLAB_XML_TYPE_DATETIME**: A time and a date.

* **HORDE_KOLAB_XML_TYPE_DATE_OR_DATETIME**: A date or a time and a date.

* **HORDE_KOLAB_XML_TYPE_COLOR**: A color (#00BBFF).

* **HORDE_KOLAB_XML_TYPE_COMPOSITE**: A composite element that combines several attributes.

* **HORDE_KOLAB_XML_TYPE_MULTIPLE**: Wrapper for an element that may occur several times.

Examples for **HORDE_KOLAB_XML_TYPE_COMPOSITE** and **HORDE_KOLAB_XML_TYPE_MULTIPLE** can be found in the definitions currently provided by the **Horde_Kolab_Format** package.

The following '**value**' settings are allowed:

* **HORDE_KOLAB_XML_VALUE_DEFAULT**: An attribute with a default value.

* **HORDE_KOLAB_XML_VALUE_MAYBE_MISSING**: An attribute that may be left undefined.

* **HORDE_KOLAB_XML_VALUE_NOT_EMPTY**: An attribute that will cause an error if it is left undefined.

* **HORDE_KOLAB_XML_VALUE_CALCULATE**: A complex attribute that gets its own function for calculating the correct value.

Examples for **HORDE_KOLAB_XML_VALUE_CALCULATE** can again be found in the current object types implemented in **Horde_Kolab_Format**.

-----------------------
 Internal API versions
-----------------------

TODO

-----------------------
 External API versions
-----------------------

TODO

---------------------
 Xml attribute types
---------------------

TODO

--------------------------------
 Detailed package documentation
--------------------------------

A detailed documentation based on the code comments and extracted via phpDocumentor can be found `here`_. Simply select the package Horde_Kolab_Format in the package selection box in the upper right corner.

.. _`here`: http://dev.horde.org/api/framework/

