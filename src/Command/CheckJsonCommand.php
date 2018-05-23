<?php

namespace App\Command;

use App\Service\FileHelper;
use App\Service\SchemaJsonHelper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckJsonCommand extends Command
{
    protected $fileHelper;

    protected $schemaJsonHelper;

    public function __construct()
    {
        parent::__construct();

        $this->fileHelper = new FileHelper('jobs.json');
        $this->schemaJsonHelper = new SchemaJsonHelper();
    }

    protected function configure()
    {
        $this->setName('check-json')
            ->setDescription('Check if json file is valid with schema.json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Json Schema Validator',
            '=====================',
            '',
        ]);

        $schemaJson = $this->fileHelper->open('schema', $_SERVER['OLDPWD'] . '/../public');
        $schemaJson = json_decode($schemaJson);
        $jsonFile = $this->fileHelper->open('jobs', $_SERVER['OLDPWD'] . '/../public');
        $jsonFile = json_decode( $jsonFile, true );

        $checked = $this->schemaJsonHelper->checkValidation($jsonFile, $schemaJson);

        foreach ($checked as $item) {
            $output->writeln($item['title'] . " record passed and valid with schema.json" );
        }

    }
}