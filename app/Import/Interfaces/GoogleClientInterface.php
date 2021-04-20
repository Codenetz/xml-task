<?php

namespace App\Import\Interfaces;

interface GoogleClientInterface
{
  public function getConnection(): \Google_Client;
}