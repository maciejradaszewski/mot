package com.dvsa.mot.selenium.e2e.trade;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VtsAbortMotTestPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VtsAbortMotTestSuccessfullyPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.StartTestConfirmation1Page;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.NotificationPage;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class TradeRolesUserJourneyForAbortingATest extends BaseTest {

    @DataProvider(name = "rolesWhoCanViewAndAbortAnActiveMotTestAtAnUsersVts")
    public Object[][] rolesWhoCanViewAndAbortAnActiveMotTestAtAnUsersVts() {
        return new Object[][] {{Login.LOGIN_SITE_ADMIN_AT_VTS1}, {Login.LOGIN_SITE_MANAGER_AT_VTS1},
                {Login.LOGIN_ANOTHER_TESTER_AT_VTS1},};
    }

    @Test(groups = {"VM-4343", "VM-4407", "Regression", "Sprint 29,Team-X", "E2E"},
            dataProvider = "rolesWhoCanViewAndAbortAnActiveMotTestAtAnUsersVts",
            description = "roles Who Can View And Abort An Mot Test")
    public void testWhoCanViewAndAbortAnActiveMotTestAtAnUsersVts(Login login) {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_NO_REG_6VIN);
        StartTestConfirmation1Page startTestConfirmation1Page = StartTestConfirmation1Page
                .navigateHereFromLoginPageAsMotTest(driver, Login.LOGIN_TESTER_AT_VTS1, vehicle);
        startTestConfirmation1Page.submitConfirm().returnToHome().resumeMotTest();
        String motId = startTestConfirmation1Page.getMotTestId();
        startTestConfirmation1Page.clickLogout();
        SiteDetailsPage.navigateHereFromLoginPage(driver, login, Site.FT_GARAGE_1)
                .clickOnActiveMotTestLink(motId).clickOnAbortMotTest()
                .clickOnTheAbortTestButtonExpectingErrorMessage();

        VtsAbortMotTestPage vtsAbortMotTestPage = new VtsAbortMotTestPage(driver);
        assertThat("Unexpected error message",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));

        VtsAbortMotTestSuccessfullyPage vtsAbortMotTestSuccessfullyPage =
                vtsAbortMotTestPage.selectAReasonForAbortingTestOption(1)
                        .clickOnTheAbortTestButton();
        assertThat("Mot test has finished already",
                vtsAbortMotTestSuccessfullyPage.isPrintVT30Displayed());

        NotificationPage notificationPage = vtsAbortMotTestSuccessfullyPage.clickLogout()
                .loginAsUser(login.LOGIN_TESTER_AT_VTS1).clickNotification("Mot test was aborted");
        assertThat("Wrong vehicle was aborted",
                notificationPage.isTheCorrectVehicleBeenAborted(vehicle.fullVIN));
    }
}
