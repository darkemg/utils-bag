<?php

/**
 * Filtro para ocultar parte do número de um cartão de crédito, substituindo os dígitos por um caracter de máscara 
 * (por exenplo, *).
 *
 * Normalmente, ao se persistir dados de uma transação com cartão de crédito, é obrigatório "esconder" os dados do 
 * número e CVV para que não fiquem expostos. Este filtro providencia a máscara que esconde os dígitos do cartão de
 * crédito, permitindo parametrizar quantos caracteres ficam expostos no início e no fim da máscara.
 *
 * @package \UtilsBag\Filter
 */
namespace UtilsBag\Filter;

use Laminas\Filter\FilterInterface;

class CardNumberHide implements FilterInterface
{
    
    /**
     * Indica qual caracter será usado para mascarar o número do cartão (padrão: *)
     *
     * @access private
     * @var string
     */
    private $maskChar;
    /**
     * Quantos caracteres do início do número do cartão ficarão visíveis (padrão: os 6 primeiros ficam visíveis).
     *
     * @access private
     * @var int
     */
    private $visibleStart;
    /**
     * Quantos caracteres do final do número do cartão ficarão visíveis (padrão: os 4 últimos visíveis).
     *
     * @access private
     * @var int
     */
    private $visibleEnd;
    
    /**
     * Método construtor da classe.
     *
     * @access public
     * @param array $options
     *              Lista de opções para configuração do filtro. O array aceita as seguintes chaves:
     *              mask_char: Caracter de máscara do filtro. Padrão: *                        
     *              visible_start: Quantidade de caracteres do início do cartão que ficam visíveis. Padrão: 6
     *              visible_end: Quantidade de caracteres do final do cartão que ficam visíveis. Padrão: 4
     */
    public function __construct(array $options = [])
    {
        $this->maskChar = array_key_exists('mask_char', $options)
            ? $options['mask_char']
            : '*';
        $this->visibleStart = array_key_exists('visible_start', $options)
            ? $options['visible_start']
            : MaskFilterOptions::FIRST6;
        $this->visibleEnd = array_key_exists('visible_end', $options)
            ? $options['visible_end']
            : MaskFilterOptions::LAST4;
    }
    
    /**
     * {@inheritDoc}
     * @see \Laminas\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        $startVisible = substr($value, 0, $this->visibleStart);
        $endVisible = substr($value, ($this->visibleEnd * -1), strlen($value) - $this->visibleEnd);
        $mask = str_repeat($this->maskChar, strlen($value) - $this->visibleStart - $this->visibleEnd);
        $filteredCardNumber = sprintf(
            '%s%s%s',
            $startVisible,
            $mask,
            $endVisible
        );
        return $filteredCardNumber;
    }
}