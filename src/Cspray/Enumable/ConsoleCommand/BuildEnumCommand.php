<?php

declare(strict_types = 1);

/**
 * @license See LICENSE file in project root
 */

namespace Cspray\Enumable\ConsoleCommand;

use Cspray\Enumable\EnumBuilder\StandardBuilder;
use Cspray\Enumable\EnumDefinition;
use Cspray\Enumable\Printer\TerminalPrinter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildEnumCommand extends Command {

    protected function configure() {
        parent::configure();
        $this->setName('enumable:build')
             ->setDescription('Builds an enum from a given type name and attributes')
             ->addArgument(
                'type',
                 InputArgument::REQUIRED,
                 'The fully qualified class name for the enum you want to declare types for.'
             )
             ->addArgument(
                 'attributes',
                 InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                 'List 1 or more attributes for your enum.'
             )
             ->addOption(
                 'dry-run',
                 null,
                 InputOption::VALUE_NONE,
                 'Instead of writing files to disk we will print output to the console'
             )
             ->addOption(
                 'output-dir',
                 'o',
                 InputOption::VALUE_REQUIRED,
                 'If set any files generated will be created here',
                 'src'
             )
             ->addOption(
                 'parent-class',
                 'pc',
                 InputOption::VALUE_REQUIRED,
                 'If set the generated anonymous class will extend this type'
             )
             ->addOption(
                 'enum-builder',
                 'eb',
                 InputOption::VALUE_REQUIRED,
                 'The builder to generate the enum; assumes no constructor parameters are required',
                 StandardBuilder::class
             );
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $type = $input->getArgument('type');
        $attrs = $input->getArgument('attributes');

        $enumDef = new EnumDefinition($type, $attrs);
        $builder = new StandardBuilder();
        $builtEnum = $builder->build($enumDef);

        $printer = new TerminalPrinter($output, $this->getApplication()->getLongVersion());
        $printer->printEnum($builtEnum);
    }

}