<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable\Printer;

use Cspray\Enumable\BuiltEnum;
use Cspray\Enumable\Printer;
use CodeAnvil\CodeGenerator;
use Symfony\Component\Console\Output\OutputInterface;

class TerminalPrinter implements Printer {

    private $out;
    private $appVersion;

    public function __construct(OutputInterface $out, string $appVer) {
        $this->out = $out;
        $this->appVersion = $appVer;
    }

    public function printEnum(BuiltEnum $enum) {
        $generator = new CodeGenerator();
        $interface = $generator->generate($enum->getInterface());
        $interface = preg_replace("#\t#", '    ', $interface);

        $this->out->writeln($this->appVersion);
        $this->out->writeln('');
        $this->out->writeln('The enum type you\'ll declare for.');
        $this->out->writeln(str_repeat('=', 80));
        $this->out->writeln($interface);
        $this->out->writeln('');

        $class = $generator->generate($enum->getClass());
        $class = preg_replace("#\t#", '    ', $class);

        $this->out->writeln('The class you\'ll use to build enums.');
        $this->out->writeln(str_repeat('=', 80));
        $this->out->writeln($class);
    }

}