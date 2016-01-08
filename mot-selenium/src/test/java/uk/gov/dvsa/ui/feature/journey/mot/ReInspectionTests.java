package uk.gov.dvsa.ui.feature.journey.mot;

import org.joda.time.DateTime;
import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.Site;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.model.mot.MotTest;
import uk.gov.dvsa.domain.model.mot.TestOutcome;
import uk.gov.dvsa.domain.model.vehicle.Vehicle;
import uk.gov.dvsa.domain.shared.role.DvsaRoles;
import uk.gov.dvsa.domain.shared.role.RoleManager;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.AssessmentDetailsConfirmationPage;
import uk.gov.dvsa.ui.pages.EventsHistoryPage;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.mot.TestCompletePage;
import uk.gov.dvsa.ui.pages.mot.TestShortSummaryPage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

import static java.lang.String.valueOf;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;
import static uk.gov.dvsa.domain.model.mot.MotTestSearchCategory.REGISTRATION;
import static uk.gov.dvsa.domain.model.mot.MotTestType.TARGETED_REINSPECTION;
import static uk.gov.dvsa.domain.model.mot.ReinspectionScoreCategory.INSPECTION_NOTICE;
import static uk.gov.dvsa.domain.model.mot.ReinspectionTestScore.DISREGARDED;


public class ReInspectionTests extends BaseTest {

    private User vehicleExaminer;
    private User tester;
    private Vehicle vehicle;
    private String motTestNr;
    private String testSiteNumber;
    private String testSiteId;

    @BeforeMethod(alwaysRun = true)
    private void setup() throws IOException {
        vehicleExaminer = userData.createVehicleExaminer("ft-Enf-", false);
        AeDetails aeDetails = aeData.createNewAe("VeReinspectionTest", 8);
        Site testSite = siteData.createNewSite(aeDetails.getId(), "Test_Site");
        tester = userData.createTester(testSite.getId());
        vehicle = vehicleData.getNewVehicle(tester);
        MotTest motTest = motApi.createTest(tester, testSite.getId(), vehicle, TestOutcome.FAILED, 1234, DateTime.now());
        motTestNr = motTest.getMotTestNumber();
        testSiteNumber = valueOf(testSite.getSiteNumber());
        testSiteId = String.valueOf(testSite.getId());
    }

    @Test (groups = { "BVT", "Regression" }, description = "VM-9858, Journey 5")
    public void veConductsVehicleReInspectionAndRecordsTheResultOfTheDvsaTest() throws IOException {

        //Given that I'm logged in as a VE, I search for mot test
        vehicleReinspectionWorkflow()
                .searchFotMotTest(vehicleExaminer, REGISTRATION.getValue(), vehicle.getRegistrationNumber(), motTestNr);

        //And I reinspect the vehicle
        int odometer = 15900;
        TestCompletePage testCompletePage =
                vehicleReinspectionWorkflow().reinspectVehicle(TARGETED_REINSPECTION.getValue(), odometer, testSiteNumber);
        assertThat(testCompletePage.isReinspectionSuccessfull(), is(true));

        //And I compare tests results
        AssessmentDetailsConfirmationPage assessmentDetailsConfirmationPage =
                vehicleReinspectionWorkflow().compareResults(DISREGARDED.getValue(), INSPECTION_NOTICE.getValue());

        //Then I can successfully save assessment details
        assertThat(assessmentDetailsConfirmationPage.isValidationMsgSuccess(), is(true));
    }

    @Test (groups = {"BVT", "Regression"}, description = "VM-9858, Journey 5")
    public void veUsersCanFindAndViewEventHistory() throws IOException {

        //Given that I'm logged in as a VE, and I go to the Events History page
        EventsHistoryPage eventsHistoryPage =
                vehicleReinspectionWorkflow().gotoEventsHistoryPage(vehicleExaminer, testSiteId);

        //I can view events history of VTS
        assertThat(eventsHistoryPage.isListOfEventsDisplayed(), is(true));
    }

    @Test (groups = {"BVT", "Regression"}, description = "VM-9858, Journey 5")
    public void veCanViewBasicVtsContactDetails() throws IOException {

        //Given that I'm logged in as a VE, and I go to Vehicle Testing Station page
        VehicleTestingStationPage vehicleTestingStationPage =
                vehicleReinspectionWorkflow().gotoVehicleTestingStationPage(vehicleExaminer, testSiteId);

        //I can view vts contact detail to plan a visit
        assertThat(vehicleTestingStationPage.isAddressDisplayed(), is(true));
        assertThat(vehicleTestingStationPage.isEmailDisplayed(), is(true));
        assertThat(vehicleTestingStationPage.isPhoneNumberDisplayed(), is(true));
    }

    @Test (groups = {"BVT", "Regression"}, description = "VM-9858, Journey 5")
    public void veCanViewCurrentActivityOnVtsPageAndAbortActiveTest() throws IOException {

        //Prepare mot test in progress
        Vehicle testVehicle = vehicleData.getNewVehicle(tester);
        String testVehicleRegistration = testVehicle.getRegistrationNumber();
        vehicleReinspectionWorkflow().startMotTestAsATester(tester, testVehicle);
        pageNavigator.signOutAndGoToLoginPage();

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

    @Test(groups = {"BVT", "Regression"}, description = "BL-101")
    public void userWithVeAndA01RolesCanResumeReInspection() throws IOException {

        //Given I am a User with a AO1 and VE Role
        User ao1PlusVe = userData.createAreaOfficeOne("AO1PlusVE");
        RoleManager.addRole(ao1PlusVe.getId(), DvsaRoles.VEHICLE_EXAMINER);

        //When I start a reInspection test
        MotTest motTest = motApi.createTest(tester, Integer.valueOf(testSiteId), vehicle,
                TestOutcome.PASSED, 123456, DateTime.now());

        vehicleReinspectionWorkflow()
                .searchFotMotTest(ao1PlusVe, REGISTRATION.getValue(),
                        vehicle.getRegistrationNumber(), motTest.getMotTestNumber())
                .clickStartReinspectionButton();

        //And Return to the homepage
        HomePage homePage = pageNavigator.gotoHomePage(ao1PlusVe);

        //Then I should see the [Resume ReInspection] button
        assertThat(homePage.isResumeMotTestDisplayed(), is(true));
    }
}