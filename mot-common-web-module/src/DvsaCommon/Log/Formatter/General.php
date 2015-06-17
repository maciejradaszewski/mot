<?php


namespace DvsaCommon\Log\Formatter;

use Zend\Debug\Debug;
use Zend\Log\Formatter\Base;


/**
 * A formatting class for general log messages.
 *
 * @package DvsaCommon\Log\Formatter
 */
class General extends Base
{
    /**
     * @var string
     */
    protected $output;

    public function __construct()
    {
        $output = '%logEntryType%||%openAmUuid%||%openAmSessionToken%||'
                . '%correlationId%||%class%||%message%||%extra%';

        $this->output = $output;

        parent::__construct(null);
    }

    /**
     * Format the event into a message string.
     *
     * @param array $event
     * @return array|mixed|string
     */
    public function format($event)
    {
        $output = $this->output;

        $data = $this->flattenEventData($event);

        foreach ($data as $name => $value) {
            $output = str_replace("%$name%", $this->normalize($value), $output);
        }

        return $output;
    }

    /**
     * DVSA specific data is contained within the $extra parameter of an event,
     * using the __dvsa_metadata__ array key. This method will flatten that
     * array so that we can iterate over with a single for loop.
     *
     * @param array $event and array containing event data.
     * @return array
     */
    protected function flattenEventData(array $event)
    {
        $data = [];

        if (array_key_exists('extra', $event)
            && array_key_exists('__dvsa_metadata__', $event['extra'])
        ) {
            foreach ($event['extra']['__dvsa_metadata__'] as $key => $value) {
                $data[$key] = $value;
            }
            unset($event['extra']['__dvsa_metadata__']);
        }

        return array_merge($event, $data);
    }
}
