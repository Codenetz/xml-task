<?php
namespace App\Import\Exceptions;

class XMLFileNotFoundException extends \Exception
{
  public function __construct()
  {
    parent::__construct("XML file is not found", 0, null);
  }
}