<?php
/**
 * Filtro para extração de uma string XML inserida entre um texto genérico.
 * 
 * O texto resultante traz apenas a string correspondente ao documento XML (incluindo a tag inicial <?xml ?>).
 *
 * @package \UtilsBag\Filter
 */

 namespace UtilsBag\Filter;

use Laminas\Filter\FilterInterface;

class XmlFromText implements FilterInterface
{
    
    /**
     * Nome do elemento raiz do XML.
     * 
     * @var string
     */
    private $rootElementName;
    
    /**
     * Método construtor da classe.
     * 
     * @access public
     * @param string $rootElementName
     *              Nome do eleento raiz do XML.
     */
    public function __construct($rootElementName)
    {
        $this->rootElementName = $rootElementName;
    }
    
    /**
     * {@inheritDoc}
     * @see \Laminas\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        $xmlText = [];
        preg_match('/<\?xml.*\/' . $this->rootElementName . '>/', $value, $xmlText);
        return join('', $xmlText);
    }
}