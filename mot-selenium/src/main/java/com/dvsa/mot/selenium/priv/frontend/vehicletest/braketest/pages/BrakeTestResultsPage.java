package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestResultsPageField;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

import java.util.Map;

public class BrakeTestResultsPage extends BasePage {
    private static final String PAGE_TITLE = "BRAKE TEST RESULTS";

    @FindBy(id = "brake_test_results_submit") private WebElement submitButton;

    @FindBy(id = "brake_test_results_cancel") private WebElement cancelButton;


    public BrakeTestResultsPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public static BrakeTestResultsPage navigateHereFromLoginPageAsMotTest(WebDriver driver,
            Login login, Vehicle vehicle, Map<BrakeTestConfigurationPageField, Object> entries) {
        return BrakeTestConfigurationPage.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .enterBrakeConfigurationPageFields(entries).submit();
    }

    public static BrakeTestResultsPage navigateHereFromLoginPageAsMotRetest(WebDriver driver,
            Login login, String motId, Map<BrakeTestConfigurationPageField, Object> entries) {
        return BrakeTestConfigurationPage.navigateHereFromLoginPageAsMotRetest(driver, login, motId)
                .enterBrakeConfigurationPageFields(entries).submit();
    }

    public BrakeTestSummaryPage submit() {
        submitButton.click();
        return new BrakeTestSummaryPage(driver);
    }

    public BrakeTestResultsPage submitExpectingError() {
        submitButton.click();
        return new BrakeTestResultsPage(driver);
    }

    public BrakeTestResultsPage enterField(BrakeTestResultsPageField field, String value) {
        WebElement webElement = findElement(field);
        switch (field.getFieldType()) {
            case Input:
                enterInputField(webElement, value);
                break;
            case Radiobutton:
                enterInputRadiobutton(webElement, new Boolean(value));
                break;
            case Checkbox:
                enterInputCheckbox(webElement, new Boolean(value));
                break;
            case Dropdown:
                enterInputDropdown(webElement, value);
                break;
            default:
                break;
        }
        return this;
    }

    public BrakeTestResultsPage enterBrakeResultsPageFields(
            Map<BrakeTestResultsPageField, Object> valuesMap) {
        for (Map.Entry<BrakeTestResultsPageField, Object> entry : valuesMap.entrySet()) {
            enterField(entry.getKey(), entry.getValue().toString());
        }
        return this;
    }

    private WebElement findElement(BrakeTestResultsPageField field) {
        WebElement element = null;
        try {
            turnOffImplicitWaits();
            element = driver.findElement(By.id(field.getId()));
        } catch (NoSuchElementException e) {
            element = null;
        } finally {
            turnOnImplicitWaits();
        }
        return element;
    }

    // Commons methods
    private void enterInputField(WebElement webElement, String value) {
        webElement.clear();
        webElement.sendKeys(value);
    }

    private void enterInputCheckbox(WebElement webElement, boolean checked) {
        if (checked && !webElement.isSelected())
            webElement.click();
        else if (!checked && webElement.isSelected())
            webElement.click();
    }

    private void enterInputRadiobutton(WebElement webElement, boolean checked) {
        if (checked && !webElement.isSelected())
            webElement.click();
        else if (!checked && webElement.isSelected())
            webElement.click();
    }

    private void enterInputDropdown(WebElement webElement, String value) {
        Select dropdown = new Select(webElement);

        try {
            turnOffImplicitWaits();
            dropdown.selectByVisibleText(value);
        } catch (NoSuchElementException e) {
            dropdown.selectByValue(value);
        } finally {
            turnOnImplicitWaits();
        }
    }

    public MotTestPage cancel() {
        cancelButton.click();
        return new MotTestPage(driver);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
