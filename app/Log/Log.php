<?php
namespace App\Log;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\ErrorHandler;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;

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
   * @param bool $stdOut
   */
  public function __construct(bool $stdOut = true)
  {
    ini_set('display_errors', 0);

    $log = new Logger('task');
    if($stdOut) $log->pushHandler(new StreamHandler('php://stdout', Logger::DEBUG));
    ErrorHandler::register($log);
    $this->log = $log;
  }

  /**
   * @param $output
   */
  public function pushConsoleHandler($output)
  {
    $this->log->pushHandler(new ConsoleHandler($output));
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