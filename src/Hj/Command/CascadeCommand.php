<?php

namespace Hj\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CascadeCommand extends Command
{
    protected function configure()
    {
        $this->setName('cascade:command');
        $this->addUsage('php console.php cascade:command assignee:update --p=jqls/assignee.yaml --n=jqlPath --p=admin --n=assignee --p=3 --n=ids --i=3  comment:add --p=jqls/comment.yaml --n=jqlPath --p=comment.php --n=commentFilePath --p=3  --n=ids --i=3 issue:update-status --p=jqls/status.yaml --n=jqlPath --p=Escalated --n=status --p=3 --n=ids --i=3');

        $this
            ->addArgument(
                'commands',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                ''
            );
        $this->addOption(
            'p',
            null,
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            "Valeur du paramètre de la commande"
        );
        $this->addOption(
            'n',
            null,
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            "Nom du paramètre de la commande"
        );
        $this->addOption(
            'i',
            null,
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
            "Nombre d'arguments par commande"
        );

    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $i = 0;
        $offset = [];
        $offset[0] = 0;
        $commands = $input->getArgument('commands');
        $valeurParametres = $input->getOption('p');
        $nomParametres = $input->getOption('n');
        $nombreParametres = $input->getOption('i');

        foreach ($commands as $key => $command) {
            $commandeCourante = $this->getApplication()->find($command);

            $nbreParametreCommandCourante = (int) $nombreParametres[$key] ?? 0;


            $parametresCommandeCourante = [];

            while ($i < $nbreParametreCommandCourante + $offset[$key]) {
                $parametresCommandeCourante[$nomParametres[$i]] = $valeurParametres[$i];
                $i++;
            }
            $offset[$key + 1] = $i;

            $this->runCommand($commandeCourante, $parametresCommandeCourante, $output);
        }
    }

    private function runCommand(Command $command, $arguments, $output)
    {
        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }
}