<?php
namespace App\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\ErrorHandler;

/**
 * Class Log
 * @package App\Log\Lib
 */
class Log
{
  /**
   * @var Logger
   */
  protected Logger $log;

  /**
   * Log constructor.
   */
  public function __construct()
  {
    ini_set('display_errors', 0);

    $log = new Logger('task');
    $log->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
    ErrorHandler::register($log);
    $this->log = $log;
  }

  /**
   * @param $message
   */
  public function info($message)
  {
    $this->log->info($message);
  }

  /**
   * @param $message
   */
  public function error($message)
  {
    $this->log->error($message);
  }

  /**
   * @param $message
   */
  public function warning($message)
  {
    $this->log->warning($message);
  }
}