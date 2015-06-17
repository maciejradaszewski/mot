package com.dvsa.mot.selenium.priv.frontend.organisation.management.common;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class FindAUserPage extends BasePage {

    @FindBy(id = "userSearchBox") protected WebElement userSearchBox;

    @FindBy(id = "search-button") protected WebElement searchButton;

    @FindBy(id = "not-found-user-id") protected WebElement unKnownUserId;

    @FindBy(id = "invalid-user-message") protected WebElement invalidUserMessage;

    public FindAUserPage(WebDriver driver) {
        super(driver);
    }

    public static FindAUserPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Site site) {
        return SiteDetailsPage.navigateHereFromLoginPage(driver, login, site)
                .clickAssignARoleLink();
    }

    public FindAUserPage enterUsername(String username) {
        userSearchBox.sendKeys(username);
        return new FindAUserPage(driver);
    }

    public SelectARolePage search() {
        searchButton.click();
        return new SelectARolePage(driver);
    }

    public SelectARolePage search(String pageTitle) {
        searchButton.click();
        return new SelectARolePage(driver, pageTitle);
    }

    public FindAUserPage searchExpectingError() {
        searchButton.click();
        return new FindAUserPage(driver);
    }

    public String getInvalidUserMessage() {
     return invalidUserMessage.getText();
    }
}

