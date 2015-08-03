<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable;

use CodeAnvil\Info\ClassInfo;
use CodeAnvil\Info\InterfaceInfo;

class BuiltEnum {

    private $interface;
    private $class;

    public function __construct(InterfaceInfo $interface, ClassInfo $class) {
        $this->interface = $interface;
        $this->class = $class;
    }

    public function getInterface() : InterfaceInfo {
        return $this->interface;
    }

    public function getClass() : ClassInfo {
        return $this->class;
    }

}