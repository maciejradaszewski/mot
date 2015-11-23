<?php
/**
 * This file is part of the DVSA MOT Common project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaCommonTest\Dto\MotTesting;

use DateTime;
use DvsaCommon\Dto\JsonUnserializable;
use DvsaCommon\Dto\MotTesting\ContingencyTestDto;
use DvsaCommonTest\Dto\AbstractDtoTester;
use JsonSerializable;

/**
 * Unit tests for class ContingencyMotTest.
 */
class ContingencyTestDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = ContingencyTestDto::class;

    /**
     * @dataProvider dateProvider
     *
     * @param bool   $isValid
     * @param string $year
     * @param string $month
     * @param string $day
     * @param string $hour
     * @param string $minute
     * @param string $amPm
     */
    public function testFilterDateFormat($isValid, $year, $month, $day, $hour, $minute, $amPm)
    {
        $dto = new ContingencyTestDto();

        /** @var DateTime|bool $datetime */
        $datetime = DateTime::createFromFormat(ContingencyTestDto::DATETIME_FORMAT,
            sprintf('%s-%s-%s %s:%s%s', $year, $month, $day, $hour, $minute, $amPm));

        if (false == $isValid) {
            $this->assertFalse($datetime);

            return;
        }

        $this->assertInstanceOf(DateTime::class, $datetime);
        $dto->setPerformedAt($datetime);
        $this->assertEquals($year, $dto->getPerformedAtYear());
        $this->assertEquals($month, $dto->getPerformedAtMonth());
        $this->assertEquals($day, $dto->getPerformedAtDay());
        $this->assertEquals($hour, $dto->getPerformedAtHour());
        $this->assertEquals($minute, $dto->getPerformedAtMinute());
        $this->assertEquals($amPm, $dto->getPerformedAtAmPm());
    }

    /**
     * @return array
     */
    public function dateProvider()
    {
        return [
            [true, '2014', '01', '01','8', '30', 'am'],
            [true, '2015', '04', '22', '8', '20', 'pm'],
            [false, '2014', '01', '01','18', '30', 'am'],
            [false, '2015', '11', '11', '5', '30', ''],
        ];
    }

    public function testIsJsonSerializableWithFullData()
    {
        $dto = new ContingencyTestDto();
        $dto->setSiteId('1');
        $dto->setPerformedAt(DateTime::createFromFormat(ContingencyTestDto::DATETIME_FORMAT,
            '2015-04-22 8:20pm'));
        $dto->setReasonCode('OT');
        $dto->setOtherReasonText('Because reasons');
        $dto->setContingencyCode('12345A');

        $this->assertInstanceOf(JsonSerializable::class, $dto);
        $serializableData = $dto->jsonSerialize();
        $this->assertEquals('1', $serializableData['siteId']);
        $this->assertEquals('2015', $serializableData['performedAtYear']);
        $this->assertEquals('04', $serializableData['performedAtMonth']);
        $this->assertEquals('22', $serializableData['performedAtDay']);
        $this->assertEquals('8', $serializableData['performedAtHour']);
        $this->assertEquals('20', $serializableData['performedAtMinute']);
        $this->assertEquals('pm', $serializableData['performedAtAmPm']);
        $this->assertEquals('OT', $serializableData['reasonCode']);
        $this->assertEquals('Because reasons', $serializableData['otherReasonText']);
        $this->assertEquals('12345A', $serializableData['contingencyCode']);

        $jsonData = json_encode($dto);
        $this->assertInternalType('string', $jsonData);

        $fromJson = json_decode($jsonData, true);
        $this->assertEquals('1', $fromJson['siteId']);
        $this->assertEquals('2015', $fromJson['performedAtYear']);
        $this->assertEquals('04', $fromJson['performedAtMonth']);
        $this->assertEquals('22', $fromJson['performedAtDay']);
        $this->assertEquals('8', $fromJson['performedAtHour']);
        $this->assertEquals('20', $fromJson['performedAtMinute']);
        $this->assertEquals('pm', $fromJson['performedAtAmPm']);
        $this->assertEquals('OT', $fromJson['reasonCode']);
        $this->assertEquals('Because reasons', $fromJson['otherReasonText']);
        $this->assertEquals('12345A', $fromJson['contingencyCode']);
    }

    public function testIsJsonSerializableWithEmptyData()
    {
        $dto = new ContingencyTestDto();

        $this->assertInstanceOf(JsonSerializable::class, $dto);
        $serializableData = $dto->jsonSerialize();
        $this->assertEquals(null, $serializableData['siteId']);
        $this->assertEquals(null, $serializableData['performedAtYear']);
        $this->assertEquals(null, $serializableData['performedAtMonth']);
        $this->assertEquals(null, $serializableData['performedAtDay']);
        $this->assertEquals(null, $serializableData['performedAtHour']);
        $this->assertEquals(null, $serializableData['performedAtMinute']);
        $this->assertEquals(null, $serializableData['performedAtAmPm']);
        $this->assertEquals(null, $serializableData['reasonCode']);
        $this->assertEquals(null, $serializableData['otherReasonText']);
        $this->assertEquals(null, $serializableData['contingencyCode']);

        $jsonData = json_encode($dto);
        $this->assertInternalType('string', $jsonData);

        $fromJson = json_decode($jsonData, true);
        $this->assertEquals(null, $fromJson['siteId']);
        $this->assertEquals(null, $fromJson['performedAtYear']);
        $this->assertEquals(null, $fromJson['performedAtMonth']);
        $this->assertEquals(null, $fromJson['performedAtDay']);
        $this->assertEquals(null, $fromJson['performedAtHour']);
        $this->assertEquals(null, $fromJson['performedAtMinute']);
        $this->assertEquals(null, $fromJson['performedAtAmPm']);
        $this->assertEquals(null, $fromJson['reasonCode']);
        $this->assertEquals(null, $fromJson['otherReasonText']);
        $this->assertEquals(null, $fromJson['contingencyCode']);
    }

    public function testIsJsonUnserializableWithFullData()
    {
        $jsonArray = [
            'siteId'            => '1',
            'performedAtYear'   => '2015',
            'performedAtMonth'  => '04',
            'performedAtDay'    => '22',
            'performedAtHour'   => '8',
            'performedAtMinute' => '20',
            'performedAtAmPm'   => 'pm',
            'reasonCode'        => 'OT',
            'otherReasonText'   => 'Because reasons',
            'contingencyCode'   => '12345A',
        ];

        $dto = new ContingencyTestDto();
        $this->assertInstanceOf(JsonUnserializable::class, $dto);
        $dto->jsonUnserialize($jsonArray);

        $jsonData = json_encode($dto);
        $this->assertInternalType('string', $jsonData);

        $this->assertEquals('1', $dto->getSiteId());
        $this->assertEquals('2015', $dto->getPerformedAtYear());
        $this->assertEquals('04', $dto->getPerformedAtMonth());
        $this->assertEquals('22', $dto->getPerformedAtDay());
        $this->assertEquals('8', $dto->getPerformedAtHour());
        $this->assertEquals('20', $dto->getPerformedAtMinute());
        $this->assertEquals('pm', $dto->getPerformedAtAmPm());
        $this->assertEquals('OT', $dto->getReasonCode());
        $this->assertEquals('Because reasons', $dto->getOtherReasonText());
        $this->assertEquals('12345A', $dto->getContingencyCode());
    }

    public function testIsJsonUnserializableWithEmptyData()
    {
        $jsonArray = [];

        $dto = new ContingencyTestDto();
        $this->assertInstanceOf(JsonUnserializable::class, $dto);
        $dto->jsonUnserialize($jsonArray);

        $jsonData = json_encode($dto);
        $this->assertInternalType('string', $jsonData);

        $this->assertEquals(null, $dto->getSiteId());
        $this->assertEquals(null, $dto->getPerformedAtYear());
        $this->assertEquals(null, $dto->getPerformedAtMonth());
        $this->assertEquals(null, $dto->getPerformedAtDay());
        $this->assertEquals(null, $dto->getPerformedAtHour());
        $this->assertEquals(null, $dto->getPerformedAtMinute());
        $this->assertEquals(null, $dto->getPerformedAtAmPm());
        $this->assertEquals(null, $dto->getReasonCode());
        $this->assertEquals(null, $dto->getOtherReasonText());
        $this->assertEquals(null, $dto->getContingencyCode());
    }
}
