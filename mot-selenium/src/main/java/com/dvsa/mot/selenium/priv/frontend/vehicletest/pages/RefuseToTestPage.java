package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonForRefusal;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class RefuseToTestPage extends BasePage {
    private String PAGE_TITLE = "REFUSE TO TEST";

    @FindBy(id = "refuse-mot-test") private WebElement refuseMotTest;

    @FindBy(id = "validation-summary-id") private WebElement validationSummary;

    @FindBy(id = "back_to_search") private WebElement backToSearch;

    @FindBy(id = "registration-mark") private WebElement registrationMark;

    @FindBy(id = "vin") private WebElement vin;

    @FindBy(id = "make-and-model") private WebElement makeAndModel;

    public RefuseToTestPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static RefuseToTestPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle) {
        return StartTestConfirmation1Page.navigateHereFromLoginPageAsMotTest(driver, login, vehicle)
                .clickRefuseVehicle();
    }

    public RefuseToTestPage selectReasonForRefusal(ReasonForRefusal reasonForRefusal) {

        WebElement reason = new WebDriverWait(driver, 1).until(ExpectedConditions
                .elementToBeClickable(
                        findWebElement(By.id("refusal-" + reasonForRefusal.getId()))));
        reason.click();
        return this;
    }

    public MotTestRefusedPage clickRefuseMotTest() {

        WebElement refuseMotTest = new WebDriverWait(driver, 10).until(ExpectedConditions
                .elementToBeClickable(findWebElement(By.id("refuse-mot-test"))));
        refuseMotTest.click();
        return new MotTestRefusedPage(driver);
    }

    public RefuseToTestPage clickRefuseMotTestExpectingError() {
        refuseMotTest.click();
        return new RefuseToTestPage(driver);
    }

    public MotTestRefusedPage refuseMotTest(ReasonForRefusal reasonForRefusal) {
        selectReasonForRefusal(reasonForRefusal);
        return clickRefuseMotTest();
    }


    public String getRegistrationMark() {
        return registrationMark.getText();
    }

    public String getVin() {
        return vin.getText();
    }

    public String getMakeAndModel() {
        return makeAndModel.getText();
    }

    public StartTestConfirmation1Page backToSearch() {
        backToSearch.click();
        return new StartTestConfirmation1Page(driver);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
