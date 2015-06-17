<?php

namespace DvsaCommonApiTest\Filter;

use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommonApi\Filter\XssFilter;
use HTMLPurifier;

/**
 * Class XssFilterTest.
 */
class XssFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \DvsaCommonApi\Filter\XssFilter
     */
    protected $filter;

    public function setUp()
    {
        $htmlPurifier = new HTMLPurifier();
        $this->filter = new XssFilter($htmlPurifier);
    }

    public function testScriptTagsAreStripped()
    {
        $filter         = $this->filter;
        $valuesExpected = [
            '<script>alert(document.cookie)</script>'               => '',
            'Hello<script>alert(document.cookie)</script> World!'   => 'Hello World!',
        ];

        // Test using filter()
        foreach ($valuesExpected as $input => $output) {
            $this->assertEquals($filter->filter($input), $output);
        }

        // Test using filterMultiple()
        $filteredValues = $filter->filter($valuesExpected);
        foreach (array_keys($valuesExpected) as $k) {
            $this->assertEquals($valuesExpected[$k], $filteredValues[$k]);
        }
    }

    public function testDto()
    {
        //  --  expected dto (clean)   --
        $addressDto = new AddressDto();
        $addressDto
            ->setAddressLine1('a1 ')
            ->setAddressLine2('a2 "&gt;')
            ->setAddressLine3('a3 <img src="#" alt="#" />')
            ->setAddressLine4('a4 <img src="onmouseover=" alt="onmouseover=&quot;alert(\'xxs\')&quot;" />')
            ->setTown('a5 <img src="jav%20ascript%3Aalert(\'XSS\');" alt="jav ascript:alert(\'XSS\');" />')
            ->setCountry('a6 ')
            ->setPostcode('a7 &lt;');

        $expectDto = new ContactDto();
        $expectDto->setAddress($addressDto)
            ->setPhones(
                [
                    (new PhoneDto)->setNumber('b1 Hello World!'),
                    (new PhoneDto)->setNumber('b2 '),
                    (new PhoneDto)->setNumber('b3 '),
                ]
            );

        //  --  given dto (dirty) with XSS stuff   --
        $addressDto = new AddressDto();
        $addressDto
            ->setAddressLine1('a1 <IMG SRC=JaVaScRiPt:alert("XSS")>')
            ->setAddressLine2('a2 <IMG """><SCRIPT>alert("XSS")</SCRIPT>">')
            ->setAddressLine3('a3 <IMG SRC=# onmouseover="alert(\'xxs\')">')
            ->setAddressLine4('a4 <IMG SRC= onmouseover="alert(\'xxs\')">')
            ->setTown('a5 <IMG SRC="jav&#x0D;ascript:alert(\'XSS\');">')
            ->setCountry('a6 <BODY onload!#$%&()*~+-_.,:;?@[/|\]^`=alert("XSS")>')
            ->setPostcode('a7 <<SCRIPT>alert("XSS");//<</SCRIPT>');

        $contactDto = new ContactDto();
        $contactDto->setAddress($addressDto)
            ->setPhones(
                [
                    (new PhoneDto)->setNumber('b1 Hello<script>alert(document.cookie)</script> World!'),
                    (new PhoneDto)->setNumber('b2 <SCRIPT a=">\'>" SRC="http://ha.ckers.org/xss.js"></SCRIPT>'),
                    (new PhoneDto)->setNumber('b3 <SCRIPT a=`>` SRC="http://ha.ckers.org/xss.js"></SCRIPT>'),
                ]
            );

        $actual = $this->filter->filter($contactDto);

        $this->assertEquals($expectDto, $actual);
    }
}
