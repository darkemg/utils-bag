<?php

/**
 * Filtro para normalização de CEP (apenas dígitos, ou com o separador "-" após os 5 primeiros dígitos)
 * 
 * @package UtilsBag\Filter
 */
namespace UtilsBag\Filter;

use Laminas\Filter\{Digits, FilterInterface};

class NormalizeCep implements FilterInterface
{

    /**
     * Define se o CEP deve conter o caracter separador "-" no valor filtrado.
     * 
     * @access private
     * @var bool
     */
    private $withSeparator;

    /**
     * Método construtor da classe
     * 
     * @access public
     * @param array $options
     *              Lista de opções para configuração do filtro. O array aceita as seguintes chaves:
     *              `with_separator`: Define se o CEP deve ser retornado com o separador. Padrão: false
     */
    public function __construct(array $options = [])
    {
        $this->withSeparator = array_key_exists('with_separator', $options)
            ? $options['with_separator']
            : false;
    }

    /**
     * {@inheritDoc}
     * @see \Laminas\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        $digits = new Digits();
        $cep = $digits->filter($value);
        if ($this->withSeparator) {
            $cep = sprintf('%s-%s', substr($cep, 0, 5), substr($cep, 5,3));
        }
        return $cep;
    }
}