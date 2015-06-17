package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.NotFoundException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

import java.util.List;

public class VtsAbortMotTestPage extends BasePage {
    protected String PAGE_TITLE = "MOT - AbortMOT test - Vehicle Testing Station";

    @FindBy(id = "reasonForCancel-1") private WebElement reasonForCancel1;

    @FindBy(id = "reasonForCancel-2") private WebElement reasonForCancel2;

    @FindBy(id = "reasonForCancel-3") private WebElement reasonForCancel3;

    @FindBy(id = "reasonForCancel-4") private WebElement reasonForCancel4;

    @FindBy(id = "reasonForCancel-5") private WebElement reasonForCancel5;

    @FindBy(id = "reasonForCancel-6") private WebElement reasonForCancel6;

    @FindBy(id = "sln-action-abort") private WebElement abortMotTestButton;

    @FindBy(id = "sln-action-return") private WebElement goToVtsMotTestPage;


    public VtsAbortMotTestPage(WebDriver driver) {
        super(driver);
        new VtsAbortMotTestPage(driver, PAGE_TITLE);
    }

    public VtsAbortMotTestPage(WebDriver driver, String pageTitle) {
        super(driver);
        PAGE_TITLE = pageTitle;
        PageFactory.initElements(driver, this);

    }

    public VtsAbortMotTestPage selectAReasonForAbortingTestOption(int option) {
        List<WebElement> radios = driver.findElements(By.name("reasonForCancelId"));
        if (option > 0 && option <= radios.size()) {
            radios.get(option - 1).click();
        } else {
            throw new NotFoundException("Reason for aborting test " + option + " not found");
        }
        return this;
    }

    public VtsAbortMotTestSuccessfullyPage clickOnTheAbortTestButton() {
        abortMotTestButton.click();
        return new VtsAbortMotTestSuccessfullyPage(driver, PAGE_TITLE);
    }

    public VtsAbortMotTestPage clickOnTheAbortTestButtonExpectingErrorMessage() {
        abortMotTestButton.click();
        return new VtsAbortMotTestPage(driver);
    }

    public boolean isErrorMessageDisplayed() {
        return new ValidationSummary().isValidationSummaryDisplayed(driver);
    }
}

