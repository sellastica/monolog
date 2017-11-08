<?php
namespace Sellastica\Monolog;

use Kdyby\Monolog\CustomChannel;
use Monolog\Handler\HandlerInterface;

class ChannelLogger implements ILogger
{
	/** @var CustomChannel */
	private $channel;


	/**
	 * @param CustomChannel $channel
	 */
	public function __construct(CustomChannel $channel)
	{
		$this->channel = $channel;
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
		return $this->channel->addRecord(\Monolog\Logger::INFO, $message, $context);
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
		return $this->channel->addRecord(\Monolog\Logger::NOTICE, $message, $context);
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
		return $this->channel->addRecord(\Monolog\Logger::WARNING, $message, $context);
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
		return $this->channel->addRecord(\Monolog\Logger::ERROR, $message, $context);
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
		return $this->channel->addRecord(\Monolog\Logger::CRITICAL, $message, $context);
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
		return $this->channel->addRecord(\Monolog\Logger::ALERT, $message, $context);
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
		return $this->channel->addRecord(\Monolog\Logger::EMERGENCY, $message, $context);
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
		return $this->channel->addRecord(
			$level,
			$message ?? $e->getMessage(),
			['channel' => $this->channel->getName(), 'exception' => $e] + $context
		);
	}

	/**
	 * Pushes a handler on to the stack.
	 *
	 * @param  HandlerInterface $handler
	 * @return $this
	 */
	public function pushHandler(HandlerInterface $handler)
	{
		$this->channel->pushHandler($handler);
		return $this;
	}
}
