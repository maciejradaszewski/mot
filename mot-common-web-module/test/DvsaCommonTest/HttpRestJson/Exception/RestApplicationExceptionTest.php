<?php
namespace DvsaCommonTest\HttpRestJson\Exception;

use DvsaCommon\HttpRestJson\Exception\RestApplicationException;

use PHPUnit_Framework_TestCase;

class RestApplicationExceptionTest extends PHPUnit_Framework_TestCase
{
    const TEST_RESOURCE_PATH = 'papapapath';
    const TEST_METHOD = 'insert';
    const TEST_POST_DATA = 'postdata';
    const TEST_STATUS_CODE = 666;
    const TEST_MESSAGE_FOR_REQUEST_DATA = "resourcePath='papapapath',
                method='insert',
                postData='\"postdata\"',
                statusCode='666',
                errors=''";

    public function testIsException()
    {
        $RestApplicationException = $this->getTestRestApplicationException();
        $this->assertInstanceOf('Exception', $RestApplicationException);
    }

    public function testGetters()
    {
        $RestApplicationException = $this->getTestRestApplicationException();
        $this->assertEquals(self::TEST_RESOURCE_PATH, $RestApplicationException->getResourcePath());
        $this->assertEquals(self::TEST_METHOD, $RestApplicationException->getMethod());
        $this->assertEquals(self::TEST_POST_DATA, $RestApplicationException->getPostData());
        $this->assertEquals(self::TEST_MESSAGE_FOR_REQUEST_DATA, $RestApplicationException->getMessageForRequestData());
        $this->assertEquals(array(), $RestApplicationException->getDisplayMessages());
    }

    public function testDisplayMessagesAreInArrayAndUnique()
    {
        $RestApplicationException = $this->getTestRestApplicationExceptionWithErrors();
        $this->assertEquals(
            array('testDisplayMessage1', 'notUniqueDisplayMessage'),
            $RestApplicationException->getDisplayMessages()
        );
    }

    public function testConstructorWithErrorData()
    {
        $exception = $this->getTestRestApplicationExceptionWithErrorsAndErrorData();
        $this->assertInstanceOf('Exception', $exception);
    }

    public function testGetErrorDataWithFormMessages()
    {
        $exception = $this->getTestRestApplicationExceptionWithErrorsAndErrorData();

        // Check that we have 1 error - the site number message if a form field error and should not be displayed.
        $this->assertEquals(
            array('testDisplayMessage1'),
            $exception->getDisplayMessages()
        );
    }


    public function testGetFormErrorDisplayMessages()
    {
        $exception = $this->getTestRestApplicationExceptionWithErrorsAndErrorData();

        // Check that we have a form field message structure
        $this->assertEquals(
            array(
                array(
                    'displayMessage' => 'siteNumber is required',
                    'ref' => 1
                )
            ),
            $exception->getFormErrorDisplayMessages()
        );
    }


    public function testGetExpandedErrorData()
    {
        $exception = $this->getTestRestApplicationExceptionWithErrorsAndErrorData();
        $expandedErrorData = $exception->getExpandedErrorData();
        $this->assertEquals(
            array(
                array(
                    'siteNumber' =>
                        array(
                            'error' => array('displayMessage' => 'siteNumber is required'),
                            'ref'            => 1,
                        )
                )
            ),
            $expandedErrorData
        );
    }

    public function testContainsError()
    {
        $e = $this->getTestRestApplicationExceptionWithErrors();
        $this->assertTrue($e->containsError('testDisplayMessage1'));
        $this->assertFalse($e->containsError('testDisplayMessage1NotThere'));
    }

    protected function getTestRestApplicationException()
    {
        return new RestApplicationException(
            self::TEST_RESOURCE_PATH,
            self::TEST_METHOD,
            self::TEST_POST_DATA,
            self::TEST_STATUS_CODE
        );
    }

    protected function getTestRestApplicationExceptionWithErrors()
    {
        return new RestApplicationException(
            self::TEST_RESOURCE_PATH,
            self::TEST_METHOD,
            self::TEST_POST_DATA,
            self::TEST_STATUS_CODE,
            $this->getTestErrors()
        );
    }

    protected function getTestRestApplicationExceptionWithErrorsAndErrorData()
    {
        return new RestApplicationException(
            self::TEST_RESOURCE_PATH,
            self::TEST_METHOD,
            self::TEST_POST_DATA,
            self::TEST_STATUS_CODE,
            $this->getTestErrorsForErrorData(),
            $this->getTestErrorData()
        );
    }

    protected function getTestErrors()
    {
        return array(
              array('displayMessage' => 'testDisplayMessage1'),
              array('displayMessage' => 'notUniqueDisplayMessage'),
              array('displayMessage' => 'notUniqueDisplayMessage'),
            );
    }


    protected function getTestErrorsForErrorData()
    {
        return array(
            array('displayMessage' => 'testDisplayMessage1'),
            array('displayMessage' => 'siteNumber is required')
        );
    }

    protected function getTestErrorData()
    {
        return
        array(
            array('siteNumber'=> 1)
        );
    }
}