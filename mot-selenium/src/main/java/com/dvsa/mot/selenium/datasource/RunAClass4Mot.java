package com.dvsa.mot.selenium.datasource;

import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfiguration4;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResults4;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.StartTestConfirmation1Page;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.PageFactory;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TestSummary;

public class RunAClass4Mot {

    private final WebDriver driver;

    public RunAClass4Mot(WebDriver driver) {
        PageFactory.initElements(driver, this);
        this.driver = driver;
    }

    /**
     * A method to run an MOT test using vehicle and test data from the data class.
     */
    public UserDashboardPage runMotTest(Login login, Vehicle vehicleInfo) {
        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.enforcement_CASE1(), null, null, null, null)
                .createCertificate().enterNewPasscode(Text.TEXT_PASSCODE).clickFinishPrint()
                .clickDoneButton();
    }

    public TestSummary runMotTestFailPrint(Login login, Vehicle vehicleInfo){
       return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo)
        .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.allFail(), null, null, null, null).createCertificate()
        .enterNewPasscode(Text.TEXT_PASSCODE);
        }

    public TestSummary runWelshMotTestPassPrint(Login login,Vehicle vehicleInfo, Site site){
        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo, site)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.enforcement_CASE1(), null, null, null, null)
                .createCertificate()
                .enterNewPasscode(Text.TEXT_PASSCODE);
    }

    public TestSummary runWelshMotTestFailPrint(Login login, Vehicle vehicleInfo, Site site){
        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicleInfo, site)
                .addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                        BrakeTestResults4.sBFailOnly(), null, null, null, null).createCertificate()
                .enterNewPasscode(Text.TEXT_PASSCODE);
    }

    /**
     * A method to run an MOT test using vehicle and test data from the data class.
     */
    public void runAReInspectionMotTestPass(String title) {
        MotTestPage mottestpage1 = new MotTestPage(driver, title);
        mottestpage1.addMotTest("12345", BrakeTestConfiguration4.enforcement_CASE1(),
                BrakeTestResults4.enforcement_CASE1(), null, null, null, null, title)
                .createCertificate();
    }

    /**
     * Test to check that the short summary pop up is present
     */
    public MotTestPage runMotToInProgressStage(Login login, Vehicle vehicleInfo) {
        LoginPage loginpage = new LoginPage(driver);
        //Login as MOT tester
        UserDashboardPage userDashboardPage = loginpage.loginAsUser(login);
        //Select garage
        VehicleSearchPage vehicleSearchPage = userDashboardPage.startMotTest();

        //Enter vehicle details
        vehicleSearchPage.submitSearchWithVinAndReg(vehicleInfo.fullVIN, vehicleInfo.carReg);
        StartTestConfirmation1Page confirm = new StartTestConfirmation1Page(driver);
        //Confirm this is the correct vehicle, state will change to in progress
        return confirm.startTest();
    }
}
