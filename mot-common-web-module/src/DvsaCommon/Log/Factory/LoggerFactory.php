<?php


namespace DvsaCommon\Log\Factory;

use Zend\Log\Filter\Priority;
use Zend\Log\Writer\Syslog;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use DvsaCommon\Log\Logger;
use DvsaCommon\Log\Formatter;

/**
 * Class LoggerFactory.
 * @package DvsaCommon\Log\Factory
 */
class LoggerFactory implements FactoryInterface
{
    /**
     * Creates a general purpose logger.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Logger|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (!array_key_exists('logAppName', $config)) {
            throw new \RuntimeException('The logAppName config key is not set');
        }

        $logger = new Logger();

        $priority = Logger::INFO;
        if (array_key_exists('logLevel', $config)) {
            $priority = $config['logLevel'];
        }

        $formatter = new Formatter\General();
        $writer = new Syslog(['application' => $config['logAppName']]);
        $writer->setFormatter($formatter);
        $writer->addFilter(new Priority($priority));

        $logger->addWriter($writer);

        return $logger;
    }
}
