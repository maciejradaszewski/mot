package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.domain.model.BrakeTestType;
import uk.gov.dvsa.domain.model.NumberOfAxles;
import uk.gov.dvsa.domain.model.VehicleWeightType;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class BrakeTestConfigurationPage extends Page{
    private static final String PAGE_TITLE = "Brake test configuration";

    @FindBy(id = "brake_test_results_submit") private WebElement nextButton;

    @FindBy(id = "brake_test_results_cancel") private WebElement cancelButton;

    @FindBy(id = "serviceBrake1TestType") private WebElement serviceBrakeTestType;

    @FindBy(id = "parkingBrakeTestType") private WebElement parkingBrakeTestType;

    @FindBy(id = "numberOfAxles") private WebElement numberOfAxlesDropdown;

    @FindBy(id = "weightType-VSI") private WebElement brakeWeightRadioBox;

    @FindBy(id = "radioOptionBrakeLineTypeDual") private WebElement brakeLineDual;

    @FindBy(id = "vehicleWeight") private WebElement vehicleWeight;

    public BrakeTestConfigurationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    protected BrakeTestResultsPage fillAllFieldsWithValidDataAndSubmit() {
        fillBrakeTestType();
        fillWeightFields();
        selectBrakeLineType();
        selectNoOfAxles();

        nextButton.click();

        return new BrakeTestResultsPage(driver);
    }

    private void fillBrakeTestType() {
        FormCompletionHelper.selectFromDropDownByValue(serviceBrakeTestType, BrakeTestType.ROLLER.getValue());
        FormCompletionHelper.selectFromDropDownByValue(parkingBrakeTestType, BrakeTestType.ROLLER.getValue());
    }

    private void fillWeightFields() {
        FormCompletionHelper.selectInputBox(brakeWeightRadioBox);
        vehicleWeight.sendKeys(VehicleWeightType.BRAKE_TEST_WEIGHT.getVehicleWeight());
    }

    private void selectBrakeLineType() {
        FormCompletionHelper.selectInputBox(brakeLineDual);
    }

    private void selectNoOfAxles() {
        FormCompletionHelper.selectFromDropDownByValue(numberOfAxlesDropdown, NumberOfAxles.TWO_AXLES.getValue());
    }
}
