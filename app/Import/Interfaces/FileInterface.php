<?php
namespace App\Import\Interfaces;

interface FileInterface {
  public function getContents(): string;
  public function toArray(): array;
}