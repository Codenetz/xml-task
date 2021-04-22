<?php

namespace App\Import\Lib;

use App\Import\Interfaces\GoogleClientInterface;

/**
 * Class GoogleDrive
 * @package App\Import\Lib
 */
class GoogleDrive
{
  /**
   * @var GoogleClientInterface
   */
  protected GoogleClientInterface $googleClient;

  /**
   * GoogleDrive constructor.
   * @param GoogleClientInterface $googleClient
   */
  public function __construct(GoogleClientInterface $googleClient)
  {
    $this->googleClient = $googleClient;
  }

  /**
   * @return \Google_Service_Drive
   */
  protected function getDrive()
  {
    return new \Google_Service_Drive($this->googleClient->getConnection());
  }

  /**
   * @param $fileId
   */
  public function givePublicPermission($fileId)
  {
    $googleDrive = $this->getDrive();
    $permissionService = new \Google_Service_Drive_Permission();
    $permissionService->role = "reader";
    $permissionService->type = "anyone";
    $googleDrive->permissions->create($fileId, $permissionService);
  }

  /**
   * @param $fileId
   */
  public function deleteFile($fileId)
  {
    $f = new \Google_Service_Drive($this->googleClient->getConnection());
    $f->files->delete($fileId);
  }
}