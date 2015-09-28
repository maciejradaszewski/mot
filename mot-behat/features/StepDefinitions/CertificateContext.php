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
        $certificateDetailsId = $this->recentTestsCertificateDetails['data']['items'][0]['id'];
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
}