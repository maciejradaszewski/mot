<?php


namespace DvsaCommon\Log\Factory;

use Zend\Log\Filter\Priority;
use Zend\Log\Processor\Backtrace;
use Zend\Log\Writer\Syslog;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

use DvsaCommon\Log\Logger;
use DvsaCommon\Log\Formatter;


/**
 * Class ExceptionLoggerFactory.
 *
 * @package DvsaCommon\Log\Factory
 */
class ExceptionLoggerFactory implements FactoryInterface
{
    /**
     * Creates a new logger service for exception handling.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Logger
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');

        if (!array_key_exists('logAppName', $config)) {
            throw new \RuntimeException('The logAppName config key is not set');
        }

        $logger = new Logger();
        $logger->addProcessor(new Backtrace());

        $formatter = new Formatter\Error();
        $writer = new Syslog(['application' => $config['logAppName']]);
        $writer->setFormatter($formatter);
        $writer->addFilter(new Priority(Logger::ERR));

        $logger->addWriter($writer);

        return $logger;
    }
}
