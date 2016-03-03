<?php


namespace DvsaCommon\Pdf\Templating\ZendPdf;


use DvsaCommon\Exception\NotImplementedException;
use DvsaCommon\Factory\AutoWire\AutoWireableInterface;
use DvsaCommon\Utility\Lazy;
use ZendPdf\Color\Html;
use ZendPdf\Font;
use ZendPdf\Page;
use ZendPdf\PdfDocument;

class ZendPdfTemplate implements AutoWireableInterface
{
    /**
     * @var string
     */
    protected $templateFile;
    protected $pdfEngine;
    protected $font;
    protected $fontColor;
    protected $fontSize;

    public function __construct()
    {
        $this->pdfEngine = new Lazy(function() {
            if(is_null($this->templateFile)){
                throw new NotImplementedException('You have to set a template PDF file');
            }

            return new PdfDocument($this->templateFile, null, true);
        });
    }

    /**
     * Gets X page from PDF file
     * @param $pageNumber
     * @return Page
     */
    public function getPage($pageNumber)
    {
        return $this->pdfEngine->value()->pages[$pageNumber];
    }

    /**
     * @param string $file path to template file
     */
    public function setTemplateFile($file)
    {
        $this->templateFile = $file;
    }

    /**
     * Renders a PDF file with our changes
     * @return string Pdf as a string
     */
    public function render()
    {
        return $this->pdfEngine->value()->render();
    }

    /**
     * @param string $fontPath
     * @return $this
     */
    public function setFontPath($fontPath)
    {
        $this->font = Font::fontWithPath($fontPath);
        return $this;
    }

    public function setFontColor($fontColor)
    {
        $this->fontColor = Html::color($fontColor);
        return $this;
    }

    public function setFontSize($fontSize)
    {
        $this->fontSize = $fontSize;
        return $this;
    }
    public function drawTextOnPage($pageNumber, $text, $x, $y)
    {
        $this->getPage($pageNumber)
            ->setFont($this->font, $this->fontSize)
            ->setFillColor($this->fontColor)
            ->drawText($text, $x, $y, 'UTF-8');
    }
}