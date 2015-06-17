<?php

use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\TestShared;

use ZendPdf\PdfDocument;
use ZendPdf\Exception\RuntimeException;

class Vm4352PrintCertificateThroughAPI
{
    public $username = TestShared::USERNAME_ENFORCEMENT;
    public $password = TestShared::PASSWORD;
    public $apiResult;
    public $apiResultInfo;
    public $pdfResult;

    private $motTestId;
    private $acceptHeader;
    private $minimumSize;
    private $noOfPages;

    private $maximumSize;

    private $initialised = false;


    /**
     * @return mixed
     */
    protected function initialise()
    {
        $this->apiResult = null;

        if ($this->acceptHeader == 'application/pdf')
        {
            $this->initialisePdf();
        }
        elseif ($this->acceptHeader == 'text/html')
        {
            $this->initialiseHtml();
        }
        else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    protected function initialisePdf()
    {

        $this->apiResult = TestShared::execCurlFormPostForContentTypeFromUrlBuilder(
            'application/pdf',
            $this,
            (new UrlBuilder())->printCertificate()->routeParam('motTestId', $this->motTestId),
            ['motTestId' => $this->motTestId],
            ["Accept: {$this->acceptHeader}"]
        );

        if (false === $this->apiResult) {
            return false;
        }

        $this->apiResultInfo = TestShared::$lastInfo;

        try {
            $pdf = PdfDocument::parse($this->apiResult);
        } catch (RuntimeException $e) {
            error_log($e->getMessage());
            return false;
        }

        $this->pdfResult = $pdf;
        $this->initialised = true;
    }

    /**
     * @return mixed
     */
    protected function initialiseHtml()
    {
        $this->apiResult = TestShared::execCurlFormPostForContentTypeFromUrlBuilder(
            'text/html',
            $this,
            (new UrlBuilder())->printCertificate()->routeParam('motTestId', $this->motTestId),
            ['motTestId' => $this->motTestId],
            ["Accept: {$this->acceptHeader}"]
        );

        if (false === $this->apiResult) {
            return false;
        }

        $this->apiResultInfo = TestShared::$lastInfo;
        $this->initialised = true;
    }


    /**
     * @return bool
     */
    public function success()
    {
        if (!$this->initialised)
        {
            return "Failed to initialise curl call";
        }

        if ($this->minimumSize && $this->maximumSize)
        {
            if (!($this->minimumSize <= $this->apiResultInfo['download_content_length'] && $this->maximumSize >= $this->apiResultInfo['download_content_length']))
            {
                return sprintf("Content size fail: Min size: [%d]; Max size: [%d]; Content size: [%d]", $this->minimumSize, $this->maximumSize, $this->apiResultInfo['download_content_length']);
            }
        }
        if ($this->apiResultInfo['http_code'] != 200) {
            return "Incorrect response code returned [{$this->apiResultInfo['http_code']}]";
        }

        if ($this->acceptHeader == 'application/pdf')
        {
            return $this->successPDF();
        }
        elseif ($this->acceptHeader == 'text/html')
        {
            return $this->successHtml();
        }
        return "Unknown Accept type specified";
    }

    /**
     * @return bool
     */
    protected function successPDF()
    {
        if (!$this->pdfResult)
        {
            return "Unable to parse PDF with ZendPDF";
        }

        // Perform checks on the parsed PdfDocument object
        if ($this->noOfPages != count($this->pdfResult['pages'])) {
            return sprintf("PDF page count fail: Expected: [%d]; Pages found: [%d]", $this->noOfPages, count($this->pdfResult['pages']));
        }

        return true;
    }

    protected function successHtml()
    {
        if (false === stristr($this->apiResult, '<html>'))
        {
            return "Content not html";
        }

        return true;
    }



    /**
     * @param integer $motTestId
     */
    public function setMotTestId($motTestId)
    {
        $this->motTestId = $motTestId;
    }

    /**
     * @param string $acceptHeader
     */
    public function setAcceptHeader($acceptHeader)
    {
        $this->acceptHeader = $acceptHeader;
        $this->initialise();
    }

    /**
     * @return string content type
     */
    public function contentType()
    {
        return $this->apiResultInfo['content_type'];
    }

    /**
     * @param integer $noOfPages
     */
    public function setNoOfPages($noOfPages)
    {
        $this->noOfPages = $noOfPages;
    }

    /**
     * @param integer $maximumSize
     */
    public function setMaximumSize($maximumSize)
    {
        $this->maximumSize = $maximumSize;
    }

    /**
     * @param integer $minimumSize
     */
    public function setMinimumSize($minimumSize)
    {
        $this->minimumSize = $minimumSize;
    }

}
