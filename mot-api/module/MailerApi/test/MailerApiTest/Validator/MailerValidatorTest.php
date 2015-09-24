<?php
namespace MailerApiTest\Validator;

use DvsaCommon\Dto\Mailer\MailerDto;
use DvsaMotApi\Service\UserService;
use MailerApi\Validator\MailerValidator;
use MailerApiTest\Mixin\ServiceManager;
use PHPUnit_Framework_TestCase;

class MailerValidatorTest extends PHPUnit_Framework_TestCase
{
    use ServiceManager;

    protected $validator;
    protected $mockUserService;

    public function setUp()
    {
        $this->prepServiceManager();

        $this->mockUserService = $this->setMockServiceClass(UserService::class, ['findPerson']);

        $this->validator = new MailerValidator($this->mockUserService);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testFailsIfUseridNotInDtoData()
    {
        $dto = new MailerDto();
        $dto->setData(['userid-not-here' => 0]);
        $this->validator->validate($dto, '');
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testFailsWithUnrecognisedReminderType()
    {
        $dto = new MailerDto();
        $dto->setData(['userid' => 5]);

        $this->validator->validate($dto, 999);
    }


    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testFailsWithMissingUseridInDtoForUsername()
    {
        $dto = new MailerDto();
        $dto->setData(['userid-not-here' => 5]);

        $this->validator->validate($dto, MailerValidator::TYPE_REMIND_USERNAME);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testFailsWithMissingUseridInDtoForPassword()
    {
        $dto = new MailerDto();
        $dto->setData(['userid-not-here' => 5]);

        $this->validator->validate($dto, MailerValidator::TYPE_REMIND_PASSWORD);
    }

    public function testOkWithMissingUseridInDtoForPassword()
    {
        $dto = new MailerDto();
        $dto->setData(['userid' => 5]);

        $this->validator->validate($dto, MailerValidator::TYPE_REMIND_PASSWORD);
    }

    public function testValidatesOkWithGoodUserIdPresentAndCorrect()
    {
        $dto = new MailerDto();
        $dto->setData(['userid' => 5]);

        $this->ensureUserIdGood(5);

        $this->assertTrue(
            $this->validator->validate(
                $dto, MailerValidator::TYPE_REMIND_USERNAME
            )
        );
    }

    public function testGivenCorrectValuesArePassed_whenValidatingCustomerCertificate_shouldReturnTrue()
    {
        $dto = new MailerDto();
        $dto->setData([
            "email" => "dummy@email.com",
            "firstName" => "some name",
            "familyName" => "familyName",
            "attachment" => "dummy attachment",
        ]);

        $this->assertTrue($this->validator->validate($dto, MailerValidator::TYPE_CUSTOMER_CERTIFICATE));
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testGivenEmailIsTooLong_whenValidatingCustomerCertificate_shouldThrowBadRequestException()
    {
        $dto = new MailerDto();
        $dto->setData([
            "email" => "dumdummydummydummydummydummydummydummydummydummydummydummydummydummydummydummydumm@email.com",
            "firstName" => "some name",
            "familyName" => "familyName",
            "attachment" => "dummy attachment",
        ]);

        $this->validator->validate($dto, MailerValidator::TYPE_CUSTOMER_CERTIFICATE);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     */
    public function testGivenNullValuesArePassed_whenValidatingCustomerCertificate_shouldThrowBadRequestException()
    {
        $dto = new MailerDto();
        $dto->setData([]);

        $this->validator->validate($dto, MailerValidator::TYPE_CUSTOMER_CERTIFICATE);
    }

    protected function ensureUserIdGood($id, $with = [])
    {
        // Must pass validating the user-id first
        $this->mockUserService->expects($this->once())
            ->method('findPerson')
            ->with($id)
            ->willReturn($with);
        return $this;
    }
}
