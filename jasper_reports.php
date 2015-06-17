#!/usr/bin/env php
<?php

/**
 * Import jasper reports
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
class Runner
{
    private $args;

    private $options = array();

    private $destination;

    private $requiredOptions = array('s', 'd');

    private $optionalOptions = array('u', 'p');

    private $ignoreFiles = array('.', '..', '.DS_Store');

    private $defaultUsername = 'jasperadmin';

    private $defaultPassword = 'jasperadmin';

    private $colors = array(
        'default' => "\e[0m",
        'error' => "\e[0;31m",
        'success' => "\e[0;32m",
        'warning' => "\e[0;33m"
    );

    const ERROR_MISSING_ARGS = 'Please ensure you have a -s [source] and -d [destination] argument set';
    const ERROR_MISSING_SOURCE = 'Unable to find the given source directory';
    const ERROR_CANNOT_CREATE_ZIP = 'Unable to create zip';
    const ERROR_INVALID_RESPONSE = 'Invalid response returned from Jasper';
    const MESSAGE_HELP_TEXT = 'Usage:
    jasper_reports -s /path/to/resources/directory -d http://jasper-host-name';

    public function __construct($args)
    {
        $this->args = $args;

        $this->checkForHelp();
        $this->checkForMissingArgs();
    }

    public function run()
    {
        $source = $this->findSource();
        $this->setDestination();

        $pathToZip = $this->zipSource($source);

        $this->importResources($pathToZip);

        $this->removeZip($pathToZip);

        $this->exitResponse('All done!', 'success');
    }

    private function getOptions()
    {
        if (empty($this->options)) {
            $this->options = getopt(implode(':', array_merge($this->requiredOptions, $this->optionalOptions)) . ':');
        }

        return $this->options;
    }

    private function getDestination()
    {
        return $this->destination;
    }

    private function setDestination()
    {
        $options = $this->getOptions();

        $this->destination = rtrim($options['d'], '/') . '/jasperserver/rest_v2';
    }

    private function checkForHelp()
    {
        if (in_array('--help', $this->args)) {
            $this->exitResponse(self::MESSAGE_HELP_TEXT);
        }
    }

    private function checkForMissingArgs()
    {
        if ($this->isMissingRequiredOptions()) {
            $this->exitResponse(self::ERROR_MISSING_ARGS, 'error');
        }
    }

    private function getUsername()
    {
        return isset($this->getOptions()['u']) ? $this->getOptions()['u'] : $this->defaultUsername;
    }

    private function getPassword()
    {
        return isset($this->getOptions()['p']) ? $this->getOptions()['p'] : $this->defaultPassword;
    }

    private function importResources($zip)
    {
        $this->respond('Importing resources into jasper: ' . $this->getDestination());

        $response = $this->makeRequest('/import', array('Content-Type: application/zip'), file_get_contents($zip), true);

        if (isset($response['phase']) && $response['phase'] == 'inprogress') {
            while (isset($response['phase']) && $response['phase'] == 'inprogress') {
                sleep(1);
                $response = $this->checkProgress($response['id']);
            }
        }

        if (
            isset($response['phase'])
            && $response['phase'] == 'finished'
            && $response['message'] == 'Import succeeded.'
        ) {
            $this->respond('Successfully imported', 'success');
        } else {
            $this->respond('Import failed', 'error');
        }

        return $response;
    }

    private function checkProgress($id)
    {
        $this->respond('Checking progress...', 'warning');

        return $this->makeRequest('/export/' . $id . '/state', array());
    }

    private function makeRequest($destination, $headers, $data = array(), $post = false)
    {
        $username = $this->getUsername();
        $password = $this->getPassword();

        $ch = curl_init();

        $headers[] = 'Authorization: Basic ' . base64_encode($username . ':' . $password);
        $headers[] = 'Accept: application/json';

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_URL, $this->getDestination() . $destination);
        curl_setopt($ch, CURLOPT_POST, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);

        curl_close($ch);

        $arrayResult = json_decode($result, true);

        if (json_last_error() == JSON_ERROR_NONE) {
            return $arrayResult;
        }

        switch (true) {
            case strstr($result, 'HTTP Status 401 - Bad credentials'):
                $extra = 'Bad credentials';
                break;
            default:
                $extra = $result;
                break;
        }

        $this->exitResponse(self::ERROR_INVALID_RESPONSE . ': ' . $extra, 'error');
    }

    private function zipSource($source)
    {
        $this->respond('Zipping resources in ' . $source);

        $outputZip = __DIR__ . '/jasper_reports_' . time() . '.zip';

        $zipArchive = new ZipArchive();
        $zipArchive->open($outputZip, ZipArchive::CREATE);

        $this->addFilesToZip($source, $zipArchive);

        $zipArchive->close();

        if (file_exists($outputZip)) {
            $this->respond('Resources successfully zipped!', 'success');
            return $outputZip;
        }

        $this->exitResponse(self::ERROR_CANNOT_CREATE_ZIP, 'error');
    }

    private function addFilesToZip($source, $zipArchive)
    {
        $files = $this->getFilesFromSource($source);

        foreach ($files as $file) {

            if (in_array($file->getFilename(), $this->ignoreFiles)) {
                continue;
            }

            if ($file->isDir()) {
                $zipArchive->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } else if ($file->isFile()) {
                $zipArchive->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }

    private function removeZip($zip)
    {
        $this->respond('Removing zip');

        unlink($zip);

        if (!file_exists($zip)) {
            $this->respond('Zip removed', 'success');
            return;
        }

        $this->respond('Zip was not removed: ' . $zip, 'warning');
    }

    private function getFilesFromSource($source)
    {
        return new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($source),
            RecursiveIteratorIterator::SELF_FIRST
        );
    }

    private function findSource()
    {
        $options = $this->getOptions();

        if (file_exists(realpath($options['s']))) {
            return realpath($options['s']);
        }

        $this->exitResponse(self::ERROR_MISSING_SOURCE, 'error');
    }

    private function isMissingRequiredOptions()
    {
        $options = $this->getOptions();

        foreach ($this->requiredOptions as $opt) {
            if (!isset($options[$opt]) || empty($options[$opt])) {
                return true;
            }
        }

        return false;
    }

    private function respond($message, $type = 'default')
    {
        echo $this->colors[$type];

        if ($type != 'default') {
            echo ucwords($type) . ': ';
        }

        echo $message . $this->colors['default'] . "\n";
    }

    private function exitResponse($message, $type = 'default')
    {
        $this->respond($message, $type);
        exit;
    }
}

$runner = new Runner($argv);

$runner->run();
