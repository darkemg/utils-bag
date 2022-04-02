<?php

/**
 * Conjunto de opções para a máscara de ocultação do cartão de crédito
 * 
 * @package \UtilsBag\Filter
 * @see \UtilsBag\Filter\CardNumberHide
 */

namespace UtilsBag\Filter;

enum CardFilterOptions : int {

    case LAST4 = 4;
    case FIRST6 = 6;
    case NONE = 0;
}
