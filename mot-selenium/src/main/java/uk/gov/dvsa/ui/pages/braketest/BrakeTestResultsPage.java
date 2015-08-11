package uk.gov.dvsa.ui.pages.braketest;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.BrakeTestResultsHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.module.BrakeTestResultsPageElements;

import java.util.Map;

public class BrakeTestResultsPage extends Page {
    @FindBy(id = "brake_test_results_submit") private WebElement submitButton;
    @FindBy(id = "brake_test_summary_done") private WebElement doneButton;
    @FindBy(id = "brake_test_results_cancel") private WebElement cancelButton;

    private static final String PAGE_TITLE = "Brake test results";

    public BrakeTestResultsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public BrakeTestResultsPage completeBrakeEffortField(String outcome) {
        if(outcome.equals("pass")) {
            enterBrakeResultsPageFields(BrakeTestResultsHelper.allPass());
        }else {
            enterBrakeResultsPageFields(BrakeTestResultsHelper.allFail());
        }

        submitButton.click();

        doneButton.click();

        return this;
    }

    public BrakeTestResultsPage enterField(BrakeTestResultsPageElements field, String value) {
        WebElement webElement = findElement(field);
        switch (field.getFieldType()) {
            case Input:
                enterInputField(webElement, value);
                break;
            case Radiobutton:
                enterInputRadioButton(webElement, Boolean.valueOf(value));
                break;
            case Checkbox:
                enterInputCheckbox(webElement, Boolean.valueOf(value));
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
            Map<BrakeTestResultsPageElements, Object> valuesMap) {
        for (Map.Entry<BrakeTestResultsPageElements, Object> entry : valuesMap.entrySet()) {
            enterField(entry.getKey(), entry.getValue().toString());
        }
        return this;
    }

    private WebElement findElement(BrakeTestResultsPageElements field) {
        return driver.findElement(By.id(field.getId()));
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

    private void enterInputRadioButton(WebElement webElement, boolean checked) {
        if (checked && !webElement.isSelected())
            webElement.click();
        else if (!checked && webElement.isSelected())
            webElement.click();
    }

    private void enterInputDropdown(WebElement webElement, String value) {
        Select dropdown = new Select(webElement);
        dropdown.selectByVisibleText(value);
    }
}
