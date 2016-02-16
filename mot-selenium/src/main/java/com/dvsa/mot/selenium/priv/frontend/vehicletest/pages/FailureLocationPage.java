package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.FailureLocation;
import com.dvsa.mot.selenium.datasource.ReasonForRejection;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.Select;
import org.openqa.selenium.support.ui.WebDriverWait;

import java.util.List;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class FailureLocationPage extends BasePage {

    @FindBy(id = "modal-rfr-title-0") private WebElement modalTitle;

    @FindBy(name = "locationLateral") private WebElement lateral;

    @FindBy(name = "locationLongitudinal") private WebElement longitudinal;

    @FindBy(name = "locationVertical") private WebElement vertical;

    @FindBy(name = "comment") private WebElement description;

    @FindBy(id = "dangerous") private WebElement dangerousFailure;

    @FindBy(name = "submit") private WebElement addButton;

    //TODO add id or name attribut in the page
    @FindBy(linkText = "Close") private WebElement cancelButton;

    public FailureLocationPage(WebDriver driver) {
        super(driver);
        // Dynamic submit button
        List<WebElement> submitElements = driver.findElements(By.name("submit"));
        for (WebElement webElement : submitElements) {
            if (webElement.isDisplayed()) {
                this.addButton = webElement;
            }
        }
    }

    public FailureLocationPage selectLateral(int selectId, FailureLocation.Lateral lat) {
        WebElement e = driver.findElement(By.id("lateral-dd-" + selectId));
        Select lateralDropdown = new Select(e);
        switch (lat) {
            case notApply:
                lateralDropdown.selectByIndex(0);
                break;
            default:
                lateralDropdown.selectByValue(lat.toString());
                break;
        }
        return this;
    }

    public FailureLocationPage selectLongitudinal(int selectId, FailureLocation.Longitudinal lon) {
        WebElement e = driver.findElement(By.id("longitudinal-dd-" + selectId));
        Select longitudinalDropdown = new Select(e);
        switch (lon) {
            case notApply:
                longitudinalDropdown.selectByIndex(0);
                break;
            default:
                longitudinalDropdown.selectByValue(lon.toString());
                break;
        }

        return this;
    }

    public FailureLocationPage selectVertical(int selectId, FailureLocation.Vertical vert) {
        WebElement e = driver.findElement(By.id("vertical-dd-" + selectId));
        Select verticalDropdown = new Select(e);
        switch (vert) {
            case notApply:
                verticalDropdown.selectByIndex(0);
                break;
            default:
                verticalDropdown.selectByValue(vert.toString());
                break;
        }

        return this;
    }

    public FailureLocationPage enterDescription(int failureId, String description) {
        driver.findElement(By.id("description-" + failureId)).sendKeys(description);
        return this;
    }

    public String getDescription() {
        return this.description.getText();
    }

    public FailureLocationPage selectDangerousFailure(boolean isDangerous) {
        try {
            if (isDangerous && !dangerousFailure.isSelected())
                dangerousFailure.click();
            else if (!isDangerous && dangerousFailure.isSelected())
                dangerousFailure.click();
        } catch (Exception e) {
            //e.printStackTrace();
        }
        return this;
    }

    public ReasonForRejectionPage addFailureLocation(int rejectionId) {
        WebDriverWait wait = new WebDriverWait(driver, 40);
        wait.until(
                ExpectedConditions.visibilityOfElementLocated(By.id("rfr-submit-" + rejectionId)));
        driver.findElement(By.id("rfr-submit-" + rejectionId)).click();
        waitForAjaxToComplete();
        waitForPageToLoad();
        return new ReasonForRejectionPage(driver);
    }

    public FailureLocationPage enterFailureLocation(int rejectionId,
            FailureLocation failureLocation) {
        selectLateral(rejectionId, failureLocation.lateral);
        selectLongitudinal(rejectionId, failureLocation.longitudinal);
        selectVertical(rejectionId, failureLocation.vertical);
        enterDescription(rejectionId, failureLocation.description);
        selectDangerousFailure(failureLocation.isDangerousFailure);
        return this;
    }

    public FailureLocationPage checkRfrText(ReasonForRejection reasonForRejection,
            boolean isAdvisory) {
        WebElement rfrText;
        String text;
        if (isAdvisory) {
            waitForElementToBeVisible(By.id("modal-rfr-advisory-" + reasonForRejection.reasonId),
                    defaultWebElementTimeout);
            rfrText =
                    driver.findElement(By.id("modal-rfr-advisory-" + reasonForRejection.reasonId));
            text = reasonForRejection.advisoryText;
        } else {
            waitForElementToBeVisible(By.id("modal-rfr-description-" + reasonForRejection.reasonId),
                    defaultWebElementTimeout);
            rfrText = driver.findElement(
                    By.id("modal-rfr-description-" + reasonForRejection.reasonId));
            text = reasonForRejection.reasonDescription;
        }
        assertThat("Rfr text in title ", rfrText.getText().trim().toUpperCase(),
                is(text.toUpperCase()));
        return this;
    }

}
