package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.MotTestApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.MotTestSummaryPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.joda.time.DateTime;
import org.testng.annotations.Test;

import static com.dvsa.mot.selenium.framework.api.MotTestApi.MotTestData;
import static org.testng.Assert.assertEquals;
import static org.testng.Assert.assertTrue;

@Test(groups = {"Regression"}) public class MotTestSummaryTest extends BaseTest {

    private static final int VTS_ID = 1;
    private static final String VTS_NUMBER = "V1234";

    public void testViewSummary() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        String motTestNumber = createPassedMotTest(vehicle);
        MotTestSummaryPage page = setUpMotTestPageSummary(motTestNumber, vehicle);

        assertEquals(page.getMotTestNumber(), motTestNumber);
    }

    public void testVeCanReprintCertificate() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001);
        MotTestSummaryPage page = setUpMotTestPageSummary(vehicle);
        assertTrue(page.printCertificateButtonExists());
    }

    /**
     * Log in as enforcement tester and visit the mot test summary page for a
     * new MOT test.
     *
     * @return
     */
    private MotTestSummaryPage setUpMotTestPageSummary(String motNumber, Vehicle vehicle) {

        return new LoginPage(driver).loginAsEnforcementUser(Login.LOGIN_ENFTESTER)
                .goToVtsNumberEntryPage().enterVTSNumber(VTS_NUMBER)
                .clickSearchExpectingEnforcementVTSsearchHistoryPage()
                .gotToTestSummary(motNumber, vehicle);
    }

    private MotTestSummaryPage setUpMotTestPageSummary(Vehicle vehicle) {
        return setUpMotTestPageSummary(createPassedMotTest(vehicle), vehicle);
    }

    private String createPassedMotTest(Vehicle vehicle) {

        MotTestApi motTestApi = new MotTestApi();

        MotTestData motTestData =
                new MotTestData(MotTestApi.TestOutcome.PASSED, 1000, DateTime.now());

        return motTestApi.createTest(login, vehicle, VTS_ID, motTestData, null);
    }
}
