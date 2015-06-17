package com.dvsa.mot.selenium.priv.frontend.vehicletest.braketest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.braketest.BrakeTestConfigurationPageField;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MOTRetestPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.MotTestPage;
import org.openqa.selenium.By;
import org.openqa.selenium.NoSuchElementException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.openqa.selenium.support.ui.Select;

import java.util.Map;

public class BrakeTestConfigurationPage extends BasePage {
    private final String PAGE_TITLE = "BRAKE TEST CONFIGURATION";

    @FindBy(id = "brake_test_results_submit") private WebElement submitButton;

    @FindBy(id = "brake_test_results_cancel") private WebElement cancelButton;

    @FindBy(id = "serviceBrake1TestType") private WebElement serviceBrake1TestType;

    @FindBy(id = "parkingBrakeTestType") private WebElement parkingBrakeTestType;

    public BrakeTestConfigurationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public static BrakeTestConfigurationPage navigateHereFromLoginPageAsMotTest(WebDriver driver,
            Login login, Vehicle vehicle) {
        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).addBrakeTest();
    }

    public static BrakeTestConfigurationPage navigateHereFromLoginPageAsMotRetest(WebDriver driver,
            Login login, String motId) {
        return MOTRetestPage.navigateHereFromLoginPage(driver, login, motId).addBrakeTest();
    }

    public BrakeTestResultsPage submit() {
        submitButton.click();
        return new BrakeTestResultsPage(driver);
    }

    public BrakeTestConfigurationPage submitExpectingError() {
        submitButton.click();
        return this;
    }

    public MotTestPage cancel() {
        cancelButton.click();
        return new MotTestPage(driver);
    }

    public BrakeTestConfigurationPage enterField(BrakeTestConfigurationPageField field,
            String value) {
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

    public BrakeTestConfigurationPage enterBrakeConfigurationPageFields(
            Map<BrakeTestConfigurationPageField, Object> valuesMap) {
        for (Map.Entry<BrakeTestConfigurationPageField, Object> entry : valuesMap.entrySet()) {
            enterField(entry.getKey(), entry.getValue().toString());
        }
        return this;
    }

    // Commons methods
    private WebElement findElement(BrakeTestConfigurationPageField field) {
        WebElement element = null;
        try {
            element = driver.findElement(By.id(field.getId()));
        } catch (NoSuchElementException e) {
            element = null;
        }
        return element;
    }

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
            dropdown.selectByVisibleText(value);
        } catch (NoSuchElementException e) {
            dropdown.selectByValue(value);
        }
    }

    public boolean isElementMarkedInvalid(BrakeTestConfigurationPageField field) {
        WebElement element = driver.findElement(By.id(field.getId()));
        try {
            return findElementMarkedInvalid().equals(element);
        } catch (Exception e) {
            return false;
        }
    }

    public String valueOfWeightField(BrakeTestConfigurationPageField field) {
        WebElement weightField = findElement(field);
        return weightField.getAttribute("value");
    }

    public boolean isSelectedBrakeTestDefaultsForClassBDisplayedCorrectly(
            String SelectedServiceBrakeTestType, String SelectedParkingBrakeTestType) {
        if (SelectedServiceBrakeTestType.equals("Plate") && SelectedParkingBrakeTestType
                .equals("Plate")) {
            return true;
        }
        return false;
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
