<?php
namespace App\Import\Command;

use App\Import\Exceptions\XMLFileNotFoundException;
use App\Import\Exceptions\XMLFileNotValidException;
use App\Import\Lib\GoogleClient;
use App\Import\Lib\GoogleDrive;
use App\Import\Lib\GoogleSpreadsheet;
use App\Import\Lib\XMLFile;
use App\Import\Lib\XMLFileNormalization;
use App\Log\Log;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 * @package App\Import\Command
 */
class ImportCommand extends Command
{

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
      ->addArgument('fileLocation', InputArgument::REQUIRED, 'XML file location')
      ->addOption('deleteSpreadsheet', null, InputOption::VALUE_OPTIONAL, 'Deletes spreadsheet from Google Drive after import', false);
  }

  /**
   * @param InputInterface $input
   * @param OutputInterface $output
   * @return int
   * @throws XMLFileNotFoundException
   */
  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $this->log->pushConsoleHandler($output);

    try {
      $fileLocation = $input->getArgument('fileLocation');
      $deleteSpreadsheet = $input->getOption('deleteSpreadsheet');
      if (!is_bool($deleteSpreadsheet)) $deleteSpreadsheet = ($deleteSpreadsheet === 'true');

      $this->log->info("Begin importing");
      $xmlFile = new XMLFile($fileLocation);

      if (!$xmlFile->exists()) throw new XMLFileNotFoundException();
      if (!$xmlFile->isValid()) throw new XMLFileNotValidException();

      $xmlFileNormalization = new XMLFileNormalization($xmlFile);

      $googleClient = new GoogleClient();
      $googleDrive = new GoogleDrive($googleClient);
      $googleSpreadSheet = new GoogleSpreadsheet($xmlFileNormalization, $googleClient);
      $spreedSheet = $googleSpreadSheet->import();
      $googleDrive->givePublicPermission($spreedSheet->getSpreadsheetId());

      $this->log->info("Public spreadsheet url:");
      $this->log->info($spreedSheet->getSpreadsheetUrl());

      if ($deleteSpreadsheet) $googleDrive->deleteFile($spreedSheet->getSpreadsheetId());
    } catch (\Exception $e) {
      $this->log->error($e);
    }

    return Command::SUCCESS;
  }
}