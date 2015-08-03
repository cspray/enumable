<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable;

interface EnumBuilder {

    public function build(EnumDefinition $definition) : BuiltEnum;

}