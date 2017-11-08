<?php
namespace Sellastica\Monolog;

use Monolog\Handler\HandlerInterface;

class Logger implements ILogger
{
	/** @var \Kdyby\Monolog\Logger */
	private $monolog;


	/**
	 * @param \Kdyby\Monolog\Logger $monolog
	 */
	public function __construct(\Kdyby\Monolog\Logger $monolog)
	{
		$this->monolog = $monolog;
	}

	/**
	 * @return \Kdyby\Monolog\Logger
	 */
	public function getMonolog(): \Kdyby\Monolog\Logger
	{
		return $this->monolog;
	}

	/**
	 * Adds a log record at the INFO level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function info($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::INFO, $message, $context);
	}

	/**
	 * Adds a log record at the NOTICE level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function notice($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::NOTICE, $message, $context);
	}

	/**
	 * Adds a log record at the WARNING level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function warning($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::WARNING, $message, $context);
	}

	/**
	 * Adds a log record at the ERROR level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function error($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::ERROR, $message, $context);
	}

	/**
	 * Adds a log record at the CRITICAL level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function critical($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::CRITICAL, $message, $context);
	}

	/**
	 * Adds a log record at the ALERT level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function alert($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::ALERT, $message, $context);
	}

	/**
	 * Adds a log record at the EMERGENCY level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	public function emergency($message, array $context = array())
	{
		return $this->monolog->addRecord(\Monolog\Logger::EMERGENCY, $message, $context);
	}

	/**
	 * @param \Throwable $e
	 * @param string|null $message
	 * @param int $level
	 * @param array $context
	 * @return bool
	 */
	public function exception(
		\Throwable $e,
		string $message = null,
		int $level = \Monolog\Logger::ERROR,
		array $context = []
	)
	{
		return $this->monolog->addRecord($level, $message ?? $e->getMessage(), ['exception' => $e] + $context);
	}

	/**
	 * @param string $channel
	 * @return ChannelLogger
	 */
	public function channel($channel): ChannelLogger
	{
		return new ChannelLogger($this->monolog->channel($channel));
	}

	/**
	 * Pushes a handler on to the stack.
	 *
	 * @param  HandlerInterface $handler
	 * @return $this
	 */
	public function pushHandler(HandlerInterface $handler)
	{
		$this->monolog->pushHandler($handler);
		return $this;
	}
}
