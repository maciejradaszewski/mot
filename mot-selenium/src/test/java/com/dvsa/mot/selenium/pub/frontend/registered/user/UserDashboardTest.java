package com.dvsa.mot.selenium.pub.frontend.registered.user;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class UserDashboardTest extends BaseTest {

    @Test(groups = {"VM-3025", "Sprint-22", "LA-2"}) public void testLoginAsAEDMandVerifyLinks() {

        UserDashboardPage userDashboard =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_AEDM);
        assertThat(" View all Special notices link present and clickable",
                userDashboard.isViewAllForSpecialNoticesLinkClickable(), is(true));
        userDashboard.clickLogout();
    }

    @Test(groups = {"VM-3025", "Sprint-22", "LA-2"})
    public void testLoginAsAreaOffice1AndVerifyLinks() {

        UserDashboardPage userDashboard =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1);
        verifyCommonGroup1LinksPresent(userDashboard);
        verifyCommonGroup2LinksPresent(userDashboard);
        userDashboard.clickLogout();
    }

    @Test(groups = {"VM-3025", "Sprint-22", "LA-2"}) public void testLoginAsTesterAndVerifyLinks() {
        UserDashboardPage userDashboard =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        assertThat(" Start MOT training mode link present and clickable",
                userDashboard.isStartMotTrainingModeLinkClickable(), is(true));
        assertThat(" View all Special notices link present and clickable",
                userDashboard.isViewAllForSpecialNoticesLinkClickable(), is(true));
        userDashboard.clickLogout();
    }

    private void verifyCommonGroup2LinksPresent(UserDashboardPage userDashboard) {
        assertThat(" Create AE link present and clickable", userDashboard.isCreateAELinkClickable(),
                is(true));
        assertThat(" Edit AE link present and clickable", userDashboard.isEditAELinkClickable(),
                is(true));
        assertThat(" View all Special notices link present and clickable",
                userDashboard.isViewAllForSpecialNoticesLinkClickable(), is(true));
    }

    private void verifyCommonGroup1LinksPresent(UserDashboardPage userDashboard) {

        assertThat(" AE Search link present and clickable", userDashboard.isSearchAELinkClickable(),
                is(true));
        assertThat(" Site search  link present and clickable",
                userDashboard.isSearchSiteLinkClickable(), is(true));
        assertThat(" User search link present and clickable",
                userDashboard.isSearchUserLinkClickable(), is(true));
        assertThat(" Replacement/duplicate Certificate",
                userDashboard.isCertificateReIssueLinkClickable(), is(true));
    }

    @Test(groups = {"Sprint 23", "MOT Testing", "VM-2722", "VM-3175"},
            description =
                    "When the tester has an mot Test in progress, retest and documentation links shouldn't be displayed. "
                            + "In addition we test story 3175, to ensure that when a tester has an mot test active, the 'resume mot test' button is displayed instead of 'start motTest'.")
    public void testStartRetestLinkAndReissueCertificateLinksAreNotPresentWhenMotTestInProgress() {
        MotTestPage.navigateHereFromLoginPage(driver, login,
                createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004)).clickLogout();
        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login);
        Assert.assertFalse(userDashboardPage.existStartMotRetestLink());
        Assert.assertFalse(userDashboardPage.existReissueCertificateLink());
        userDashboardPage.resumeMotTest()
                .cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR);
        Assert.assertTrue(userDashboardPage.existStartMotRetestLink());
        Assert.assertTrue(userDashboardPage.existReissueCertificateLink());
    }

    @Test(groups = {"Sprint 23", "MOT Testing", "VM-3175"},
            description = "When tester start an mot test and navigated away, the tester must resume the started mot test before start a new one.")
    public void testResumeMotTestWhenUserNavigatedAway() {
        UserDashboardPage userDashboardPage = MotTestPage.navigateHereFromLoginPage(driver, login,
                createVehicle(Vehicle.VEHICLE_CLASS4_CLIO_2004)).clickMyProfile().clickHome();
        Assert.assertTrue(userDashboardPage.existResumeMotTestButton());
        Assert.assertFalse(userDashboardPage.existStartMotRetestLink());
        Assert.assertFalse(userDashboardPage.existReissueCertificateLink());
        userDashboardPage.resumeMotTest()
                .cancelMotTest(ReasonToCancel.REASON_VEHICLE_REGISTERED_ERROR);
        Assert.assertTrue(userDashboardPage.existStartMotRetestLink());
        Assert.assertTrue(userDashboardPage.existReissueCertificateLink());
    }

    @Test(groups = {"VM-4564", "Regression", "W-Sprint3"})
    public void testLoginAsCustomerServiceAndVerifyLinks() {
        UserDashboardPage userDashboard =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_CUSTOMER_SERVICE);
        assertThat(" View all Special notices link present and clickable",
                userDashboard.isViewAllForSpecialNoticesLinkClickable(), is(true));
        userDashboard.clickLogout();
    }

    @Test(groups = {"VM-4791", "Regression"})
    public void testVerifyCookieLinkClickableInTheFooter() {
        UserDashboardPage userDashboardPage = LoginPage.loginAs(driver, login);
        assertThat(userDashboardPage.isCookieLinkClickable(), is(true));
    }
}
