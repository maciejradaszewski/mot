package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.ManualAdvisory;
import com.dvsa.mot.selenium.datasource.ManualAdvisory.Lateral;
import com.dvsa.mot.selenium.datasource.ManualAdvisory.Longitudinal;
import com.dvsa.mot.selenium.datasource.ManualAdvisory.Vertical;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

public class ManualAdvisoryPage extends BasePage {

    @FindBy(id = "modal-rfr-title-0") private WebElement title;

    @FindBy(id = "lateral-dd-0") private WebElement lateral;

    @FindBy(id = "longitudinal-dd-0") private WebElement longitudinal;

    @FindBy(id = "vertical-dd-0") private WebElement vertical;

    @FindBy(id = "description-0") private WebElement description;

    @FindBy(id = "dangerous") private WebElement dangerousFailure;

    @FindBy(id = "rfr-submit-0") private WebElement addButton;

    @FindBy(id = "rfr-cancel-0") private WebElement cancelButton;

    @FindBy(className = "validation-summary") private WebElement errorMessages;

    @FindBy(id = "info-message") private WebElement infoMessage;

    public ManualAdvisoryPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

    public ManualAdvisoryPage selectLateral(Lateral lat) {
        Select lateralDropdown = new Select(lateral);
        switch (lat) {
            case notApply:
                lateralDropdown.selectByIndex(1);
                break;
            default:
                lateralDropdown.selectByValue(lat.toString());
                break;
        }

        return this;
    }

    public ManualAdvisoryPage selectLongitudinal(Longitudinal lon) {
        Select lateralDropdown = new Select(longitudinal);
        switch (lon) {
            case notApply:
                lateralDropdown.selectByIndex(1);
                break;
            default:
                lateralDropdown.selectByValue(lon.toString());
                break;
        }

        return this;
    }

    public ManualAdvisoryPage selectVertical(Vertical vert) {
        Select lateralDropdown = new Select(vertical);
        switch (vert) {
            case notApply:
                lateralDropdown.selectByIndex(1);
                break;
            default:
                lateralDropdown.selectByValue(vert.toString());
                break;
        }

        return this;
    }

    public ManualAdvisoryPage enterDescription(String description) {
        this.description.sendKeys(description);
        return this;
    }

    public ManualAdvisoryPage clearDescription() {
        this.description.clear();
        return this;
    }

    public ManualAdvisoryPage selectDangerousFailure(boolean isDangerous) {
        if ((dangerousFailure.isDisplayed() && isDangerous && !dangerousFailure.isSelected()) || (
                dangerousFailure.isDisplayed() && !isDangerous && dangerousFailure.isSelected()))
            dangerousFailure.click();
        return this;
    }

    public ReasonForRejectionPage addManualAdvisory() {
        addButton.click();
        return new ReasonForRejectionPage(driver);
    }

    public ReasonForRejectionPage addManualAdvisoryExpectingError() {
        addButton.click();
        waitForTextToBePresentInElement(errorMessages,
                Assertion.ASSERTION_PROFANITY_DETECTED.assertion, 20);
        return new ReasonForRejectionPage(driver);
    }

    public ReasonForRejectionPage cancelManualAdvisory() {
        cancelButton.click();
        waitForElementToBeVisible(driver.findElement(By.tagName("h1")), 5);
        return new ReasonForRejectionPage(driver);
    }

    public String getErrorMessages() {
        return errorMessages.getText();
    }

    public ManualAdvisoryPage enterManualAdvisory(ManualAdvisory manualAdvisory) {
        selectLateral(manualAdvisory.lateral);
        selectLongitudinal(manualAdvisory.longitudinal);
        selectVertical(manualAdvisory.vertical);
        enterDescription(manualAdvisory.description);
        selectDangerousFailure(manualAdvisory.isDangerousFailure);
        return this;
    }

    public ReasonForRejectionPage submitManualAdvisory(ManualAdvisory manualAdvisory) {
        ReasonForRejectionPage reasonForRejectionPage = new ReasonForRejectionPage(driver);
        int previousCount = reasonForRejectionPage.getPreviousRfrCount();
        enterManualAdvisory(manualAdvisory);
        addManualAdvisory();
        reasonForRejectionPage.waitForRfrUpdate(previousCount);
        return new ReasonForRejectionPage(driver);
    }

}
