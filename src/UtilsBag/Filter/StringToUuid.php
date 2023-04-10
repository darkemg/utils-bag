<?php

/**
 * Formata uma string de acordo com a RFC4122 para UUIDs (formato "8-4-4-4-12").
 * 
 * Estre filtro NÃO gera o UUID; apenas aplica a formatação definida para UUIDs aos
 * primeiros 32 caracteres de uma string.
 * 
 * @package \UtilsBag\Filter
 */
namespace UtilsBag\Filter;

use Laminas\Filter\FilterChain;
use Laminas\Filter\StringToLower;
use Laminas\Filter\FilterInterface;
use Laminas\I18n\Filter\Alnum;

class StringToUuid implements FilterInterface
{

    private $rightPadChar;

    /**
     * Método construtor da classe.
     * 
     * @access public
     * @param array $options
     *              Lista de opções para configuração do filtro. O array aceita as seguintes chaves:
     *              right_pad_char: Define o caracter usado para preencher o texto informado até o tamanho de 32 caracteres. Padrão: '0';
     */
    public function __construct(array $options = [])
    {
        $this->rightPadChar = array_key_exists('right_pad_char', $options)
            ? $options['right_pad_char']
            : '0';
    }

    /**
     * {@inheritDoc}
     * @see \Laminas\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        $uuid = (new StringToLower())->filter(str_pad($value, 32, $this->rightPadChar, STR_PAD_RIGHT));
        return sprintf('%s-%s-%s-%s-%s',
            substr($uuid, 0, 8),
            substr($uuid, 8, 4),
            substr($uuid, 12, 4),
            substr($uuid, 16, 4),
            substr($uuid, 20, 12));
    }
}