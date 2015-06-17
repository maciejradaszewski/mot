package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;
import org.testng.Assert;
import org.testng.annotations.Test;

public class RecordAndRetestTest extends BaseTest {


    @Test(groups = {"slice_A","VM-1586", "VM-1949",
            "VM-1950"}, description = "Original test - Mileage entered is lower same and higher than previous")
    public void testPassedMotTestAgainForOdometerReadingWithDifferentValues() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA);
        MotTestPage motTestPage = TestSummary
                .navigateHereFromLoginPage(driver, login, vehicle, "29000",
                        BrakeTestConfiguration4.brakeTestConfigClass4_CASE1(),
                        BrakeTestResults4.allPass(), null, null, null, null)
                .clickFinishPrint(Text.TEXT_PASSCODE).clickHome().startMotTest()
                .submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg)

                .startTest().enterOdometerValuesAndSubmit("28999");
        Assert.assertEquals(Assertion.ASSERTION_CURRENT_LOWER_THAN_PREVIOUS.assertion,
                motTestPage.getOdometerReadingNotice());

        motTestPage.reSubmitOdometerValueForMotTest("29000");
        Assert.assertEquals(Assertion.ASSERTION_CURRENT_EQUALS_PREVIOUS.assertion,
                motTestPage.getOdometerReadingNotice());

        motTestPage.reSubmitOdometerValueForMotTest("55000");
        Assert.assertEquals(Assertion.ASSERTION_VALUE_SIGNIFICANTLY_HIGHER.assertion,
                motTestPage.getOdometerReadingNotice());
    }
}
