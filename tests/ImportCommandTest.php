<?php
define('APP_DIR', __DIR__ . '/..');

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommandTest extends PHPUnit\Framework\TestCase
{
  const XML_FILE = "data/coffee_feed.xml";
  const XML_NOT_VALID_FILE = "data/coffee_feed_not_valid.xml";
  const XML_EMPTY_FILE = "data/empty.xml";
  const XML_REMOTE_FILE = "https://raw.githubusercontent.com/Codenetz/xml-task/master/data/coffee_feed.xml";

  public function testLocalXML()
  {
    $output = $this->execute(self::XML_FILE);
    $this->assertStringContainsString('Public spreadsheet url', $output);
    $this->assertStringContainsString('https://docs.google.com/spreadsheets/d/', $output);
  }

  public function testRemoteXML()
  {
    $output = $this->execute(self::XML_REMOTE_FILE);
    $this->assertStringContainsString('Public spreadsheet url', $output);
    $this->assertStringContainsString('https://docs.google.com/spreadsheets/d/', $output);
  }

  public function testNotFoundXML()
  {
    $output = $this->execute('notfound.xml');
    $this->assertStringContainsString('XMLFileNotFoundException', $output);
  }

  public function testNotValidXML()
  {
    $output = $this->execute(self::XML_NOT_VALID_FILE);
    $this->assertStringContainsString('XMLFileNotValidException', $output);
  }

  public function testEmptyXML()
  {
    $output = $this->execute(self::XML_EMPTY_FILE);
    $this->assertStringContainsString('XMLFileNotValidException', $output);
  }

  /**
   * @param $input
   * @return string
   */
  protected function execute($input): string
  {
    $command = new \App\Import\Command\ImportCommand(
      new App\Log\Log(false)
    );

    $application = new Application();
    $application->add($command);
    $application->setAutoExit(false);
    $command = $application->find('import:xml');
    $commandTester = new CommandTester($command);
    $commandTester->execute([
      'fileLocation' => $input,
      '--deleteSpreadsheet' => true
    ], [
      'verbosity' => OutputInterface::VERBOSITY_DEBUG
    ]);

    return $commandTester->getDisplay();
  }
}

