package com.dvsa.mot.selenium.priv.frontend.login.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementHomePage;
import com.dvsa.mot.selenium.priv.frontend.openam.ChangePasswordPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMClaimAccountSignInPage;
import com.dvsa.mot.selenium.priv.frontend.openam.OpenAMLockoutPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserNamePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.VehicleSearchPage;
import org.openqa.selenium.By;
import org.openqa.selenium.Keys;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

/**
 * Page Object class which models <b>Login Page</b>
 */

public class LoginPage extends BasePage {

    private static final String PAGE_TITLE = "MOT TESTING SERVICE";
    private static final String OLD_PAGE_TITLE = "SIGN IN TO OPENAM";

    @FindBy(name = "Login.Submit") private WebElement submitButton;

    @FindBy(id = "info-message") private WebElement infoMessage;

    public LoginPage(WebDriver driver) {

        super(driver);

        String pageTitle = getPageTitle();

        if (pageTitle.equals(PAGE_TITLE)) {

            checkTitle(PAGE_TITLE);

        } else if (pageTitle.equals(OLD_PAGE_TITLE)) {

            checkTitle(OLD_PAGE_TITLE);
        }
    }

    private WebElement getUserNameInputField() {

        WebElement usernameField = null;

        if (isElementPresent(By.xpath("//div[@class='form-group']/input[@type='text'][2]"))) {

            usernameField = driver.findElement(
                    By.xpath("//div[@class='form-group']/input[@type='text'][2]"));

        } else if (isElementPresent(By.id("IDToken1"))) {

            usernameField = driver.findElement(By.id("IDToken1"));
        }

        return usernameField;
    }

    private WebElement getPasswordInputField() {

        WebElement passwordField = null;

        if (isElementPresent(By.xpath("//div[@class='form-group']/input[@type='password'][2]"))) {

            passwordField = driver.findElement(
                    By.xpath("//div[@class='form-group']/input[@type='password'][2]"));

        } else if (isElementPresent(By.id("IDToken2"))) {

            passwordField = driver.findElement(By.id("IDToken2"));
        }

        return passwordField;
    }

    public LoginPage typeUsername(String text) {

        getUserNameInputField().sendKeys(text);
        return this;
    }

    public LoginPage typePassword(String password) {
        getPasswordInputField().sendKeys(password);
        return this;
    }

    public UserDashboardPage clickSubmit() {
        submitButton.click();
        return new UserDashboardPage(driver);
    }

    private LoginPage enterLoginCredentials(String username, String password) {
        typeUsername(username);
        typePassword(password);
        return this;
    }


    public UserDashboardPage loginAsUser(Login login) {
        enterLoginCredentials(login.username, login.password);
        return clickSubmit();
    }

    public UserDashboardPage loginAsUserSubmitWithEnterKeyInUsernameField(Login login) {
        enterLoginCredentials(login.username, login.password);
        getUserNameInputField().click();
        getUserNameInputField().sendKeys(Keys.ENTER);
        return new UserDashboardPage(driver);
    }

    public UserDashboardPage loginAsUserSubmitWithEnterKeyInPasswordField(Login login) {
        enterLoginCredentials(login.username, login.password);
        getPasswordInputField().click();
        getPasswordInputField().sendKeys(Keys.ENTER);
        return new UserDashboardPage(driver);
    }

    public EnforcementHomePage loginAsEnforcementUser(Login login) {
        enterLoginCredentials(login.username, login.password);
        clickSubmit();
        return new EnforcementHomePage(driver);
    }

    /**
     * Login to the system with provided single VTS user,
     * Search for car with provided VIN and Reg, go to the MOT Test landing page
     *
     * @param user
     * @param password
     * @param vin
     * @param reg
     * @return MotTestPage
     */
    public MotTestPage loginSearchVINandRegGoToMotTestResults(String user, String password,
            String vin, String reg) {
        return VehicleSearchPage.navigateHereFromLoginPage(driver, new Login(user, password))
                .submitSearchWithVinAndReg(vin, reg).startTest();
    }

    public UserDashboardPage loginAsUserExpectingUserDashboardPage(Login login) {
        enterLoginCredentials(login.username, login.password);
        clickSubmit();
        return new UserDashboardPage(driver);
    }

    public boolean isUserLoggedIn() {
        return getUserNameInputField().isDisplayed() ? false : true;
    }

    public static LoginPage navigateToLoginPage(WebDriver driver) {
        driver.get(baseUrl());
        return new LoginPage(driver);
    }

    public static UserDashboardPage loginAs(WebDriver driver, Login login) {
        navigateToLoginPage(driver);
        LoginPage loginPage = new LoginPage(driver);
        UserDashboardPage userDashboardPage =
                loginPage.loginAsUserExpectingUserDashboardPage(login);
        userDashboardPage.verifyOnDashBoard();
        return userDashboardPage;
    }

    public OpenAMClaimAccountSignInPage navigateToClaimAccountPage(WebDriver driver, Login login) {
        enterLoginCredentials(login.username, login.password);
        clickSubmit();
        driver.get(baseUrl() + "/account/claim");
        return new OpenAMClaimAccountSignInPage(driver);
    }

    public static UserNamePage forgottenPassWord(WebDriver driver) {

        driver.get(baseUrl() + "/forgotten-password");
        return new UserNamePage(driver);
    }

    public ChangePasswordPage loginExpectingChangePasswordPage(WebDriver driver, Login login) {
        enterLoginCredentials(login.username, login.password);
        submitButton.click();
        return new ChangePasswordPage(driver);
    }

    public OpenAMLockoutPage loginExpectingToBeLockedOut(WebDriver driver, Login login) {
        enterLoginCredentials(login.username, login.password);
        submitButton.click();
        return new OpenAMLockoutPage(driver);
    }

    public AuthorisationFailedPage loginExpectingAuthorisationFailedPage(WebDriver driver,
        String username, String password) {
        enterLoginCredentials(username, password);
        submitButton.click();
        return new AuthorisationFailedPage(driver);
    }

}
