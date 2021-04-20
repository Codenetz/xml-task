<?php

namespace App\Import\Lib;

use App\Import\Interfaces\FileInterface;

/**
 * Class File
 * @package App\Import\Lib
 */
class XMLFile implements FileInterface
{
  /**
   * @var string
   */
  protected string $fileLocation;

  /**
   * File constructor.
   * @param string $fileLocation
   */
  public function __construct(string $fileLocation)
  {
    $this->fileLocation = $fileLocation;
  }

  /**
   * @return array
   */
  public function toArray(): array
  {
    $xml = simplexml_load_string($this->getContents(), "SimpleXMLElement", LIBXML_NOCDATA);
    return array_values(json_decode(json_encode($xml), true))[0];
  }

  /**
   * @return string
   */
  public function getContents(): string
  {
    $contents = file_get_contents($this->fileLocation);
    return $contents ?? "";
  }

  /**
   * @return bool
   */
  public function isValid()
  {
    return !!(@simplexml_load_string($this->getContents()));
  }

  /**
   * @return bool
   */
  public function exists()
  {
    if(str_starts_with($this->fileLocation, 'http')) {
      $ch = curl_init($this->fileLocation);

      curl_setopt($ch, CURLOPT_NOBODY, true);
      curl_exec($ch);
      $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      curl_close($ch);

      return $retcode === 200;
    }

    return file_exists($this->fileLocation);
  }

  /**
   * @return string
   */
  public function getFileLocation(): string
  {
    return $this->fileLocation;
  }

  /**
   * @param string $fileLocation
   * @return File
   */
  public function setFileLocation(string $fileLocation): File
  {
    $this->fileLocation = $fileLocation;
    return $this;
  }
}