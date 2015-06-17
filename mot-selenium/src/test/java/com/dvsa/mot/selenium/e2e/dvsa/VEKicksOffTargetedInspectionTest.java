package com.dvsa.mot.selenium.e2e.dvsa;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementReInspectionTestCompletePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementVTSSearchPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.testng.annotations.Test;

import java.util.Arrays;

import static com.dvsa.mot.selenium.datasource.Text.TEXT_ENF_SITE_SEARCH;
import static com.dvsa.mot.selenium.datasource.Vehicle.VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT;
import static com.dvsa.mot.selenium.datasource.enums.PageTitles.MOT_REINSPECTION_TEST_ENTRY_PAGE;
import static com.dvsa.mot.selenium.datasource.enums.ReinspectionTypes.Targeted_Reinspection;
import static com.dvsa.mot.selenium.framework.api.MotTestApi.TestOutcome.PASSED;
import static org.testng.Assert.assertTrue;

public class VEKicksOffTargetedInspectionTest extends BaseTest {

    private Login ve;
    private Vehicle vehicle;

    @Test(groups = {"E2E", "slice_A"}) public void testThatVECanKickOffTargetedInspection() {
        // create VE and Vehicle
        ve = createVE();
        vehicle = createVehicle(VEHICLE_CLASS4_BMW_ALPINA_REISSUE_CERT);
        int aeId = createAE(RandomDataGenerator.generateRandomString(5, 23));
        Site site = new VtsCreationApi()
                .createVtsSite(aeId, TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        RandomDataGenerator.generateRandomString(5, 25));



        // create MOT test and store mot test number in variable
        String motTestNumber =
                createMotTest(createTester(Arrays.asList(site.getId())), site,
                        vehicle, 12345, PASSED);

        /**
         Step 1 login as VE and navigate to MOT test search page
         Step 2 search for MOT test using Site (recent test) with #VTS1234
         Step 3 Select MOT test created with motTestNumber on the MOT Test History page
         Step 4 Start a targeted inspection against the MOT test created with motTestNumber
         **/
        EnforcementVTSSearchPage.navigateHereFromLoginPage(driver, ve)
                .selectDropdown(TEXT_ENF_SITE_SEARCH).searchForVehicle(site.getNumber())
                .viewMOTTest(motTestNumber).
                selectTestType(Targeted_Reinspection.getInspectionType());

        MotTestPage motTestPage =
                new MotTestPage(driver, MOT_REINSPECTION_TEST_ENTRY_PAGE.getPageTitle());
        motTestPage.
                enterOdometerValuesAndSubmit("50000",
                        MOT_REINSPECTION_TEST_ENTRY_PAGE.getPageTitle()).
                addBrakeTest().enterBrakeConfigurationPageFields(
                    BrakeTestConfiguration4.brakeTestConfigClass4_Roller()
                ).
                submit().enterBrakeResultsPageFields(BrakeTestResults4.allPass()).submit()
                .clickDoneButton(MOT_REINSPECTION_TEST_ENTRY_PAGE.getPageTitle()).
                createCertificate().clickFinishPrintReinspection();

        // assert that the print document and compare test results buttons are present on the MOT reinspection complete page
        EnforcementReInspectionTestCompletePage enforcementReInspectionTestCompletePage =
                new EnforcementReInspectionTestCompletePage(driver);
        assertTrue(enforcementReInspectionTestCompletePage.isReprintCertificateButtonPresent(),
                "Check that the print document button is displayed");
        assertTrue(enforcementReInspectionTestCompletePage.verifyCompareTestsButton(),
                "Check that the compare test results button is displayed");

    }
}
