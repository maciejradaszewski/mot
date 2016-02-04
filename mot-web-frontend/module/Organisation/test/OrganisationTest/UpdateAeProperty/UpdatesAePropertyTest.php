<?php

namespace OrganisationTest\UpdateVtsProperty;

use Core\Action\ActionResult;
use Core\Action\RedirectToRoute;
use Core\Routing\AeRouteList;
use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\OrganisationMapper;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonTest\TestUtils\AeUpdaterStub;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
use DvsaCommonTest\TestUtils\MethodSpy;
use DvsaCommonTest\TestUtils\VtsUpdaterStub;
use DvsaCommonTest\TestUtils\XMock;
use Organisation\UpdateAeProperty\Process\UpdateAeNameProcess;
use Organisation\UpdateAeProperty\Process\UpdateAeReviewProcess;
use Organisation\UpdateAeProperty\UpdateAePropertyAction;
use Organisation\UpdateAeProperty\UpdateAePropertyFormSession;
use Organisation\UpdateAeProperty\UpdateAePropertyProcessBuilder;
use Organisation\UpdateAeProperty\UpdateAePropertyViewModel;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\View\Helper\Url;

class UpdatesAePropertyTest extends \PHPUnit_Framework_TestCase
{
    private $aeId = 11;
    /**
     * @var OrganisationMapper
     */
    private $organisationMapper;

    /**
     * @var AuthorisationServiceMock
     */
    private $authorisationService;

    private $propertyName = "ae-property";

    private $requiredPermission = "PERMISSION";

    private $formElementName = 'ae-form-element-name';

    private $submitButtonText = "Submit changes";

    private $oldValueFromDb = "Old ae property";

    /** @var MethodSpy */
    private $updateAeSpy;

    /** @var VtsUpdaterStub */
    private $aeUpdater;

    private $successMessage = "You have changed the property";

    private $partial = "Partial file that displays form";

    private $pageTitle = "This is a proper page title";

    private $reviewPageTitle = "This is a proper review page title";

    private $reviewPageLede = "This is a proper review page lede";

    private $reviewSubmitButtonText = "Review button text";

    private $breadCrumb = "Breadcrumb";


    /** @var Url */
    private $urlHelper;

    /** @var  UpdateAePropertyFormSession */
    private $formSession;

    protected function setUp()
    {
        $aeDto = new OrganisationDto();
        $aeDto->setName($this->oldValueFromDb);

        $this->organisationMapper = XMock::of(OrganisationMapper::class);
        $this->organisationMapper->expects($this->any())
            ->method('getAuthorisedExaminer')
            ->willReturn($aeDto);

        $this->aeUpdater = XMock::of(AeUpdaterStub::class);
        $this->updateAeSpy = new MethodSpy($this->aeUpdater, 'update');

        $this->urlHelper = Xmock::of(Url::class);
        $this->formSession = new UpdateAePropertyFormSession();

        $this->authorisationService = new AuthorisationServiceMock();
    }

    public function testGetViewFormFirstTimeAction()
    {
        // GIVEN I'm viewing the form for editing

        $processBuilder = new UpdateAePropertyProcessBuilder($this->organisationMapper);
        $processBuilder->add($this->createProcess(false));
        $action = new UpdateAePropertyAction(
            new UpdateAePropertyFormSession(),
            $this->organisationMapper,
            $this->authorisationService,
            $processBuilder,
            $this->urlHelper
        );

        // AND I do it for the first time, so there is no form in the user session
        $formUuid = null;

        // AND I have permission to do it
        $this->authorisationService->grantedAtOrganisation($this->requiredPermission, $this->aeId);

        // WHEN I view it
        $result = $action->execute(false, $this->propertyName, $this->aeId, null, []);

        // THEN I'm not redirected anywhere
        $this->assertInstanceOf(ActionResult::class, $result);

        // AND I receive a proper view model
        /** @var UpdateVtsPropertyViewModel $vm */
        $vm = $result->getViewModel();
        $this->assertInstanceOf(UpdateAePropertyViewModel::class, $vm);

        //AND the form is pre-populated with data from database
        $this->assertEquals($this->oldValueFromDb, $vm->getForm()->get($this->formElementName)->getValue());

        // AND the form button has a proper name
        $this->assertEquals($this->submitButtonText, $vm->getSubmitButtonText());

        // AND title and subtitles are correctly set
        $this->assertEquals($this->pageTitle, $result->layout()->getPageTitle());
        $this->assertEquals("Authorised Examiner", $result->layout()->getPageSubTitle());
        //AND todo template is gov layout
    }

    public function testNotAuthorisedGet()
    {
        // GIVEN I'm viewing the form for editing
        $processBuilder = new UpdateAePropertyProcessBuilder($this->organisationMapper);
        $processBuilder->add(new UpdateAeNameProcess($this->organisationMapper));
        $action = new UpdateAePropertyAction(
            $this->formSession,
            $this->organisationMapper,
            $this->authorisationService,
            $processBuilder,
            $this->urlHelper
        );

        // todo AND I do not have permission to do it

        try {
            // WHEN I view it
            $action->execute(false, UpdateAePropertyAction::AE_NAME_PROPERTY, $this->aeId, null, []);

            // THEN an exception is thrown
            $this->fail("The exception was not thrown");
        } catch (UnauthorisedException $exception) {
        }
    }

    public function testNotAuthorisedPost()
    {
        // todo you do not have a permission, you can't enter the page
    }

    public function testGetRedirectsWhenTheFormUuidDoesNotExistInSession()
    {
        // todo this should redirect to itself with GET method, but without formUuid in query
    }

    public function testCannotGetToReviewStepIfTheVtsPropertyDoesNotRequireReview()
    {
        // todo if a property does not require review step, then you should not be able to reach review step
    }

    public function testPostWithoutRequiredReviewStepAndWithValidData()
    {
        // GIVEN I have permission to update a property of a VTS
        $this->authorisationService->grantedAtOrganisation($this->requiredPermission, $this->aeId);
        $processBuilder = new UpdateAePropertyProcessBuilder($this->organisationMapper);

        // AND I want to update a property that does not require review step
        $processBuilder->add($this->createProcess(false));

        $siteMapper = XMock::of(OrganisationMapper::class);
        $action = new UpdateAePropertyAction(
            $this->formSession,
            $siteMapper,
            $this->authorisationService,
            $processBuilder,
            $this->urlHelper
        );

        // AND I filled the form out properly
        $formData = [$this->formElementName => "Small Garage"];

        // WHEN I post the form
        /** @var RedirectToRoute $result */
        $result = $action->execute(true, $this->propertyName, $this->aeId, null, $formData);

        // THEN the form is submitted to API
        $this->assertEquals(1, $this->updateAeSpy->invocationCount());
        $this->assertEquals($this->aeId, $this->updateAeSpy->paramsForLastInvocation()[0]);
        $this->assertEquals([$this->formElementName => "Small Garage"], $this->updateAeSpy->paramsForLastInvocation()[1]);

        // AND I get redirected to VTS overview page of the correct VTS
        $this->assertInstanceOf(RedirectToRoute::class, $result);
        $this->assertEquals(AeRouteList::AE, $result->getRouteName());
        $this->assertEquals($this->aeId, $result->getRouteParams()['id']);

        // AND the success message is set
        $this->assertEquals(1, count($result->getSuccessMessages()));
        $this->assertEquals($this->successMessage, $result->getSuccessMessages()[0]);
    }

    public function testPostWithoutRequiredReviewStepWithInvalidData()
    {
        // todo
    }

    public function testReviewStepWithoutFormUuid()
    {
        // todo
    }

    public function testPostWithRequiredReviewStepWithValidData()
    {
        // GIVEN I have permission to update a property of a VTS
        $this->authorisationService->grantedAtOrganisation($this->requiredPermission, $this->aeId);
        $processBuilder = new UpdateAePropertyProcessBuilder($this->organisationMapper);

        // AND I want to update a property that does not require review step
        $processBuilder->add($this->createProcess(true));

        $organisationMapper = XMock::of(OrganisationMapper::class);
        $action = new UpdateAePropertyAction(
            $this->formSession,
            $organisationMapper,
            $this->authorisationService,
            $processBuilder,
            $this->urlHelper
        );

        // AND I filled the form out properly
        $formData = [$this->formElementName => "Small Garage"];

        // WHEN I post the form
        /** @var RedirectToRoute $result */
        $result = $action->execute(true, $this->propertyName, $this->aeId, null, $formData);

        // THEN the form is not submitted to API
        $this->assertEquals(0, $this->updateAeSpy->invocationCount());

        //print_r($result);
        // AND I get redirected to review page
        $this->assertInstanceOf(RedirectToRoute::class, $result);
        $this->assertEquals(AeRouteList::AE_EDIT_PROPERTY_REVIEW, $result->getRouteName());

        // of a correct VTS
        $this->assertEquals($this->aeId, $result->getRouteParams()['id']);

        // AND a formUuid set
        $formUuid = $result->getRouteParams()['formUuid'];
        $this->assertNotEmpty($formUuid);

        // AND form is stored in session
        $this->assertEquals($formData, $this->formSession->get($this->aeId, $this->propertyName, $formUuid));
    }

    public function testPostWithRequiredReviewStepWithoutValidData()
    {
        // todo
    }

    public function testGetWhenGoingBackFromReviewStep()
    {
        // todo
    }

    public function storingFormTwiceCreatesDifferentUuids()
    {
        $uuid1 = $this->formSession->store(1, 'A', []);
        $uuid2 = $this->formSession->store(1, 'A', []);

        $this->assertNotEquals($uuid1, $uuid2);
    }

    private function createProcess($requiresReview)
    {
        $form = new Form();
        $form->add((new Text())->setName($this->formElementName));
        $form->setInputFilter((new InputFilter())->add([
            'name'     => $this->formElementName,
            'required' => true,
        ]));

        return new UpdateAeReviewProcess(
            $this->propertyName,
            $this->requiredPermission,
            $requiresReview,
            $this->partial,
            $form,
            $this->submitButtonText,
            function () {
                return [$this->formElementName => $this->oldValueFromDb];
            },
            function ($vtsId, $formData) {
                $this->aeUpdater->update($vtsId, $formData);
            },
            $this->successMessage,
            $this->pageTitle,
            function ()
            {
                return new GdsTable();
            },
            $this->reviewPageTitle,
            $this->reviewPageLede,
            $this->reviewSubmitButtonText,
            $this->breadCrumb
        );
    }
}
