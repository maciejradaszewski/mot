package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.enums.ContingencyReasons;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.ContingencyTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchRetestPage;
import org.joda.time.DateTime;
import org.testng.annotations.Test;

import java.util.ArrayList;

import static org.testng.Assert.assertTrue;

public class ContingencyTests extends BaseTest {

    private int day = new DateTime().getDayOfMonth();
    private int month = new DateTime().getMonthOfYear();
    private int year = new DateTime().getYear();
    Vehicle myVehicle;

    @Test(groups = {"VM-4825", "Sprint05", "V", "Test 01", "slice_A",
            "A"}, description = "End to end test scenario for contingency test normal test failure user journey")
    public void normalMOTFromContingencyTestFail() {

        ArrayList myManyVTS = new ArrayList();
        myManyVTS.add(Site.JOHNS_GARAGE.getId());
        myManyVTS.add(Site.POPULAR_GARAGES.getId());
        Login myLogin1 = createTester(myManyVTS);
        myVehicle = createVehicle(Vehicle.VEHICLE_CLASS4_ASTRA_2010);

        ContingencyTestPage.navigateHereFromLoginPage(driver, myLogin1)
                .testByYouMultiVTS(String.valueOf(Site.POPULAR_GARAGES.getId())).
                fillContingencyTestEntryForm(true, Text.TEXT_CONTINGENCY_TEXT_CODE, day, month,
                        year, ContingencyReasons.PAYMENT_ISSUE);
        VehicleSearchPage vehicleSearchPage = new VehicleSearchPage(driver);
        vehicleSearchPage.submitSearch(myVehicle).confirmStartTest()
                .enterOdometerValues(Text.TEXT_VALID_ODOMETER_MILES).
                submitOdometer().addBrakeTest().
                enterBrakeConfigurationPageFields(
                        BrakeTestConfiguration4.brakeTestConfigClass4_Roller()).submit().
                enterBrakeResultsPageFields(BrakeTestResults4.allFail()).submit().clickDoneButton()
                .createCertificate().clickFinishPrint(Text.TEXT_PASSCODE);
        TestSummary testSummary = new TestSummary(driver);
        assertTrue(testSummary.printDocButtonExist(),
                "Check that the print button is displayed on page");
    }

    @Test(groups = {"VM-4825", "Sprint05", "V", "Test 02",
            "slice_A"}, description = "End to end test scenario for contingency test retest user journey", dependsOnGroups = "A")
    public void retestFromContingencyTest() {

        ArrayList myManyVTS = new ArrayList();
        myManyVTS.add(Site.ANGEL_GARAGE.getId());
        myManyVTS.add(Site.POPULAR_GARAGES.getId());
        Login myLogin2 = createTester(myManyVTS);

        ContingencyTestPage.navigateHereFromLoginPage(driver, myLogin2)
                .testByYouMultiVTS(String.valueOf(Site.POPULAR_GARAGES.getId()))
                .fillContingencyTestEntryForm(false, Text.TEXT_CONTINGENCY_TEXT_CODE, day, month,
                        year, ContingencyReasons.OTHER);
        VehicleSearchRetestPage vehicleSearchRetestPage = new VehicleSearchRetestPage(driver);
        vehicleSearchRetestPage.submitSearch(myVehicle)
                .confirmStartReTest(PageTitles.MOT_RETEST_RESULT_ENTRY_PAGE.getPageTitle()).
                enterOdometerValues(Text.TEXT_VALID_ODOMETER_MILES)
                .submitOdometer(PageTitles.MOT_RETEST_RESULT_ENTRY_PAGE.getPageTitle()).
                addBrakeTest().enterBrakeConfigurationPageFields(
                BrakeTestConfiguration4.brakeTestConfigClass4_Roller()).submit().
                enterBrakeResultsPageFields(BrakeTestResults4.allPass()).submit().
                clickDoneButton(PageTitles.MOT_RETEST_RESULT_ENTRY_PAGE.getPageTitle()).
                createCertificate().clickFinishPrint(Text.TEXT_PASSCODE,
                PageTitles.MOT_RETEST_COMPLETE.getPageTitle());
        TestSummary testSummary = new TestSummary(driver);
        assertTrue(testSummary.printDocButtonExist(),
                "Check that the print button is displayed on page");
    }

}
