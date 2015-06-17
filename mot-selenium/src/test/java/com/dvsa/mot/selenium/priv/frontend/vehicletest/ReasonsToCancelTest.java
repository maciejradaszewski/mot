package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.ReasonsToCancelPage;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class ReasonsToCancelTest extends BaseTest {

    @DataProvider(name = "DP-MotTestReasonsToCancel") public Object[][] reasonsToCancelProvider() {

        return new Object[][] {{ReasonToCancel.REASON_ACCIDENT_OR_ILLNESS,
                createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004)},
                {ReasonToCancel.REASON_ABORTED_BY_VE,
                        createVehicle(Vehicle.VEHICLE_CLASS4_HYUNDAI_2012)},
                {ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR,
                        createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002)},
                {ReasonToCancel.REASON_TEST_EQUIPMENT_ISSUE,
                        createVehicle(Vehicle.VEHICLE_CLASS1_KAWASAKI_2013)},
                {ReasonToCancel.REASON_VTS_INCIDENT,
                        createVehicle(Vehicle.VEHICLE_CLASS1_DAKOTA_1924)},
                {ReasonToCancel.REASON_DANGEROUS_OR_CAUSE_DAMAGE,
                        createVehicle(Vehicle.VEHICLE_CLASS4_BOXSTER_2001)}};
    }

    @Test(dataProvider = "DP-MotTestReasonsToCancel", groups = {"slice_A", "VM-1557",
            "short-vehicle"})
    public void testAbortMotTestAndClickCancelAndClickReturn(ReasonToCancel reasonToCancel,
            Vehicle vehicle) {

        ReasonsToCancelPage reasonsToCancelPage =
                ReasonsToCancelPage.navigateHereFromLoginPage(driver, login, vehicle)
                        .submitReasonsToCancelPageExpectingError();

        assertThat("Unexpected error message",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        reasonsToCancelPage.returnToMotTestPage().clickCancelMotTest();

        if (reasonToCancel.equals(ReasonToCancel.REASON_DANGEROUS_OR_CAUSE_DAMAGE)) {
            new ReasonsToCancelPage(driver)
                    .enterAndSubmitReasonsToCancelPageExpectingAbandonedPage(reasonToCancel,
                            Text.TEXT_PASSCODE).clickFinish();
        } else {
            new ReasonsToCancelPage(driver)
                    .enterAndSubmitReasonsToCancelPageExpectingAbortedPage(reasonToCancel)
                    .clickFinish();
        }

        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);

        assertThat(
                "Check user is redirected to home page by checking if start MOT test button is displayed",
                userDashboardPage.isStartMotTestDisplayed(), is(true));
    }

    @Test(groups = {"slice_A", "VM-4502"})
    public void testTesterCannotAbandonAnMotTestOfAnotherTester() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004);

        String motTestId =
                MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).getMotTestId();
        MotTestPage motTestPage = new MotTestPage(driver);
        motTestPage.clickLogout().loginAs(driver, Login.LOGIN_TESTER1);
        driver.get(baseUrl() + "/mot-test/" + motTestId + "/cancel");

        assertThat("Abandoning a test option is displayed",
                new ReasonsToCancelPage(driver).isReasonDangerousOrCauseDamageDisplayed(),
                is(false));
    }
}
