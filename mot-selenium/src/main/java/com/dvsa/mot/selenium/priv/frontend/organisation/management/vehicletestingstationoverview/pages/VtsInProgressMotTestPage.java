package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class VtsInProgressMotTestPage extends BasePage {
    protected String PAGE_TITLE = "MOT - Vehicle Testing Station";

    @FindBy(id = "sln-action-abort") private WebElement abortMotTestButton;

    @FindBy(id = "sln-action-return") private WebElement goToVtsOverViewPage;

    public VtsInProgressMotTestPage(WebDriver driver) {
        super(driver);
        new VtsInProgressMotTestPage(driver, PAGE_TITLE);
    }
    
    public VtsInProgressMotTestPage(WebDriver driver, String pageTitle) {
        super(driver);
        PAGE_TITLE = pageTitle;
        PageFactory.initElements(driver, this);
    }

    public VtsAbortMotTestPage clickOnAbortMotTest() {
        abortMotTestButton.click();
        return new VtsAbortMotTestPage(driver);
    }
}

