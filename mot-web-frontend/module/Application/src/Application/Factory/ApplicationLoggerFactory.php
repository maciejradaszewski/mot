<?php

namespace Application\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Log\Filter\Priority;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class ApplicationLoggerFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $logger = new Logger;

        $config = $serviceLocator->get('config');
        if (array_key_exists('logPath', $config)) {
            $logPath = $config['logPath'];
            $writer = new Stream($logPath);

            $logLevel = Logger::WARN;
            if (array_key_exists('logLevel', $config)) {
                $logLevel = $config['logLevel'];
            }
            $filter = new Priority($logLevel);
            $writer->addFilter($filter);

            $logger->addWriter($writer);
        } else {
            $writer = new Stream('php://output');
            $filter = new Priority(Logger::EMERG);
            $writer->addFilter($filter);
            $logger->addWriter($writer);
        }
        Logger::registerErrorHandler($logger);

        return $logger;
    }
}
