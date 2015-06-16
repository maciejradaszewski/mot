<?php
/**
 * Report Controller Trait.
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */

namespace Dvsa\Mot\Frontend\Traits;

use DvsaReport\Service\Csv\CsvService;
use DvsaReport\Service\Pdf\PdfService;
use Zend\View\Model\ViewModel;

/**
 * Report Controller Trait is responsible for creating a report.
 *
 * @author Rob Caiger <rob@clocal.co.uk>
 */
trait ReportControllerTrait
{
    /**
     * Holds the renderer.
     *
     * @var object
     */
    private $renderer;

    /**
     * Render the report.
     *
     * @param array   $reportVariables
     * @param string  $reportTemplate
     * @param string  $documentName
     * @param boolean $renderHeader
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function renderReport($reportVariables, $reportTemplate, $documentName, $renderHeader = true)
    {
        $reportVariables['isPdf'] = false;
        // We need to configure the report whether we are generating a PDF or just rendering a view
        $viewModel = new ViewModel($reportVariables);
        $viewModel->setTemplate($reportTemplate);

        // If we have an extension, we need to create a document
        $extension = $this->params()->fromRoute('extension');

        if ($extension == '.csv') {
            // If we want a CSV
            return $this->generateCsvResponse($reportVariables['table'], $documentName);
        } elseif ($extension == '.pdf') {
            $viewModel->setVariable('isPdf', true);
            // If we want a PDF
            return $this->generatePdfResponse($viewModel, $documentName, $renderHeader);
        }

        return $viewModel;
    }

    /**
     * Generate CSV response.
     *
     * @param object $table
     * @param string $documentName
     *
     * @return \Zend\Http\Response
     */
    private function generateCsvResponse($table, $documentName)
    {
        $csvService = new CsvService();
        $csvService->setData($this->getCsvDataFromTable($table));
        $csvService->setResponse($this->getResponse());

        return $csvService->generateDocument($documentName.'.csv');
    }

    /**
     * Get csv data from table.
     *
     * @param type $table
     *
     * @return type
     */
    private function getCsvDataFromTable($table)
    {
        $columns = $table->getColumns();
        $rows    = $table->getRows();
        $csvData = [];

        foreach ($rows as $row) {
            $dataRow = [];
            foreach ($columns as $column) {
                $dataRow[$column->getTitle()] = $column->renderCellContent(
                    $row,
                    $this->getRenderer()
                );
            }
            $csvData[] = $dataRow;
        }

        return $csvData;
    }

    /**
     * Generate PDF Response.
     *
     * @param ViewModel $viewModel
     * @param string    $documentName
     *
     * @return Response
     */
    private function generatePdfResponse($viewModel, $documentName, $renderHeader)
    {
        $uri        = $this->getRequest()->getUri();
        $base       = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());
        $pdfService = new PdfService();

        $html = $pdfService->replaceWebRoot($this->getRenderer()->render($viewModel), $base);

        $layoutParams = [
            'content'      => $html,
            'base'         => $base,
            'renderHeader' => $renderHeader,
        ];

        // Setup the PDF layout, and add the reportTemplate content
        $layout = new ViewModel($layoutParams);
        $layout->setTemplate('partials/table/pdf-layout');
        // Create the PDF
        $pdfService->setHtml($this->getRenderer()->render($layout));
        $pdfService->setResponse($this->getResponse());

        return $pdfService->generateDocument($documentName.'.pdf');
    }

    /**
     * Get the view renderer.
     *
     * @return object
     */
    private function getRenderer()
    {
        if (empty($this->renderer)) {
            $this->renderer = $this->getServiceLocator()->get('ViewRenderer');
        }

        return $this->renderer;
    }
}
