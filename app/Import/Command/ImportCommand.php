<?php
namespace App\Import\Command;

use App\Import\Exceptions\XMLFileNotFoundException;
use App\Import\Exceptions\XMLFileNotValidException;
use App\Import\Lib\GoogleClient;
use App\Import\Lib\GoogleDrive;
use App\Import\Lib\GoogleSpreadsheet;
use App\Import\Lib\XMLFile;
use App\Import\Lib\XMLFileNormalization;
use App\Log\Lib\Error;
use App\Log\Log;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 * @package App\Import\Command
 */
class ImportCommand extends Command {

  /**
   * @var string
   */
  protected static $defaultName = 'import:xml';

  protected Log $log;

  public function __construct(Log $log, string $name = null)
  {
    parent::__construct($name);
    $this->log = $log;
  }

  /**
   *
   */
  protected function configure()
  {
    $this
      ->setDescription('Importing XML to Google spreadsheet')
      ->addArgument('fileLocation', InputArgument::REQUIRED, 'XML file location');
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   * @throws XMLFileNotFoundException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    try {
      $fileLocation = $input->getArgument('fileLocation');

      $this->log->info("Begin importing");
      $xmlFile = new XMLFile($fileLocation);

      if (!$xmlFile->exists()) throw new XMLFileNotFoundException();
      if (!$xmlFile->isValid()) throw new XMLFileNotValidException();

      $xmlFileNormalization = new XMLFileNormalization($xmlFile);

      $googleClient = new GoogleClient();
      $googleDrive = new GoogleDrive($googleClient);
      $googleSpreadSheet = new GoogleSpreadsheet($xmlFileNormalization, $googleDrive, $googleClient);
      $spreedSheet = $googleSpreadSheet->import();

      $this->log->info("Public spreadsheet url:");
      $this->log->info($spreedSheet->getSpreadsheetUrl());
    }
    catch (\Exception $e) {
      $this->log->error($e);
    }

    return Command::SUCCESS;
  }
}