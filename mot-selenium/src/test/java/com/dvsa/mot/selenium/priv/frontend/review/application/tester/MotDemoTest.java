package com.dvsa.mot.selenium.priv.frontend.review.application.tester;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificatePage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.PerformanceDashboardPage;
import org.testng.annotations.Test;

import java.util.Collections;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalTo;
import static org.hamcrest.Matchers.is;

public class MotDemoTest extends BaseTest {

    private final Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_2);

    private Login createANewTesterRole() {
        //Create new AE with 1000 slots and 0 associated VTS's
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("createSite", Login.LOGIN_AREA_OFFICE1, 1000);

        //Add VTS to AE with 1 tester
        String siteName = "NewVTS";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);

        //Get Tester login
        Person testerLogin = createTesterAsPerson(Collections.singletonList(site.getId()), false);
        Login login = testerLogin.login;
        return login;
    }

    @Test(groups = {"VM-3654", "VM-10522", "Sprint 25", "MOT Testing", "Regression"},
            description = "Perform a demo test and verify it is not available when searched for the replacement or duplicate certificate")
    public void testPerformADemoTestAndVerifyItIsNotAppearingInReplacementOrDuplicateDocumentationSearch() {
        Login testerLogin = createANewTesterRole();

        testAsAnActiveTesterStartADemoTest(vehicle, testerLogin).clickLogout();
        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle);
        assertThat(duplicateReplacementCertificatePage.isReplacementCertificateViewDisplayed(),
                is(false));
    }

    private UserDashboardPage testAsAnActiveTesterStartADemoTest(Vehicle vehicle, Login login) {
        //Demo Test started and resuming it after confirming the test
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).
                startDemoTest().
                submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg).
                confirmDemoTest().clickHome().resumeDemoTest()
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.brakeTestEntry_CASE1(), null, null, null, null).
                        createCertificate().
                        clickFinishPrint().
                        clickDoneButton();
    }

    @Test(groups = {"VM-3654", "VM-3653", "Sprint 25", "MOT Testing", "Regression"},
            description = "A user can start a demo test for classes where he is not trained on and verify the same test is not available on the tester's performance dashboard")
    public void testIsTheDemoTestNotDisplayedOnActiveTestersPerformanceDashboard() {
        Login testerLogin = createANewTesterRole();
        PerformanceDashboardPage performanceDashboardPage =
                testAsAnActiveTesterStartADemoTest(vehicle, testerLogin)
                        .clickOnTesterPerformanceDashboard();
        assertThat(performanceDashboardPage.isDemoTestResultRecorded(), is(true));
    }

    @Test(groups = {"VM-3648", "Sprint 25", "MOT Testing", "Regression"},
            description = "Start a demo test and verify is the slots associated to an ae is decremented after it")
    public void testIsSlotsAssociatedToAnAeDecrementedAfterADemoTest() {

        Login testerLogin = createANewTesterRole();
        UserDashboardPage userDashboardPage =
                new LoginPage(driver).loginAsUserExpectingUserDashboardPage(testerLogin);

        int noOfAvailableAeSlots = userDashboardPage.getAvailableSlotsInAe(1);
        userDashboardPage.clickLogout();
        testAsAnActiveTesterStartADemoTest(vehicle, testerLogin).clickHome();
        assertThat("No of slots decremented after a demo test", noOfAvailableAeSlots,
                equalTo(userDashboardPage.getAvailableSlotsInAe(1)));
    }
}
