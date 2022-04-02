<?php

/**
 * Filtro para converter a capitalização de strings multibyte, de acordo com
 * regras mais comuns utilizadas:
 * 
 * - tudo para minúsculas;
 * - tudo para maiúsculas;
 * - apenas a primeira letra de cada palavra em maíscula (capitalização de título)
 * - apenas a primeira letra da string para minúscula (não altera o resto)
 * - apenas a primeira letra da string para maíscula (não altera o resto)
 * 
 * @package \UtilsBag\Filter
 */

namespace UtilsBag\Filter;

use Laminas\Filter\FilterInterface;

class MultibyteCaseConvert implements FilterInterface
{
    /**
     * Modo de operação do filtro de conversão.
     * 
     * @access private
     * @var int
     */
    private $mode;
    
    /**
     * Método construtor da classe.
     * 
     * @access public
     * @param int $mode
     *              Modo de operação do filtro de conversão de capitalização
     */
    public function __construct($mode)
    {
        $this->mode = $mode;
    }
    
    /**
     * {@inheritDoc}
     * @see \Laminas\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        switch ($this->mode) {
            case CaseFilterOptions::UPPER:
                $string = mb_strtoupper($value);
                break;
            case CaseFilterOptions::LOWER:
                $string = mb_strtolower($value);
                break;
            case CaseFilterOptions::TITLE:
                $string = mb_convert_case($value, \MB_CASE_TITLE);
                break;
            case CaseFilterOptions::FIRST_UPPER:
                $string = mb_strtoupper(mb_substr($value, 0, 1)) . mb_substr($value, 1);
                break;
            case CaseFilterOptions::FIRST_LOWER:
                $string = mb_strtolower(mb_substr($value, 0, 1)) . mb_substr($value, 1);
                break;
            default:
                $string = $value;
                break;
        }
        return $string; 
    }
}