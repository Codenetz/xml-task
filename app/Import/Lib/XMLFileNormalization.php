<?php

namespace App\Import\Lib;

use App\Import\Exceptions\XMLFileNotValidException;
use App\Import\Interfaces\FileInterface;

/**
 * Class File
 * @package App\Import\Lib
 */
class XMLFileNormalization implements FileInterface
{
  /**
   * @var FileInterface
   */
  protected FileInterface $file;

  /**
   * File constructor.
   * @param FileInterface $file
   */
  public function __construct(FileInterface $file)
  {
    $this->file = $file;
  }

  /**
   * @return array
   */
  public function toArray(): array
  {
    $itemsNormalized = [];

    $items = $this->file->toArray();

    if(count($items) <= 0) throw new XMLFileNotValidException();
    if(!isset($items[0])) throw new XMLFileNotValidException();

    $names = array_keys($items[0]);
    $itemsNormalized[] = $names;
    foreach ($items as $content) {
      $item = [];
      foreach ($names as $name) {
        $value = $content[$name];
        $value = @(string)(is_array($value) ? implode(", ", $value) : $value);
        $value = (empty($value)) ? '-' : $value;
        $item[] = $value;
      }

      $itemsNormalized[] = $item;
    }

    return $itemsNormalized;
  }

  /**
   * @return string
   */
  public function getContents(): string
  {
    return $this->file->getContents();
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
   * @return XMLFileNormalization
   */
  public function setFile(FileInterface $file): XMLFileNormalization
  {
    $this->file = $file;
    return $this;
  }
}