<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable\EnumBuilder;

use CodeAnvil\Info\ClassInfo;
use CodeAnvil\Info\InterfaceInfo;
use CodeAnvil\Info\MethodInfo;
use CodeAnvil\Info\ParameterInfo;
use CodeAnvil\Info\PropertyInfo;
use Collections\LinkedList;
use Cspray\Enumable\EnumBuilder;
use Cspray\Enumable\EnumDefinition;
use Cspray\Enumable\BuiltEnum;
use function Stringy\create as s;

class StandardBuilder implements EnumBuilder {

    public function build(EnumDefinition $definition) : BuiltEnum {
        list($ns, $class) = $this->getNamespaceAndClass($definition->getFullyQualifiedType());
        $interface = new InterfaceInfo();
        $interface->setNamespace($ns)
                  ->setName($class);

        $interfaceMethods = $this->getInterfaceMethodDefinitions($definition->getPossibleAttributes());
        foreach ($interfaceMethods as $method) {
            $interface->addMethod($method);
        }

        $classInfo = new ClassInfo();
        $classInfo->setNamespace($ns)
                  ->setName($class . 'Enum')
                  ->makeAbstract();

        $classMethods = $this->getClassMethods($definition->getFullyQualifiedType(), $class, $definition->getPossibleAttributes());
        foreach ($classMethods as $method) {
            $classInfo->addMethod($method);
        }

        $enumProperty = new PropertyInfo();
        $enumProperty->setName('enums')
                     ->makeStatic()
                     ->setVisibility('private');

        $classInfo->addProperty($enumProperty);

        return new BuiltEnum($interface, $classInfo);
    }

    private function getNamespaceAndClass(string $fqt) : array {
        $fragments = explode('\\', $fqt);
        $className = array_pop($fragments);
        $ns = implode('\\', $fragments);
        return [$ns, $className];
    }

    private function getInterfaceMethodDefinitions(array $attrs) : array {
        $getVal = new MethodInfo();
        $getVal->setName('getValue')
               ->setReturnType('string');

        $methods = [$getVal];

        foreach ($attrs as $attr) {
            $tmp = (string) s('is_' . $attr)->camelize();
            $tmpMethod = new MethodInfo();
            $tmpMethod->setName($tmp)
                      ->setReturnType('bool');

            $methods[] = $tmpMethod;
        }

        return $methods;
    }

    private function getClassMethods(string $interfaceType, string $className, array $attrs) : array {
        $methods = [];
        foreach ($attrs as $attr) {
            $tmp = (string) s($attr)->underscored()->toUpperCase();
            $tmpMethod = new MethodInfo();
            $tmpMethod->setName($tmp)
                      ->setReturnType($interfaceType)
                      ->makeStatic();

            $lines = new LinkedList();

            $lines[] = 'if (!isset(self::$enums[\'' . $attr . '\'])) {';
            $lines[] = "\t\t\tself::\$enums['{$attr}'] = self::buildEnum('{$attr}');";
            $lines[] = "\t\t}";
            $lines[] = "\t\treturn self::\$enums['{$attr}'];";

            $tmpMethod->setBody(implode("\n", $lines->toArray()));
            $methods[] = $tmpMethod;
        }

        $buildEnum = new MethodInfo();
        $buildEnum->setName('buildEnum')
                  ->setVisibility('private')
                  ->makeStatic()
                  ->setReturnType($interfaceType);

        $parameter = new ParameterInfo();
        $parameter->setName('val')
                  ->setTypeDeclaration('string');

        $buildEnum->addParameter($parameter);

        $lines = new LinkedList();
        $lines[] = 'return new class($val) implements ' . $className . ' {';
        $lines[] = '';
        $lines[] = "\t\t\tprivate \$val;";
        $lines[] = '';
        $lines[] = "\t\t\tpublic function __construct(string \$val) {";
        $lines[] = "\t\t\t\t\$this->val = \$val;";
        $lines[] = "\t\t\t}";
        $lines[] = '';
        $lines[] = "\t\t\tpublic function getValue() : string {";
        $lines[] = "\t\t\t\treturn \$this->val;";
        $lines[] = "\t\t\t}";
        $lines[] = '';

        foreach ($attrs as $attr) {
            $tmp = s('is_' . $attr)->camelize();
            $lines[] = "\t\t\tpublic function {$tmp}() : bool {";
            $lines[] = "\t\t\t\treturn \$this->val === '{$attr}';";
            $lines[] = "\t\t\t}";
            $lines[] = '';
        }

        $lines[] = "\t\t\tpublic function __toString() : string {";
        $lines[] = "\t\t\t\treturn \$this->val;";
        $lines[] = "\t\t\t}";
        $lines[] = '';

        $lines[] = "\t\t};";



        $buildEnum->setBody(implode("\n", $lines->toArray()));

        $methods[] = $buildEnum;

        return $methods;
    }



}