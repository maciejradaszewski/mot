<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Dvsa\Mot\Behat\Support\Api\Certificate;
use Dvsa\Mot\Behat\Support\Api\MotTest;
use PHPUnit_Framework_Assert as PHPUnit;
use Smalot\PdfParser\Document;

class CertificateContext implements Context
{
    /**
     * @var Certificate
     */
    private $certificate;

    /**
     * @var MotTest
     */
    private $motTest;

    /**
     * @var array
     */
    private $motTests;

    /**
     * @var SessionContext
     */
    private $sessionContext;

    /**
     * @var MotTestContext
     */
    private $motTestContext;

    private $certificateResult;

    private $recentTestsCertificateDetails;

    /**
     * @param Certificate $certificate
     * @param MotTest $motTest
     */
    public function __construct(Certificate $certificate, MotTest $motTest)
    {
        $this->certificate = $certificate;
        $this->motTest = $motTest;
    }

    /**
     * @BeforeScenario
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        $this->sessionContext = $scope->getEnvironment()->getContext(SessionContext::class);
        $this->motTestContext = $scope->getEnvironment()->getContext(MotTestContext::class);
    }

    /**
     * @Then /^the certificate contains (.*) pages$/
     */
    public function theCertificateContains($noOfPages)
    {
        $pdf = $this->parsePdf($this->certificateResult);

        PHPUnit::assertEquals($noOfPages, count($pdf->getPages()), 'PDF does not contain correct number of pages');
    }

    /**
     * @Given /^requests the certificate$/
     */
    public function requestsTheCertificate()
    {
        $this->certificateResult = $this->certificate->requestCertificate(
            $this->motTestContext->getMotTestNumber(),
            $this->sessionContext->getCurrentAccessToken()
        );
    }

    /**
     * @Given /^the certificate contains the text (.*)$/
     */
    public function theCertificateContainsTheText($expectedText)
    {
        $pdf = $this->parsePdf($this->certificateResult);

        if ((bool)strpos($expectedText, '&&')) {
            $expectedText = explode('&&', $expectedText);
        }

        if (is_array($expectedText)) {
            foreach ($expectedText as $text) {
                $result = (bool)strpos($pdf->getText(), trim($text));
                PHPUnit::assertTrue($result, 'Could not find expected text in document - ' . $text);
            }
        } else {
            $result = (bool)strpos($pdf->getText(), $expectedText);
            PHPUnit::assertTrue($result, 'Could not find expected text in document - ' . $expectedText);
        }
    }

    /**
     * @When /^I retrieve recent tests certificate details in the VTS recent test was performed$/
     */
    public function iRetrieveRecentTestsCertificateDetails()
    {
        $token = $this->sessionContext->getCurrentAccessToken();
        $vtsId = $this->motTestContext->getMotTestData()['vehicleTestingStation']['id'];
        $this->recentTestsCertificateDetails = $this->motTest->getRecentTestsCertificateDetails($token, $vtsId)->getBody();
    }

    /**
     * @Then /^I can retrieve certificate details for the most recent test from the list$/
     */
    public function certificateDetailsForRecentTestAreAvailable()
    {
        $token = $this->sessionContext->getCurrentAccessToken();
        $certificateDetailsId = $this->recentTestsCertificateDetails['data'][0]['id'];
        $certificateDetailsResponse = $this->motTest->getRecentTestCertificateDetails(
            $token,
            $certificateDetailsId
        );

        PHPUnit::assertEquals(
            200,
            $certificateDetailsResponse->getStatusCode(),
            "Recent test certificate details enpoint did not return 200 code"
        );
    }

    /**
     * @When /^I reprint an MOT Test Certificate$/
     */
    public function iReprintAnMOTTestCertificate()
    {
        $token = $this->sessionContext->getCurrentAccessToken();
        $this->motTest->getMOTCertificateDetails($token, '651157444199');

        $this->certificate->getDuplicateCertificate($token);
    }

    /**
     * @param mixed $certificate
     *
     * @return Document
     *
     * @throws \Exception
     */
    private function parsePdf($certificate)
    {
        try {
            $parser = new \Smalot\PdfParser\Parser();

            return $parser->parseContent($certificate);
        } catch (\Exception $ex) {
            throw new \Exception('Unable to parse the MOT certificate. ' . $ex->getMessage());
        }
    }

    /**
     * @When I fetch jasper document for test
     */
    public function iFetchJasperDocumentForTest()
    {
        foreach($this->motTestContext->getMotTestNumbers() as $motTestNumber)
        {
            $motTest = $this->motTest->getMotData($this->sessionContext->getCurrentAccessToken(), $motTestNumber)->getBody()->toArray()['data'];
            $document = $this->certificate->getJasperDocument($motTest['document'], $this->sessionContext->getCurrentAccessToken())->getBody()->toArray()['data'][0];
            $motTest['document'] = $document;

            $this->motTests[]=$motTest;
        }
    }

    /**
     * @Then document has only odometer readings from tests performed in past
     */
    public function documentHasOnlyOdometerReadingsFromTestsPerformedInPast()
    {
        $fullOdometerHistory = [];
        foreach($this->motTests as $motTest) {
            $fullOdometerHistory[]= [
                'startedDate' => $motTest['startedDate'],
                'odometerReading' => $motTest['odometerReading']
            ];
        }

        foreach($this->motTests as $motTest) {
            $documentContent = json_decode($motTest['document']['document_content'], true);
            $documentOdometerHistory = $documentContent['OdometerHistory'];
            $this->validateOdometerHistory($fullOdometerHistory, $documentOdometerHistory, $motTest['startedDate']);
        }
    }

    private function validateOdometerHistory($fullOdometerHistory, $documentOdometerHistory, $startedDate)
    {
        $expectedOdometerReadings = array_filter($fullOdometerHistory, function($value) use ($startedDate) {
            $motTestDate = new DateTime($startedDate);
            $odometerReadingDate = new DateTime($value['startedDate']);

            return $motTestDate >= $odometerReadingDate;
        });

        $expectedOdometerReadings = array_reverse($expectedOdometerReadings);

        $expectedOdometerValues = array_reduce($expectedOdometerReadings, function($result, $value) {
            $result[]=$value['odometerReading']['value'] . ' ' . $value['odometerReading']['unit'];

            return $result;
        });

        $documentOdometerValues = array_reduce(explode("\n", $documentOdometerHistory), function($result, $value) {
            preg_match('/: ([0-9].*)/', $value, $matches);
            $result[]=$matches[1];

            return $result;
        });

        PHPUnit::assertEquals($expectedOdometerValues, $documentOdometerValues);
    }

    /**
     * @Given jasper documents were not printed
     */
    public function jasperDocumentsWereNotPrinted()
    {
        foreach($this->motTestContext->getMotTestNumbers() as $motTestNumber)
        {
            $motTest = $this->motTest->getMotData($this->sessionContext->getCurrentAccessToken(), $motTestNumber)->getBody()->toArray()['data'];
            $this->certificate->deleteJasperDocument($motTest['document'], $this->sessionContext->getCurrentAccessToken());

            $this->motTests[]=$motTest;
        }
    }

    /**
     * @Given print of created mot tests is issued
     */
    public function printOfCreatedMotTestsIsIssued()
    {
        foreach($this->motTests as $motTest) {
            $this->certificate->requestCertificate($motTest['motTestNumber'], $this->sessionContext->getCurrentAccessToken());
        }
    }
}