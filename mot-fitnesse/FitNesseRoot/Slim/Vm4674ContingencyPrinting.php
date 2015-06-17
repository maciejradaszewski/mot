<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

use ZendPdf\PdfDocument;
use ZendPdf\Exception\RuntimeException;


class Vm4674ContingencyPrinting
{
    protected $data;
    protected $result;
    protected $username = 'ft-tester';
    protected $password = TestShared::PASSWORD;


    public function __construct()
    {
        $this->result = 'fail';
        $this->data = [];
    }

    // using __call would be nice BUT it seems to break....maybe later!
//    public function __call($fn, $args)
//    {
//        return parent::__call($fn, $args);
//    }

    public function setDescription()
    {
    }

    public function setContentType($v)
    {
        $this->data['contentType'] = $v;
    }

    public function setCertificateName($v)
    {
        $this->data['certificateName'] = $v;
    }

    public function setTestStation($v)
    {
        $this->data['testStation'] = $v;
    }

    public function setInspectionAuthority($v)
    {
        $this->data['inspectionAuthority'] = $v;
    }

    public function result()
    {
        $urlString = (new UrlBuilder())
            ->printContingencyCertificate()
            ->routeParam('name', $this->valOrMt('certificateName'))
            ->toString();

        $qryString = http_build_query(
            [
                'testStation'   => $this->valOrMt('testStation'),
                'inspAuthority' => $this->valOrMt('inspectionAuthority')
            ]
        );

        $this->result = TestShared::getPdf(
            $urlString . '?' . $qryString,
            $this->username,
            $this->password,
            [
                'Accept: application/pdf'
            ]
        );

        // pick apart the response to coalesce it into a response of 'pass' or 'fail'
        if (200 == $this->result['info']['http_code']) {
            return 'pass';
        }
        return 'fail';
    }

    protected function valOrMt($key)
    {
        return array_key_exists($key, $this->data)
            ? $this->data[$key]
            : '';
    }
}
