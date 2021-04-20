<?php
namespace App\Import\Exceptions;

class XMLFileNotValidException extends \Exception
{
  public function __construct()
  {
    parent::__construct("XML file is not valid", 0, null);
  }
}