package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.datasource.Contact;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class CreateSitePage extends BasePage {
    protected String PAGE_TITLE = "CREATE VEHICLE TESTING STATION";

    @FindBy(id = "name") private WebElement name;

    @FindBy(id = "addressLine1") private WebElement addressLine1;

    @FindBy(id = "addressLine2") private WebElement addressLine2;

    @FindBy(id = "addressLine3") private WebElement addressLine3;

    @FindBy(id = "town") private WebElement town;

    @FindBy(id = "postcode") private WebElement postcode;

    @FindBy(id = "email") private WebElement email;

    @FindBy(id = "emailConfirmation") private WebElement emailConfirmation;

    @FindBy(id = "phoneNumber") private WebElement phoneNumber;

    @FindBy(id = "faxNumber") private WebElement faxNumber;

    @FindBy(id = "correspondenceContactSame") private WebElement correspondenceContactSame;

    @FindBy(id = "correspondenceAddressLine1") private WebElement correspondenceAddressLine1;

    @FindBy(id = "correspondenceAddressLine2") private WebElement correspondenceAddressLine2;

    @FindBy(id = "correspondenceAddressLine3") private WebElement correspondenceAddressLine3;

    @FindBy(id = "correspondenceTown") private WebElement correspondenceTown;

    @FindBy(id = "correspondencePostcode") private WebElement correspondencePostcode;

    @FindBy(id = "correspondenceEmail") private WebElement correspondenceEmail;

    @FindBy(id = "correspondenceEmailConfirmation") private WebElement
            correspondenceEmailConfirmation;

    @FindBy(id = "correspondencePhoneNumber") private WebElement correspondencePhoneNumber;

    @FindBy(id = "correspondenceFaxNumber") private WebElement correspondenceFaxNumber;

    @FindBy(id = "save") private WebElement save;

    @FindBy(id = "cancel-link") private WebElement cancel;


    public CreateSitePage(WebDriver driver) {
        super(driver);
        new CreateSitePage(driver, PAGE_TITLE);
    }

    public CreateSitePage(WebDriver driver, String pageTitle) {
        super(driver);
        PAGE_TITLE = pageTitle;
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public static CreateSitePage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickOnNewSiteLink();
    }

    public SiteDetailsPage cancel() {
        cancel.click();
        return new SiteDetailsPage(driver);
    }
}

