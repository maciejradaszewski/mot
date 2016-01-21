<?php

namespace SiteTest\UpdateVtsProperty;

use Core\Action\ActionResult;
use Core\Action\RedirectToRoute;
use Core\Routing\VtsRouteList;
use Core\ViewModel\Gds\Table\GdsTable;
use DvsaClient\Mapper\SiteMapper;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommonTest\TestUtils\Auth\AuthorisationServiceMock;
use DvsaCommonTest\TestUtils\MethodSpy;
use DvsaCommonTest\TestUtils\VtsUpdaterStub;
use DvsaCommonTest\TestUtils\XMock;
use Site\UpdateVtsProperty\Process\UpdateVtsNameProcess;
use Site\UpdateVtsProperty\Process\UpdateVtsReviewProcess;
use Site\UpdateVtsProperty\UpdateVtsPropertyAction;
use Site\UpdateVtsProperty\UpdateVtsPropertyFormSession;
use Site\UpdateVtsProperty\UpdateVtsPropertyProcessBuilder;
use Site\UpdateVtsProperty\UpdateVtsPropertyViewModel;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\View\Helper\Url;

class UpdateVtsPropertyTest extends \PHPUnit_Framework_TestCase
{
    private $vtsId = 11;
    /**
     * @var SiteMapper
     */
    private $siteMapper;

    /**
     * @var AuthorisationServiceMock
     */
    private $authorisationService;

    private $propertyName = "vts-property";

    private $requiredPermission = "PERMISSION";

    private $formElementName = 'vts-form-element-name';

    private $submitButtonText = "Submit changes";

    private $oldValueFromDb = "Old vts property";

    /** @var MethodSpy */
    private $updateVtsSpy;

    /** @var VtsUpdaterStub */
    private $vtsUpdater;

    private $successMessage = "You have changed the property";

    private $partial = "Partial file that displays form";

    private $pageTitle = "This is a proper page title";

    private $reviewPageTitle = "This is a proper review page title";

    private $reviewPageLede = "This is a proper review page lede";

    private $reviewSubmitButtonText = "Review button text";

    private $breadCrumb = "Breadcrumb";


    /** @var Url */
    private $urlHelper;

    /** @var  UpdateVtsPropertyFormSession */
    private $formSession;

    protected function setUp()
    {
        $vtsDto = new VehicleTestingStationDto();
        $vtsDto->setName($this->oldValueFromDb);

        $this->siteMapper = XMock::of(SiteMapper::class);
        $this->siteMapper->expects($this->any())
            ->method('getById')
            ->willReturn($vtsDto);

        $this->vtsUpdater = XMock::of(VtsUpdaterStub::class);
        $this->updateVtsSpy = new MethodSpy($this->vtsUpdater, 'update');

        $this->urlHelper = Xmock::of(Url::class);
        $this->formSession = new UpdateVtsPropertyFormSession();

        $this->authorisationService = new AuthorisationServiceMock();
    }

    public function testGetViewFormFirstTimeAction()
    {
        // GIVEN I'm viewing the form for editing

        $processBuilder = new UpdateVtsPropertyProcessBuilder($this->siteMapper);
        $processBuilder->add($this->createProcess(false));
        $action = new UpdateVtsPropertyAction(
            new UpdateVtsPropertyFormSession(),
            $this->siteMapper,
            $this->authorisationService,
            $processBuilder,
            $this->urlHelper
        );

        // AND I do it for the first time, so there is no form in the user session
        $formUuid = null;

        // AND I have permission to do it
        $this->authorisationService->grantedAtSite($this->requiredPermission, $this->vtsId);

        // WHEN I view it
        $result = $action->execute(false, $this->propertyName, $this->vtsId, null, []);

        // THEN I'm not redirected anywhere
        $this->assertInstanceOf(ActionResult::class, $result);

        // AND I receive a proper view model
        /** @var UpdateVtsPropertyViewModel $vm */
        $vm = $result->getViewModel();
        $this->assertInstanceOf(UpdateVtsPropertyViewModel::class, $vm);

        //AND the form is pre-populated with data from database
        $this->assertEquals($this->oldValueFromDb, $vm->getForm()->get($this->formElementName)->getValue());

        // AND the form button has a proper name
        $this->assertEquals($this->submitButtonText, $vm->getSubmitButtonText());

        // AND title and subtitles are correctly set
        $this->assertEquals($this->pageTitle, $result->layout()->getPageTitle());
        $this->assertEquals("Vehicle Testing Station", $result->layout()->getPageSubTitle());
        //AND todo template is gov layout
    }

    public function testNotAuthorisedGet()
    {
        // GIVEN I'm viewing the form for editing
        $processBuilder = new UpdateVtsPropertyProcessBuilder($this->siteMapper);
        $processBuilder->add(new UpdateVtsNameProcess($this->siteMapper));
        $action = new UpdateVtsPropertyAction(
            $this->formSession,
            $this->siteMapper,
            $this->authorisationService,
            $processBuilder,
            $this->urlHelper
        );

        // todo AND I do not have permission to do it

        try {
            // WHEN I view it
            $action->execute(false, UpdateVtsPropertyAction::VTS_NAME_PROPERTY, $this->vtsId, null, []);

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
        $this->authorisationService->grantedAtSite($this->requiredPermission, $this->vtsId);
        $processBuilder = new UpdateVtsPropertyProcessBuilder($this->siteMapper);

        // AND I want to update a property that does not require review step
        $processBuilder->add($this->createProcess(false));

        $siteMapper = XMock::of(SiteMapper::class);
        $action = new UpdateVtsPropertyAction(
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
        $result = $action->execute(true, $this->propertyName, $this->vtsId, null, $formData);

        // THEN the form is submitted to API
        $this->assertEquals(1, $this->updateVtsSpy->invocationCount());
        $this->assertEquals($this->vtsId, $this->updateVtsSpy->paramsForLastInvocation()[0]);
        $this->assertEquals([$this->formElementName => "Small Garage"], $this->updateVtsSpy->paramsForLastInvocation()[1]);

        // AND I get redirected to VTS overview page of the correct VTS
        $this->assertInstanceOf(RedirectToRoute::class, $result);
        $this->assertEquals(VtsRouteList::VTS, $result->getRouteName());
        $this->assertEquals($this->vtsId, $result->getRouteParams()['id']);

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
        $this->authorisationService->grantedAtSite($this->requiredPermission, $this->vtsId);
        $processBuilder = new UpdateVtsPropertyProcessBuilder($this->siteMapper);

        // AND I want to update a property that does not require review step
        $processBuilder->add($this->createProcess(true));

        $siteMapper = XMock::of(SiteMapper::class);
        $action = new UpdateVtsPropertyAction(
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
        $result = $action->execute(true, $this->propertyName, $this->vtsId, null, $formData);

        // THEN the form is not submitted to API
        $this->assertEquals(0, $this->updateVtsSpy->invocationCount());

        //print_r($result);
        // AND I get redirected to review page
        $this->assertInstanceOf(RedirectToRoute::class, $result);
        $this->assertEquals(VtsRouteList::VTS_EDIT_PROPERTY_REVIEW, $result->getRouteName());

        // of a correct VTS
        $this->assertEquals($this->vtsId, $result->getRouteParams()['id']);

        // AND a formUuid set
        $formUuid = $result->getRouteParams()['formUuid'];
        $this->assertNotEmpty($formUuid);

        // AND form is stored in session
        $this->assertEquals($formData, $this->formSession->get($this->vtsId, $this->propertyName, $formUuid));
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

        return new UpdateVtsReviewProcess(
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
                $this->vtsUpdater->update($vtsId, $formData);
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
