package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SearchForAePage extends BasePage {

    private static String PAGE_TITLE = "SEARCH FOR AE";

    @FindBy(xpath = ".//h1") private WebElement aeSearchTitle;

    @FindBy(id = "number") private WebElement aeNumberInputField;

    @FindBy(id = "submitAeSearch") private WebElement aeSearchButton;

    @FindBy(id = "navigation-link-") private WebElement returnHomeLink;

    public SearchForAePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static SearchForAePage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickListAllAEs();
    }

    public AuthorisedExaminerFullDetailsPage submitSearchForAe(String aeNumber) {
        aeNumberInputField.sendKeys(aeNumber);
        aeSearchButton.click();
        return new AuthorisedExaminerFullDetailsPage(driver);
    }

    public EnforcementHomePage clickReturnHomeLink() {
        returnHomeLink.click();
        return new EnforcementHomePage(driver);
    }

    public DetailsOfAuthorisedExaminerPage searchForAeAndSubmit(String aeNumber) {
        aeNumberInputField.sendKeys(aeNumber);
        aeSearchButton.click();
        return new DetailsOfAuthorisedExaminerPage(driver);
    }

}
