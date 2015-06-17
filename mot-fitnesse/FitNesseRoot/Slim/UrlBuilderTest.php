<?php

use MotFitnesse\Util\UrlBuilder;

class UrlBuilderTest extends PHPUnit_Framework_TestCase
{
    const HTTP_VOSA_MOT_API_8080 = 'http://mot-api:8080';

    public function testShouldBuildMainUrl()
    {
        $this->assertSame(self::HTTP_VOSA_MOT_API_8080, (new UrlBuilder())->toString());
    }

    public function testShouldBuildUrlWithoutParameters()
    {
        $this->assertSame(self::HTTP_VOSA_MOT_API_8080 . '/', (new UrlBuilder())->index()->toString());
    }

    public function testShouldBuildUrlWithoutProvidingOptionalParameters()
    {
        $this->assertSame(self::HTTP_VOSA_MOT_API_8080 . '/session', (new UrlBuilder())->session()->toString());
    }

    public function testShouldBuildUrlWithRequiredParameterProvided()
    {
        $this->assertSame(
            self::HTTP_VOSA_MOT_API_8080 . '/mot-test/test-item-selector/12',
            (new UrlBuilder())->motTest()->testItemSelector()->routeParam('tisId', 12)->toString()
        );
    }

    public function testShouldBuildUrlWithOptionalParameterProvided()
    {
        $this->assertSame(
            self::HTTP_VOSA_MOT_API_8080 . '/session/12', (new UrlBuilder())->session()->routeParam('id', 12)->toString()
        );
    }

    public function testShouldBuildUrlWithTwoParametersProvided()
    {
        $this->assertSame(
            self::HTTP_VOSA_MOT_API_8080 . '/application/12/designated-manager/13',
            (new UrlBuilder())->authorisedExaminerDesignatedManager()->routeParam('uuid', 12)->routeParam('aedmId', 13)->toString()
        );
    }

    public function testShouldAppendGetParamToEndOfUrl()
    {
        $this->assertSame(
            self::HTTP_VOSA_MOT_API_8080 . '/session?key=value',
            (new UrlBuilder())->session()->queryParam('key', 'value')->toString()
        );
    }

    public function testShouldAppendArrayOfGetParametersToEndOfUrl()
    {
        $this->assertSame(
            self::HTTP_VOSA_MOT_API_8080 . '/session?key=value&key2=value2',
            (new UrlBuilder())->session()->queryParams(array('key' => 'value', 'key2' => 'value2'))->toString()
        );
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Routes are in wrong order
     */
    public function testShouldThrowExceptionWhenRoutesAreInWrongOrder()
    {
        (new UrlBuilder())->reasonForRejection()->toString();
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Providing param when no route specified
     */
    public function testShouldThrowExceptionWhenNoRouteHasBeenEnteredAndParamIsProvided()
    {
        (new UrlBuilder())->routeParam('id', 12);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Incorrect param id = 12 for route /reason-for-rejection
     */
    public function testShouldThrowExceptionWhenRouteDoesNotHaveParamsOrParamNameIsIncorrect()
    {
        (new UrlBuilder())->reasonForRejection()->routeParam('id', 12);
    }

    /**
     * @expectedException RuntimeException
     * @expectedExceptionMessage Missing required parameter in route: /test-item-selector/:tisId
     */
    public function testShouldThrowExceptionWhenRequiredParameterIsNotProvided()
    {
        (new UrlBuilder())->motTest()->testItemSelector()->toString();
    }
}
