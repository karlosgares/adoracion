<?php
namespace App\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
 use Symfony\Component\DependencyInjection\ContainerInterface;

class SacerdoteDiaCommand extends Command
{
    private $container;

    public function __construct( ContainerInterface $container)
    {
        $this->container = $container;

        // you *must* call the parent constructor
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('adoracion:sacerdote:update')
            ->setDescription('Actualiza el calendario de la semana');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $em = $this->container->get('doctrine')->getEntityManager();
       $em->getRepository("App\\Entity\\DiasemanaHora")->activarSemana();
        // ...
        $output->writeln([
            'ok',// A line
            '============',// Another line
            '',// Empty line
        ]);
    }
}