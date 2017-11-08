<?php
namespace Sellastica\Monolog;

interface ILogger
{
	/**
	 * Adds a log record at the INFO level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function info($message, array $context = array());

	/**
	 * Adds a log record at the NOTICE level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function notice($message, array $context = array());

	/**
	 * Adds a log record at the WARNING level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function warning($message, array $context = array());

	/**
	 * Adds a log record at the ERROR level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function error($message, array $context = array());

	/**
	 * Adds a log record at the CRITICAL level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function critical($message, array $context = array());

	/**
	 * Adds a log record at the ALERT level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function alert($message, array $context = array());

	/**
	 * Adds a log record at the EMERGENCY level.
	 *
	 * This method allows for compatibility with common interfaces.
	 *
	 * @param  string  $message The log message
	 * @param  array   $context The log context
	 * @return Boolean Whether the record has been processed
	 */
	function emergency($message, array $context = array());

	/**
	 * @param \Throwable $e
	 * @param string|null $message
	 * @param int $level
	 * @param array $context
	 * @return bool
	 */
	function exception(
		\Throwable $e,
		string $message = null,
		int $level = \Monolog\Logger::ERROR,
		array $context = []
	);
}
