package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.MotTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.testng.annotations.Test;

import java.util.Arrays;

import static com.dvsa.mot.selenium.datasource.Assertion.ASSERTION_TEST_INCOMPLETE;
import static com.dvsa.mot.selenium.datasource.Login.LOGIN_AREA_OFFICE1;
import static com.dvsa.mot.selenium.datasource.Login.LOGIN_ENFTESTER;
import static com.dvsa.mot.selenium.datasource.Vehicle.VEHICLE_CLASS4_CLIO_2004;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class AbilityToAbortMOTTest extends BaseTest {

    @Test(groups = {"slice_A", "VM-2948", "Sprint 22", "MOT Testing", "VM-4408"},
            description = "In order to prevent fraudulent test being carried out as a VE I require the ability to abort a test.")
    public void testVECanAbortTest() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe(RandomStringUtils.randomAlphabetic(5));
        Vehicle vehicle = createVehicle(VEHICLE_CLASS4_CLIO_2004);
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, LOGIN_AREA_OFFICE1,
                        RandomStringUtils.randomAlphabetic(5));
        Login login = createTester(Arrays.asList(site.getId()));

        //Navigate to UserDashboard page
        UserDashboardPage testerDashboard =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);

        //Get AE slots
        int slotsBeforeTest = testerDashboard.getAvailableSlotsInAe(1);
        //Navigate to MOT Test page
        MotTestPage motTestPage = testerDashboard.startMotTest().submitSearch(vehicle).startTest();
        //Get MOT Test id from URL
        String motTestId = motTestPage.getMotTestId();
        //Logout Tester, find and abort the test as VE
        MotTestSummaryPage motTestSummaryPage =
                motTestPage.clickLogout().loginAsEnforcementUser(LOGIN_ENFTESTER)
                        .goToVtsNumberEntryPage().enterVTSNumber(site.getNumber())
                        .clickSearchExpectingEnforcementVTSsearchHistoryPage()
                        .goToTestInProgressSummary(login, vehicle);

        assertThat("Assert test is incomplete validation text.", motTestSummaryPage.getTestStatus(),
                is(ASSERTION_TEST_INCOMPLETE.assertion));

        motTestSummaryPage.abortTest()
                .enterReasonForAborting(Text.TEXT_ENTER_A_REASON_FOR_ABORTING_BY_VE)
                .confirmReasonForAborting().clickLogout().loginAsUser(login);
        //Navigate to MOT test by Id
        driver.get(baseUrl() + "/mot-test/" + motTestId);
        motTestPage = new MotTestPage(driver).enterOdometerValuesAndSubmit(
                RandomDataGenerator.generateRandomNumber(5, vehicle.carReg.hashCode()));

        assertThat("Assert This test has been aborted by DVSA and cannot be continued text.",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        //Check slot balance has not changed
        testerDashboard = motTestPage.clickHome();
        int slotsAfterTest = testerDashboard.getAvailableSlotsInAe(1);

        assertThat("Slot should be returned on abort test", slotsAfterTest, is(slotsBeforeTest));

        //Log out
        motTestPage.clickLogout();
    }
}
