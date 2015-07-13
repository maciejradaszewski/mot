package uk.gov.dvsa.ui.user.journeys;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeClass;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.*;
import uk.gov.dvsa.domain.service.ServiceLocator;
import uk.gov.dvsa.helper.TestDataHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.*;

import java.io.IOException;

import static java.lang.String.valueOf;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static uk.gov.dvsa.domain.model.MotTestSearchCategory.REGISTRATION;
import static uk.gov.dvsa.domain.model.MotTestType.TARGETED_REINSPECTION;
import static uk.gov.dvsa.domain.model.ReinspectionScoreCategory.INSPECTION_NOTICE;
import static uk.gov.dvsa.domain.model.ReinspectionTestScore.DISREGARDED;


public class VeReInspectVehicleTest extends BaseTest {

    private User vehicleExaminer;
    private User tester;
    private Vehicle vehicle;
    private AeDetails aeDetails;
    private Site testSite;
    private MotTest motTest;
    private String motTestNr;
    private String testSiteNumber;
    private String testSiteId;

    @BeforeClass(alwaysRun = true)
    private void setup() throws IOException {
        vehicleExaminer = ServiceLocator.getUserService().createUserAsVE("ft-Enf-", false);
        aeDetails = ServiceLocator.getAeService().createAe("VeReinspectionTest", 8);
        testSite = ServiceLocator.getSiteService().createSite(aeDetails.getId(), "Test_Site");
        tester = ServiceLocator.getUserService().createUserAsTester(testSite.getId());
        vehicle = TestDataHelper.getNewVehicle();
        motTest = ServiceLocator.getMotTestService().createMotTest(tester, testSite.getId(), vehicle, TestOutcome.FAILED, 1234, DateTime.now());
        motTestNr = motTest.getMotTestNumber();
        testSiteNumber = valueOf(testSite.getSiteNumber());
        testSiteId = String.valueOf(testSite.getId());
    }

    private int odometer = 15900;

    @Test (groups = { "BVT" }, description = "VM-9858, Journey 5")
    public void veConductsVehicleReinspectionAndRecordsTheResultOfTheDvsaTest() throws IOException {

        //Given that I'm logged in as a VE, I search for mot test
        vehicleReinspectionWorkflow()
                .searchFotMotTest(vehicleExaminer, REGISTRATION.getValue(), vehicle.getRegistrationNumber(), motTestNr);

        //And I reinspect the vehicle
        TestCompletePage testCompletePage =
                vehicleReinspectionWorkflow().reinspectVehicle(TARGETED_REINSPECTION.getValue(), odometer, testSiteNumber);
        assertThat(testCompletePage.isReinspectionSuccessfull(), is(true));

        //And I compare tests results
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                vehicleReinspectionWorkflow().compareResults(DISREGARDED.getValue(), INSPECTION_NOTICE.getValue());

        //Then I can successfully save assessment details
        assertThat(assessmentDetailsConfirmationPage.isValidationMsgSuccess(), is(true));
    }

    @Test (groups = {"BVT"}, description = "VM-9858, Journey 5")
    public void veUsersCanFindAndViewEventHistory() throws IOException {

        //Given that I'm logged in as a VE, and I go to the Events History page
        EventsHistoryPage eventsHistoryPage =
                vehicleReinspectionWorkflow().gotoEventsHistoryPage(vehicleExaminer, testSiteId);

        //I can view events history of VTS
        assertThat(eventsHistoryPage.isListOfEventsDisplayed(), is(true));
    }

    @Test (groups = {"BVT"}, description = "VM-9858, Journey 5")
    public void veCanViewBasicVtsContactDetails() throws IOException {

        //Given that I'm logged in as a VE, and I go to Vehicle Testing Station page
        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, testSiteId);

        //I can view vts contact detail to plan a visit
        assertThat(vehicleTestingStationPage.isVtsAddressDisplayed(), is(true));
        assertThat(vehicleTestingStationPage.isVtsEmailDisplayed(), is(true));
        assertThat(vehicleTestingStationPage.isVtsPhoneNumberDisplayed(), is(true));
    }

    @Test (groups = {"BVT"}, description = "VM-9858, Journey 5")
    public void veCanViewCurrentActivityOnVtsPageAndAbortActiveTest() throws IOException {

        //Prepare mot test in progress
        Vehicle testVehicle = TestDataHelper.getNewVehicle();
        String testVehicleRegistration = testVehicle.getRegistrationNumber();
        vehicleReinspectionWorkflow().startMotTestAsATester(tester, testVehicle);
        pageNavigator().signOutAndGoToLoginPage();

        //Given that I'm logged in as a VE, and I go to Vehicle Testing Station page
        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, testSiteId);

        //I can view active MOT Test
        assertThat(vehicleTestingStationPage.isActiveMotTestDisplayed(testVehicleRegistration), is(true));

        //And I can abort active MOT Test
        TestShortSummaryPage testShortSummaryPage =
                vehicleReinspectionWorkflow().abortActiveTestOnVtsPage(testVehicleRegistration);

        assertThat(testShortSummaryPage.isTestAbortedSuccessfull(), is(true));
    }
}