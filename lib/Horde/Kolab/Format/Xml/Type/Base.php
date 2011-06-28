<?php
/**
 * Utilities for the various XML handlers.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_Format
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_Format
 */

/**
 * Utilities for the various XML handlers.
 *
 * Copyright 2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you did not
 * receive this file, see
 * http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html.
 *
 * @since Horde_Kolab_Format 1.1.0
 *
 * @category Kolab
 * @package  Kolab_Format
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_Format
 */
class Horde_Kolab_Format_Xml_Type_Base
{
    /**
     * The XML document this object works with.
     *
     * @var DOMDocument
     */
    protected $_xmldoc;

    /**
     * The XPath query handler.
     *
     * @var DOMXpath
     */
    private $_xpath;

    /**
     * The parameters for this handler.
     *
     * @var array
     */
    private $_params;

    /**
     * Constructor
     *
     * @param DOMDocument $xmldoc The XML document this object works with.
     * @param array       $params Additional parameters for this handler.
     */
    public function __construct($xmldoc, $params = array())
    {
        $this->_xmldoc = $xmldoc;
        $this->_xpath = new DOMXpath($this->_xmldoc);
        $this->_params = $params;
    }

    /**
     * Return a parameter value.
     *
     * @param string $name The parameter name.
     *
     * @return mixed The parameter value.
     */
    public function getParam($name)
    {
        return isset($this->_params[$name]) ? $this->_params[$name] : null;
    }

    /**
     * Returns if the XML handling should be relaxed.
     *
     * @return boolean True if the XML should not be strict.
     */
    protected function isRelaxed()
    {
        return !empty($this->_params['relaxed']);
    }

    /**
     * Return a single named node matching the given XPath query.
     *
     * @param string $query The query.
     *
     * @return DOMNode|false The named DOMNode or empty if no node was found.
     */
    public function findNode($query)
    {
        return $this->_findSingleNode($this->findNodes($query));
    }

    /**
     * Return a single named node below the given context matching the given
     * XPath query.
     *
     * @param string  $query   The query.
     * @param DOMNode $context Search below this node.
     *
     * @return DOMNode|false The named DOMNode or empty if no node was found.
     */
    public function findNodeRelativeTo($query, DOMNode $context)
    {
        return $this->_findSingleNode(
            $this->findNodesRelativeTo($query, $context)
        );
    }

    /**
     * Return a single node for the result set.
     *
     * @param DOMNodeList $result The query result.
     *
     * @return DOMNode|false The DOMNode or empty if no node was found.
     */
    private function _findSingleNode($result)
    {
        if ($result->length) {
            return $result->item(0);
        }
        return false;
    }

    /**
     * Return all nodes matching the given XPath query.
     *
     * @param string $query The query.
     *
     * @return DOMNodeList The list of DOMNodes.
     */
    public function findNodes($query)
    {
        return $this->_xpath->query($query);
    }

    /**
     * Return all nodes matching the given XPath query.
     *
     * @param string  $query   The query.
     * @param DOMNode $context Search below this node.
     *
     * @return DOMNodeList The list of DOMNodes.
     */
    public function findNodesRelativeTo($query, DOMNode $context)
    {
        return $this->_xpath->query($query, $context);
    }
}