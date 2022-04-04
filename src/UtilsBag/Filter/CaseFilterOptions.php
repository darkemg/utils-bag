<?php

/**
 * Conjunto de opções para o modo de operação do filtro de conversão de capitalização de string
 * 
 * @package \UtilsBag\Filter
 * @see \UtilsBag\Filter\MultibyteCaseConvert
 */

namespace UtilsBag\Filter;

enum CaseFilterOptions: int {

    case UPPER = 1;
    case LOWER = 2;
    case TITLE = 3;
    case FIRST_UPPER = 4;
    case FIRST_LOWER = 5;
}
