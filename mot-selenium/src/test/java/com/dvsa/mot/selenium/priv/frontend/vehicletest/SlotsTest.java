package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.datasource.dynamic.AE;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.annotations.Test;

import java.util.Arrays;
import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.testng.Assert.assertFalse;
import static org.testng.Assert.assertTrue;

public class SlotsTest extends BaseTest {

    @Test(groups = {"Regression", "VM-12"})
    public void testInactiveUserCanNotStartMOTTest_SingleTester() {

        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_NOROLES);

        assertThat("Start MOT test is available for inactive user",
                dashboardPage.isStartMotTestDisplayed(), is(false));
    }

    @Test(groups = {"Regression", "VM-16", "VM-2729"})
    public void testUserStatusAndSlots_Active_SingleTester_NoSlots() {

        UserDashboardPage dashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_NOSLOTSTESTER)
                        .clickStartMotTestExpectingError();

        assertThat("Check page source", dashboardPage.getPageSource()
                .contains(Assertion.ASSERTION_PURCHASE_SLOTS.assertion), is(true));
        assertThat("Active slots", dashboardPage.getAvailableSlotsInAe(1), is(0));
    }

    @Test(groups = {"Regression", "VM-5173"}, description = "added this test to cover a bug fix")
    public void testAnActiveTesterHavingOnly_SingleSlotAtVts_CanResumeAnMotTest() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS1_KAWASAKI_2013);

        //Create new AE with new tester ,1 slot and 1 associated VTS's
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("createSite", Login.LOGIN_AREA_OFFICE1, 1);

        //Add VTS to AE
        String siteName = "NewVTS";
        Site site = new VtsCreationApi().createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,siteName);

        //Get Tester login add to VTS and AE
        Person testerLogin = createTesterAsPerson(Collections.singletonList(site.getId()),false);
        Login login = testerLogin.login;

        //Get no of available slots at the start of an Mot Test
        int noOfAvailableAeSlots =
                new LoginPage(driver).loginAsUserExpectingUserDashboardPage(login)
                        .getAvailableSlotsInAe(1);
        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);
        userDashboardPage.startMotTest().
                submitSearchWithVinAndReg(vehicle.fullVIN,
                        vehicle.carReg).startTest().clickLogout()
                .loginAsUser(login).resumeMotTest()
                .cancelMotTest(ReasonToCancel.REASON_ACCIDENT_OR_ILLNESS);

        assertThat("slot been released after a test been cancelled",
                userDashboardPage.getAvailableSlotsInAe(1), is(noOfAvailableAeSlots));
    }
}
