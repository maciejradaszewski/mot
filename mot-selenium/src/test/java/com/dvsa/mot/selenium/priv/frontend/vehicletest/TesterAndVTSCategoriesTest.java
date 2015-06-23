package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.StartTestConfirmation1Page;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class TesterAndVTSCategoriesTest extends BaseTest {

    private static ReasonToCancel reasonToCancel = ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR;

    @Test(groups = {"Regression", "short"})
    public void TesterCat4CanTestVehicleCat4_singleVTSAuthorised() {

        MotTestPage motTestPage = MotTestPage.navigateHereFromLoginPage(driver, login,
                createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004));
        motTestPage.cancelMotTest(reasonToCancel);
        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);

        assertThat("Validate that user is navigated to home page",
                userDashboardPage.isStartMotTestDisplayed(), is(true));
    }

    @Test(groups = "Regression") public void TesterCat1CanTestVehicleCat1_singleVTSAuthorised() {

        MotTestPage motTestPage = MotTestPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_CATATESTER,
                        createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002));
        motTestPage.cancelMotTest(reasonToCancel);
        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);

        assertThat("Validate that user is navigated to home page",
                userDashboardPage.isStartMotTestDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "short"})
    public void TesterCat1CanNotTestVehicleCat1_singleVTSAuthorised() {

        StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, Login.LOGIN_CATATESTER,
                        createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004))
                .submitConfirmExpectingError();

        assertThat("Wrong validation summary",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = "Regression") public void TesterCat4CanNotTestVehicleCat1_manyVTSAuthorised() {

        StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, Login.LOGIN_CATBTESTER,
                        createVehicle(Vehicle.VEHICLE_CLASS1_BALENO_2002))
                .submitConfirmExpectingError();

        assertThat("Wrong validation summary",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }
}
