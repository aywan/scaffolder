<?php
/**
 * Spiral Framework. Scaffolder
 *
 * @license MIT
 * @author  Anton Titov (Wolfy-J)
 * @author  Valentin V (vvval)
 */
declare(strict_types=1);

namespace Spiral\Scaffolder\Command;

use Spiral\Scaffolder\Declaration\ControllerDeclaration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ControllerCommand extends AbstractCommand
{
    protected const ELEMENT = 'controller';

    protected const NAME        = 'create:controller';
    protected const DESCRIPTION = 'Create controller declaration';
    protected const ARGUMENTS   = [
        ['name', InputArgument::REQUIRED, 'Controller name']
    ];
    protected const OPTIONS     = [
        [
            'action',
            'a',
            InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
            'Pre-create controller action'
        ],
        [
            'comment',
            'c',
            InputOption::VALUE_OPTIONAL,
            'Optional comment to add as class header'
        ]
    ];

    /**
     * Create controller declaration.
     */
    public function perform(): void
    {
        /** @var ControllerDeclaration $declaration */
        $declaration = $this->createDeclaration();

        foreach ($this->option('action') as $action) {
            $declaration->addAction($action);
        }

        $this->writeDeclaration($declaration);
    }
}
