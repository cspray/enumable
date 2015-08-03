<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable;

class EnumDefinition {

    private $type;
    private $attrs;

    public function __construct(string $type, array $attrs) {
        $this->type = $type;
        $this->attrs = $attrs;
    }

    public function getFullyQualifiedType() : string {
        return $this->type;
    }

    public function getPossibleAttributes() : array {
        return $this->attrs;
    }

}