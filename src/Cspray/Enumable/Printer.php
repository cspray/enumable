<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable;

interface Printer {

    public function printEnum(BuiltEnum $enum);

}