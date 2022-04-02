<?php

/**
 * Filtro de transliteração para  de strings.
 *
 * Uma string transliterada é um texto onde certos caracteres especiais em um charset são traduzidos para outros 
 * caracteres correspondentes em outro charset. Por exemplo:
 * 
 * Á => A
 * ý => y
 * ¢ => EUR
 * 
 * @package \UtilsBag\Filter
 */
namespace UtilsBag\Filter;

use Laminas\Filter\FilterInterface;
use Symfony\Component\String\UnicodeString;

class Transliterator implements FilterInterface
{
    
    /**
     * Lista de caracteres delimitadores.
     * 
     * Os delimitadores são utilizados para indicar ao filtro que deve ser gerada uma separação de palavras caso estes
     * caracteres sejam encontrados. Por exemplo:
     * 
     * $delimiters = ["'"]
     * d'água = d agua
     * 
     * @access private
     * @var array
     */
    private $delimiters;
    /**
     * Caracter separador de palavras na transliteração.
     * 
     * @access private
     * @var string
     */
    private $separator;
    /**
     * Identificador do locale do sistema, no formato ISO 639-1.
     * Exemplo: pt_BR, en_US
     * 
     * @access private
     * @var string
     */
    private $locale;
    /**
     * Charset original da string a ser filtrada.
     * 
     * @access private
     * @var string
     */
    private $originalCharset;
    /**
     * Chartset-alvo para o qual a string deve ser transliterada.
     * 
     * @access private
     * @var string
     */
    private $targetCharset;
    /**
     * Expressão regular com os caracteres que devem ser mantidos na string após a transliteração.
     * 
     * @access private
     * @var string
     */
    private $regexClear;
    
    /**
     * Método construtor da classe.
     * 
     * @access public
     * @param array $options
     *              Lista de opções para configuração do filtro. O array aceita as seguintes chaves:
     *              delimiters: Array com a lista de caracteres delimitadores. Padrão: vazio
     *              separator: Caracter separador de palavras. Padrão: espaço em branco
     *              locale: Locale do sistema. Padrão: pt_BR
     *              original_charset: Charset original da string. Padrão: UTF-8
     *              target_charset: Charset alvo da string. Padrão: ASCII
     */
    public function __construct(array $options = [])
    {
        $this->delimiters = array_key_exists('delimiters', $options) 
            ? $options['delimiters'] 
            : [];
        $this->separator = array_key_exists('separator', $options) 
            ? $options['separator'] 
            : ' ';
        $this->locale = array_key_exists('locale', $options) 
            ? $options['locale'] 
            : 'pt_BR';
        $this->originalCharset = array_key_exists('original_charset', $options) 
            ? $options['original_charset'] 
            : 'UTF-8';
        $this->targetCharset = array_key_exists('target_charset', $options) 
            ? $options['target_charset'] 
            : 'ASCII';
        $this->regexClear = '/[^a-zA-Z0-9\/_|+\. -]/';
    }
    
    /**
     * {@inheritDoc}
     * @see \Laminas\Filter\FilterInterface::filter()
     */
    public function filter($value)
    {
        $oldLocale = setlocale(LC_CTYPE, null);
        // Muda o locale do sistema.
        // Isto é necessário pois as chamadas a função iconv() retornam o caracter ? para caracteres acentuados se o
        // locale definido para LC_CTYPE for "C" ou "POSIX"
        setlocale(LC_CTYPE, sprintf(
            '%s.%s',
            $this->locale,
            str_replace('-', '', $this->originalCharset))
        );
        $clean = $value;
        // Troca os delimitadores por espaços em branco antes de aplicar iconv() na string
        if (!empty($this->delimiters)) {
            $clean = str_replace($this->delimiters, ' ', $clean);
        }
        // Transliteração de caracteres
        if ($this->originalCharset === 'UTF-8' && $this->targetCharset === 'ASCII') {
            $clean = (new UnicodeString($clean))->ascii();
        } else {
            $clean = iconv(
                $this->originalCharset,
                $this->targetCharset . '//TRANSLIT',
                $clean
            );
        }
        // Remove caracteres especiais que não tenham sido transliterados
        $clean = preg_replace($this->regexClear, '', $clean);
        $clean = trim($clean, $this->separator);
        $clean = preg_replace("/[\/_|+ -]+/", $this->separator, $clean);
        // Volta o locale à configuração original
        setlocale(LC_CTYPE, $oldLocale);
        return $clean;
    }
}