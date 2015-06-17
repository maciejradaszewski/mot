package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class VtsAbortMotTestSuccessfullyPage extends BasePage {

    protected String PAGE_TITLE = "MOT - AbortMOT test - Vehicle Testing Station";

    @FindBy(id = "print-abort-certificate") private WebElement printAbortCertificate;

    @FindBy(id = "mot-test-number") private WebElement motTestNumber;

    @FindBy(id = "reason-for-aborting-test") private WebElement reasonForAbortingTest;

    @FindBy(id = "sln-action-return") private WebElement goToVtsOverViewPage;

    @FindBy(id = "confirmationTitle") private WebElement confirmationMessage;

    public VtsAbortMotTestSuccessfullyPage(WebDriver driver, String pageTitle) {
        super(driver);
        PAGE_TITLE = pageTitle;
        PageFactory.initElements(driver, this);

    }

    public boolean isPrintVT30Displayed() {
        return (printAbortCertificate.isDisplayed());
    }
}

