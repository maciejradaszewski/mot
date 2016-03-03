<?php

namespace DvsaMotTest\Service;

use Core\Service\MotFrontendIdentityProviderInterface;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\HttpRestJson\Client;
use DvsaCommon\HttpRestJson\Exception\GeneralRestException;
use DvsaCommon\Pdf\Templating\ZendPdf\ZendPdfTemplate;
use DvsaCommon\UrlBuilder\MotTestUrlBuilder;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommonApi\Service\Exception\NotFoundException;
use DvsaMotTest\Model\MotChecklistPdfField;
use DvsaMotTest\Presenter\MotChecklistPdfPresenter;

class MotChecklistPdfService
{
    const PDF_PAGE_NUMBER = 0;
    const MOT_CHECKLIST_CONFIG_KEY_MAIN = 'mot_checklist';
    const MOT_CHECKLIST_CONFIG_KEY_TEMPLATES = 'templates';
    const MOT_CHECKLIST_CONFIG_KEY_FONTS = 'fonts';
    const MOT_CHECKLIST_CONFIG_KEY_CAR = 'car';
    const MOT_CHECKLIST_CONFIG_KEY_MOTORBIKE = 'motorbike';
    const MOT_CHECKLIST_CONFIG_KEY_MONOSPACED = 'monospaced';

    protected $jsonClient;
    protected $authorisationService;
    protected $pdfService;
    protected $pdfConfig;
    protected $motChecklistPdfPresenter;


    public function __construct(
        Client $jsonClient,
        MotFrontendIdentityProviderInterface $authorisationService,
        ZendPdfTemplate $zendPdfTemplate,
        MotChecklistPdfPresenter $motChecklistPdfPresenter,
        array $pdfConfig
    )
    {
        $this->jsonClient = $jsonClient;
        $this->authorisationService = $authorisationService;
        $this->pdfService = $zendPdfTemplate;
        $this->motChecklistPdfPresenter = $motChecklistPdfPresenter;
        $this->pdfConfig = $pdfConfig;
    }

    /**
     * Generates PDF checklist for given MOT test number
     * @param $motTestNumber
     * @return string PDF as a string
     * @throws NotFoundException
     */
    public function getChecklistPdf($motTestNumber)
    {
        try {
            $motTestDto = $this->getMotTestFromApi($motTestNumber);
        } catch (GeneralRestException $e) {
            throw new NotFoundException('MOT test');
        }

        $this->motChecklistPdfPresenter->setMotTest($motTestDto);
        $this->motChecklistPdfPresenter->setIdentity($this->authorisationService->getIdentity());

        $this->configurePdfService();
        $this->populateTemplate($this->motChecklistPdfPresenter->getDataFields());

        return $this->pdfService->render();
    }

    /**
     * @param $motTestNumber
     * @return MotTestDto
     */
    protected function getMotTestFromApi($motTestNumber)
    {
        $apiUrl = MotTestUrlBuilder::motTest($motTestNumber)->toString();
        $result = $this->jsonClient->get($apiUrl);

        $data = ArrayUtils::tryGet($result, 'data');

        return $data;
    }

    protected function configurePdfService()
    {
        if ($this->motChecklistPdfPresenter->isClass1or2Vehicle()) {
            $this->pdfService->setTemplateFile(
                $this->pdfConfig[static::MOT_CHECKLIST_CONFIG_KEY_TEMPLATES][static::MOT_CHECKLIST_CONFIG_KEY_MOTORBIKE]
            );
        } else {
            $this->pdfService->setTemplateFile(
                $this->pdfConfig[static::MOT_CHECKLIST_CONFIG_KEY_TEMPLATES][static::MOT_CHECKLIST_CONFIG_KEY_CAR]
            );
        }

        $this->pdfService->setFontPath(
            $this->pdfConfig[static::MOT_CHECKLIST_CONFIG_KEY_FONTS][static::MOT_CHECKLIST_CONFIG_KEY_MONOSPACED]
        );
    }

    /**
     * @param MotChecklistPdfField[] $dataFields
     */
    protected function populateTemplate(array $dataFields)
    {
        foreach ($dataFields as $field) {
            $this->pdfService->setFontColor($field->getFontColor());
            $this->pdfService->setFontSize($field->getFontSize());
            $this->pdfService->drawTextOnPage(
                self::PDF_PAGE_NUMBER, $this->getNotEmptyText($field->getText()), $field->getXCoordinate(), $field->getYCoordinate()
            );
        }
    }

    /**
     * @param string|int $originalText
     * @return string
     */
    protected function getNotEmptyText($originalText)
    {
        if (!empty($originalText)) {
            return $originalText;
        } else {
            return '';
        }
    }

}