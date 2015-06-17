package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.PRSrejection;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MOTTestResultPageTestCompletePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class MotTestCompleteTest extends BaseTest {

    @Test(groups = {"Sprint 22", "MOT Testing", "VM-2940", "VM-3336", "slice_A"},
            description = "When a PRS is added to a test, it should be possible to print out a fail and then a pass document at the same time for this test.")
    public void testPrintFailAndPassCertificateWithPRSPresent() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        TestSummary testSummary = TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, "123460",
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allPass(), null,
                        new PRSrejection[] {PRSrejection.HORN_CONTROL_INSECURE}, null, null);

        String motTestNumber = testSummary.getMotTestNumber();

        assertThat("Check test status", testSummary.getTestStatus(), is("Pass"));
        assertThat(
                "Mot Test number must have 12 digits and not begin with 0 (\" + motTestNumber + \")",
                motTestNumber.matches("^[1-9][0-9]{11}$"), is(true));

        MOTTestResultPageTestCompletePage testCompletePage =
                testSummary.enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint();

        assertThat("Print pass certificate message doesn't exist",
                testCompletePage.passCertificateMessageIsPresent(), is(true));
        assertThat("Print refusal certificate message doesn't exist",
                testCompletePage.refusalCertificateMessageIsPresent(), is(true));
    }
}
