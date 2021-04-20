<?php

namespace App\Import\Lib;

use App\Import\Interfaces\FileInterface;
use App\Import\Interfaces\GoogleClientInterface;

/**
 * Class GoogleSpreadsheet
 * @package App\Import\Lib
 */
class GoogleSpreadsheet
{
  /**
   * @var FileInterface
   */
  protected FileInterface $file;
  /**
   * @var GoogleDrive
   */
  protected GoogleDrive $googleDrive;
  /**
   * @var GoogleClientInterface
   */
  protected GoogleClientInterface $googleClient;

  /**
   * GoogleSpreadsheet constructor.
   * @param FileInterface $file
   * @param GoogleDrive $googleDrive
   * @param GoogleClientInterface $googleClient
   */
  public function __construct(FileInterface $file, GoogleDrive $googleDrive, GoogleClientInterface $googleClient)
  {
    $this->file = $file;
    $this->googleDrive = $googleDrive;
    $this->googleClient = $googleClient;
  }

  /**
   * @return \Google_Service_Sheets
   */
  protected function getSpreadsheet()
  {
    return new \Google_Service_Sheets($this->googleClient->getConnection());
  }

  /**
   * @param \Google_Service_Sheets $spreedSheetService
   * @return \Google_Service_Sheets_Spreadsheet
   */
  protected function createSpreedSheet(\Google_Service_Sheets $spreedSheetService): \Google_Service_Sheets_Spreadsheet
  {
    return $spreedSheetService->spreadsheets->create(
      new \Google_Service_Sheets_Spreadsheet([
        'properties' => [
          'title' => date("d.m.y h:i:s", time())
        ]
      ]), [
      'fields' => 'spreadsheetId'
    ]);
  }

  /**
   * @param int $position
   * @return mixed
   */
  protected function getLetterFromPosition(int $position)
  {
    return range('A', 'Z')[$position];
  }

  /**
   * @param \Google_Service_Sheets $spreedSheetService
   * @param \Google_Service_Sheets_Spreadsheet $spreedSheet
   * @param array $data
   */
  protected function write(
    \Google_Service_Sheets $spreedSheetService,
    \Google_Service_Sheets_Spreadsheet $spreedSheet,
    array $data): void
  {
    $values = $data;
    $body = new \Google_Service_Sheets_ValueRange([
      'values' => $values
    ]);

    $params = [
      'valueInputOption' => 'RAW'
    ];

    $spreedSheetService->spreadsheets_values->update(
      $spreedSheet->spreadsheetId,
      'Sheet1!A1:' . $this->getLetterFromPosition(count($data[0])) . count($data),
      $body,
      $params
    );
  }

  /**
   * @return \Google_Service_Sheets_Spreadsheet
   */
  public function import(): \Google_Service_Sheets_Spreadsheet
  {
    $contents = $this->file->toArray();
    $spreedSheetService = $this->getSpreadsheet();
    $spreedSheet = $this->createSpreedSheet($spreedSheetService);
    $this->write($spreedSheetService, $spreedSheet, $contents);
    $this->googleDrive->givePublicPermission($spreedSheet->getSpreadsheetId());
    return $spreedSheetService->spreadsheets->get($spreedSheet->getSpreadsheetId());
  }

  /**
   * @return FileInterface
   */
  public function getFile(): FileInterface
  {
    return $this->file;
  }

  /**
   * @param FileInterface $file
   * @return GoogleSpreadsheet
   */
  public function setFile(FileInterface $file): GoogleSpreadsheet
  {
    $this->file = $file;
  }
}