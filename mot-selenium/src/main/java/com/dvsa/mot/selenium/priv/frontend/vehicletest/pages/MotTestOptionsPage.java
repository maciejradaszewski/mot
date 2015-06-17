package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class MotTestOptionsPage extends BasePage {
    private static String PAGE_TITLE = "MOT TEST STARTED";

    @FindBy(id = "sign-out") private WebElement signOut;

    @FindBy(id = "return_to_home") private WebElement returnToHome;

    public MotTestOptionsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public MotTestOptionsPage(WebDriver driver, String pageTitle) {
        super(driver);
        checkTitle(pageTitle);
    }

    public static MotTestOptionsPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle) {
        return StartTestConfirmation1Page.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .submitConfirm();
    }

    public static MotTestOptionsPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle, Site site) {
        return StartTestConfirmation1Page.navigateHereFromLoginAsManyVTSTester(driver, login,
                vehicle, site)
                .submitConfirm();
    }

    public UserDashboardPage returnToHome() {
        returnToHome.click();
        return new UserDashboardPage(driver);
    }

    public boolean isSignOutButtonDisplayed() {

        return isElementDisplayed(signOut);
    }

}
