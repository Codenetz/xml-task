<?php

namespace App\Import\Lib;

use App\Import\Interfaces\GoogleClientInterface;

/**
 * Class GoogleClient
 * @package App\Import\Lib
 */
class GoogleClient implements GoogleClientInterface
{
  protected ?\Google_Client $connection = null;

  /**
   * @return \Google_Client
   * @throws \Google\Exception
   */
  public function getConnection(): \Google_Client
  {
    if ($this->connection instanceof \Google_Client) return $this->connection;

    $client = new \Google_Client();
    $client->setApplicationName('GoogleClient');
    $client->setScopes(\Google_Service_Sheets::SPREADSHEETS);
    $client->addScope(\Google_Service_Drive::DRIVE);
    $client->setAuthConfig(APP_DIR . '/var/googleServiceAccount.json');
    $this->connection = $client;
    return $this->connection;
  }
}