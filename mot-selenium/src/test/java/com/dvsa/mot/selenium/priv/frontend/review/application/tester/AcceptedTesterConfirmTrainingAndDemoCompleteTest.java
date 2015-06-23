package com.dvsa.mot.selenium.priv.frontend.review.application.tester;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.datasource.dynamic.AE;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.DuplicateReplacementCertificatePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MOTTestResultPageTestCompletePage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.PerformanceDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import java.util.Collections;

public class AcceptedTesterConfirmTrainingAndDemoCompleteTest extends BaseTest {


    @Test(groups = {"VM-3654", "Sprint 25", "MOT Testing","Regression"},
            description = "Start a demo test and verify is the demo test is available when searched for the replacement or duplicate certificate")
    public void testStartADemoTestAndVerifyItIsNotAppearingInReplacementOrDuplicateDocumentationSearch() {
        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_BMW_ALPINA_2);
        Login login = createTester(false);
        MOTTestResultPageTestCompletePage motTestResultPageTestCompletePage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login).
                        startDemoTest().
                        submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg).
                        startDemoTest().
                        addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                                BrakeTestResults4.brakeTestEntry_CASE1(), null, null, null, null).
                        createCertificate().
                        clickFinishPrint();
        motTestResultPageTestCompletePage.clickLogout();
        DuplicateReplacementCertificatePage duplicateReplacementCertificatePage =
                DuplicateReplacementCertificatePage
                        .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1, vehicle);
        Assert.assertFalse(
                duplicateReplacementCertificatePage.isReplacementCertificateViewDisplayed());
    }

    private UserDashboardPage testAsAnActiveTesterStartADemoTest() {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).
                startDemoTest().
                submitSearchWithVinAndReg(Vehicle.VEHICLE_CLASS4_MERCEDES_C300.fullVIN,
                        Vehicle.VEHICLE_CLASS4_MERCEDES_C300.carReg).
                startDemoTest().
                addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.brakeTestEntry_CASE1(), null, null, null, null).
                createCertificate().
                clickFinishPrint().
                clickDoneButton();
    }

    @Test(groups = {"VM-3654", "VM-3653", "Sprint 25", "MOT Testing","Regression"},
            description = "A user can start a demo test for classes where he is not trained on and verify the same test is not available on the tester's performance dashboard")
    public void testIsTheDemoTestNotDisplayedOnActiveTestersPerformanceDashboard() {
        PerformanceDashboardPage performanceDashboardPage =
                testAsAnActiveTesterStartADemoTest().clickOnTesterPerformanceDashboard();
        Assert.assertTrue(performanceDashboardPage.isDemoTestResultRecorded());
    }

    @Test(groups = {"VM-3648", "Sprint 25", "MOT Testing", "Regression"},
            description = "Start a demo test and verify is the slots associated to an ae is decremented after it")
    public void testIsSlotsAssociatedToAnAeDecrementedAfterADemoTest() {

        Vehicle vehicle = createVehicle(Vehicle.VEHICLE_CLASS4_MERCEDES_C300);

        //Create new AE with 1000 slots and 0 associated VTS's
        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("createSite", Login.LOGIN_AREA_OFFICE1, 1000);

        //Add VTS to AE with 1 tester
        String siteName = "NewVTS";
        Site site = new VtsCreationApi().createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,siteName);

        //Get Tester login
        Person testerLogin = createTesterAsPerson(Collections.singletonList(site.getId()),false);
        Login login = testerLogin.login;

        int noOfAvailableAeSlots =
                new LoginPage(driver).loginAsUserExpectingUserDashboardPage(login)
                        .getAvailableSlotsInAe(1);

        UserDashboardPage userDashboardPage = new UserDashboardPage(driver);
        userDashboardPage.startDemoTest().
                submitSearchWithVinAndReg(vehicle.fullVIN, vehicle.carReg).
                startDemoTest().
                addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.brakeTestEntry_CASE1(), null, null, null, null).
                createCertificate().
                clickFinishPrint().
                clickDoneButton().clickHome();
        Assert.assertEquals(noOfAvailableAeSlots, userDashboardPage.getAvailableSlotsInAe(1),
                "No of slots decremented after a demo test");
    }
}
