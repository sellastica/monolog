<?php
namespace Sellastica\Monolog\Handler;

use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\NormalizerFormatter;
use Nette;

class SlackRecord extends \Monolog\Handler\Slack\SlackRecord
{
	const COLOR_DANGER = 'danger',
		COLOR_WARNING = 'warning',
		COLOR_GOOD = 'good',
		COLOR_DEFAULT = '#e3e4e6';

	/**
	 * Slack channel (encoded ID or name)
	 * @var string|null
	 */
	private $channel;

	/**
	 * Name of a bot
	 * @var string|null
	 */
	private $username;

	/**
	 * User icon e.g. 'ghost', 'http://example.com/user.png'
	 * @var string
	 */
	private $userIcon;

	/**
	 * Whether the message should be added to Slack as attachment (plain text otherwise)
	 * @var bool
	 */
	private $useAttachment;

	/**
	 * Whether the the context/extra messages added to Slack as attachments are in a short style
	 * @var bool
	 */
	private $useShortAttachment;

	/**
	 * Whether the attachment should include context and extra data
	 * @var bool
	 */
	private $includeContextAndExtra;

	/**
	 * Dot separated list of fields to exclude from slack message. E.g. ['context.field1', 'extra.field2']
	 * @var array
	 */
	private $excludeFields;

	/**
	 * @var FormatterInterface
	 */
	private $formatter;

	/**
	 * @var NormalizerFormatter
	 */
	private $normalizerFormatter;

	/**
	 * @var Nette\Http\IRequest
	 */
	private $httpRequest;


	/**
	 * @param $channel
	 * @param $username
	 * @param bool $useAttachment
	 * @param $userIcon
	 * @param bool $useShortAttachment
	 * @param bool $includeContextAndExtra
	 * @param array $excludeFields
	 * @param FormatterInterface|null $formatter
	 * @param Nette\Http\IRequest $httpRequest
	 */
	public function __construct(
		$channel = null,
		$username = null,
		$useAttachment = true,
		$userIcon = null,
		$useShortAttachment = false,
		$includeContextAndExtra = false,
		array $excludeFields = [],
		FormatterInterface $formatter = null,
		Nette\Http\IRequest $httpRequest
	)
	{
		$this->channel = $channel;
		$this->username = $username;
		$this->userIcon = trim($userIcon, ':');
		$this->useAttachment = $useAttachment;
		$this->useShortAttachment = $useShortAttachment;
		$this->includeContextAndExtra = $includeContextAndExtra;
		$this->excludeFields = $excludeFields;
		$this->formatter = $formatter;
		$this->httpRequest = $httpRequest;

		if ($this->includeContextAndExtra) {
			$this->normalizerFormatter = new NormalizerFormatter();
		}
	}

	/**
	 * @param array $record
	 * @return array
	 */
	public function getSlackData(array $record): array
	{
		$dataArray = [];
		$record = $this->excludeFields($record);

		if ($this->username) {
			$dataArray['username'] = $this->username;
		}

		if ($this->channel) {
			$dataArray['channel'] = $this->channel;
		}

		if ($this->formatter && !$this->useAttachment) {
			$message = $this->formatter->format($record);
		} else {
			$message = $record['message'];
		}

		if ($this->useAttachment) {
			$attachment = [
				'fallback' => $message,
				'text' => $message,
				'color' => $this->getAttachmentColor($record['level']),
				'fields' => [],
				'mrkdwn_in' => ['fields'],
				'ts' => $record['datetime']->getTimestamp(),
			];

			if ($this->useShortAttachment) {
				$attachment['title'] = $record['level_name'];
			} else {
				$attachment['title'] = 'Message';
				$attachment['fields'][] = $this->generateAttachmentField('Level', $record['level_name']);
				$attachment['fields'][] = $this->generateAttachmentField('URL', $this->httpRequest->getUrl()->getAbsoluteUrl());
			}

			if ($this->includeContextAndExtra) {
				foreach (['extra', 'context'] as $key) {
					if (empty($record[$key])) {
						continue;
					}

					if ($this->useShortAttachment) {
						$attachment['fields'][] = $this->generateAttachmentField(
							ucfirst($key),
							$record[$key]
						);
					} else {
						// Add all extra fields as individual fields in attachment
						$attachment['fields'] = array_merge(
							$attachment['fields'],
							$this->generateAttachmentFields($record[$key])
						);
					}
				}
			}

			$dataArray['attachments'] = [$attachment];
		} else {
			$dataArray['text'] = $message;
		}

		if ($this->userIcon) {
			if (filter_var($this->userIcon, FILTER_VALIDATE_URL)) {
				$dataArray['icon_url'] = $this->userIcon;
			} else {
				$dataArray['icon_emoji'] = ":{$this->userIcon}:";
			}
		}

		return $dataArray;
	}

	/**
	 * Generates attachment field
	 *
	 * @param string $title
	 * @param string|array $value \
	 *
	 * @return array
	 */
	private function generateAttachmentField($title, $value)
	{
		$value = is_array($value)
			? sprintf('```%s```', $this->stringify($value))
			: $value;

		return [
			'title' => $title,
			'value' => $value,
			'short' => false,
		];
	}

	/**
	 * Generates a collection of attachment fields from array
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	private function generateAttachmentFields(array $data)
	{
		$fields = [];
		foreach ($data as $key => $value) {
			$fields[] = $this->generateAttachmentField($key, $value);
		}

		return $fields;
	}

	/**
	 * Get a copy of record with fields excluded according to $this->excludeFields
	 *
	 * @param array $record
	 *
	 * @return array
	 */
	private function excludeFields(array $record)
	{
		foreach ($this->excludeFields as $field) {
			$keys = explode('.', $field);
			$node = &$record;
			$lastKey = end($keys);
			foreach ($keys as $key) {
				if (!isset($node[$key])) {
					break;
				}
				if ($lastKey === $key) {
					unset($node[$key]);
					break;
				}
				$node = &$node[$key];
			}
		}

		return $record;
	}
}
