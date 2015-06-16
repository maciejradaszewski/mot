<?php

namespace ReportTest\Table\Formatter;

use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;
use DvsaCommonTest\TestUtils\TestCaseViewTrait;
use Report\Table\ColumnOptions;
use Report\Table\Formatter\MotTestLink;
use Zend\Mvc\Router\RouteMatch;
use Zend\ServiceManager\ServiceManager;
use Zend\Stdlib\Parameters;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver as Resolver;

class MotTestLinkTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseViewTrait;

    public function testFormat()
    {
        //  logical block: create view renderer
        $renderer = $this->getPhpRenderer(
            [
                'table/formatter/mot-test-link' => __DIR__ . '/../../../../view/table/formatter/mot-test-link.phtml',
            ]
        );

        // logical block: prepare parameters
        $column = new ColumnOptions();
        $column->setField('testFieldA');

        $expectMotTestNr = 99999;
        $expectFieldValue = 'testFieldAValue';

        $rowData = [
            'motTestNumber' => $expectMotTestNr,
            'testFieldA'    => $expectFieldValue,
        ];

        //  logical block: call
        $output = MotTestLink::format($rowData, $column, $renderer);

        //  logical block: check
        $this->assertEquals(
            sprintf(
                "<a href=\"%s\">\n    %s</a>",
                MotTestUrlBuilderWeb::motTest($expectMotTestNr),
                $expectFieldValue
            ),
            $output
        );
    }
}
