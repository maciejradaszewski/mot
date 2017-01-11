package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;
import uk.gov.dvsa.domain.model.mot.CancelTestReason;
import uk.gov.dvsa.domain.model.mot.Defect;
import uk.gov.dvsa.domain.model.mot.OdometerUnit;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormDataHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.PageLocator;
import uk.gov.dvsa.ui.pages.braketest.BrakeTestConfigurationPage;
import uk.gov.dvsa.ui.pages.braketest.BrakeTestResultsPage;

public class TestResultsEntryNewPage extends Page implements TestResultsEntryPageInterface {

    public static final String PATH = "/mot-test";

    private static final String PAGE_TITLE = "MOT Test results";

    @FindBy(id = "vehicleMakeModel") private WebElement vehicleMakeModel;
    @FindBy(id = "vehicleRegistration") private WebElement vehicleRegistration;
    @FindBy(id = "firstUsedDate") private WebElement firstUsedDate;
    @FindBy(id = "odometerReading") private WebElement odometerReading;
    @FindBy(id = "odometer") private WebElement odometerField;
    @FindBy(id = "addDefectButton") private WebElement addDefect;
    @FindBy(id = "searchForDefect") private WebElement searchForDefect;
    @FindBy(id = "addBrakeTestButton") private WebElement addBrakeTest;
    @FindBy(id = "reviewTest") private WebElement reviewTest;
    @FindBy(id = "cancelTest") private WebElement cancelTest;
    @FindBy(id = "validation-message--success") private WebElement validationMessageSuccess;
    @FindBy(css = "#rfrList") private WebElement reasonsForRejectionList;
    @FindBy(xpath = ".//*[@id='rfrList']//*[@class='defect__title']") private WebElement reasonForRejectionTitle;
    @FindBy(xpath = "//*[@id='rfrList']//a[contains(., 'Remove')]") private WebElement removeDefectLink;
    @FindBy(id = "reviewTest") private WebElement reviewTestButton;
    @FindBy(id = "addOdometerReadingButton") private WebElement addOdometerReading;
    @FindBy(id = "odometer_submit") private WebElement odometerSubmit;
    @FindBy(id = "unit") private WebElement odometerUnit;

    public TestResultsEntryNewPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public String getVehicleMakeModel() {
        return vehicleMakeModel.getText();
    }

    public String getVehicleRegistration() {
        return vehicleRegistration.getText();
    }

    public String getVehicleFirstUsedDate() {
        return firstUsedDate.getText();
    }

    public String getOdometerReading() {
        return odometerReading.getText();
    }

    public boolean addOdomoterReadingButtonIsDisplayed() {
        return addOdometerReading.isDisplayed();
    }

    public boolean addDefectButtonIsDisplayed() {
        return addDefect.isDisplayed();
    }

    public OdometerReadingPage clickAddReadingButton() {
        addOdometerReading.click();
        return new OdometerReadingPage(driver);
    }

    public TestSummaryPage clickReviewTestButton() {
        reviewTest.click();
        return new TestSummaryPage(driver);
    }

    public boolean searchForDefectIsDisplayed() {
        return searchForDefect.isDisplayed();
    }

    public boolean addBrakeTestButtonIsDisplayed() {
        return addBrakeTest.isDisplayed();
    }

    public boolean reviewTestButtonIsDisplayed() {
        return reviewTest.isDisplayed();
    }

    public TestAbortedPage abortMotTest(CancelTestReason reason){
        processTestCancellation(reason);

        return new TestAbortedPage(driver);
    }

    public TestAbandonedPage abandonMotTest(CancelTestReason reason){
        processTestCancellation(reason);

        return new TestAbandonedPage(driver);
    }

    public TestResultsEntryNewPage completeBrakeTestWithPassValues() {
        addDefaultBrakeTestValues("pass");

        return this;
    }

    public DefectCategoriesPage clickAddDefectButton() {
        addDefect.click();

        return new DefectCategoriesPage(driver);
    }

    private void processTestCancellation(CancelTestReason reason) {
        cancelTest.click();

        ReasonToCancelTestPage cancelTestPage = new ReasonToCancelTestPage(driver);
        cancelTestPage.enterReason(reason);
        cancelTestPage.clickConfirmAndCancelTest();
    }

    private TestResultsEntryNewPage addDefaultBrakeTestValues(String outcome) {
        addBrakeTest.click();

        BrakeTestConfigurationPage brakeTestConfigurationPage =
                PageLocator.getBrakeTestConfigurationPage(driver);

        BrakeTestResultsPage brakeTestResultsPage =
                brakeTestConfigurationPage.fillAllFieldsWithValidDataAndSubmit();

        brakeTestResultsPage.completeBrakeEffortField(outcome);

        return this;
    }

    public boolean isOdometerReadingUpdateSuccessMessageDisplayed(){
        return validationMessageSuccess.getText().equals("The odometer reading has been updated");
    }

    public RemoveDefectPage navigateToRemoveDefectPage(Defect defect) {
        removeDefectLink.click();

        return new RemoveDefectPage(driver, defect.getAddOrRemovalType());
    }

    public boolean isDefectRemovedSuccessMessageDisplayed(Defect defect) {
        return validationMessageSuccess.getText().equals(
                String.format("This %s has been removed:\n%s", defect.getAddOrRemovalType(), defect.getAddOrRemoveName()));
    }

    public boolean isDefectInReasonsForRejection(Defect defect) {
        return reasonForRejectionTitle.isDisplayed() && reasonForRejectionTitle.getText().contains(defect.getDefectName());
    }

    public Boolean isClickReviewTestButtonPresent(){
        return reviewTestButton.isDisplayed();
    }

    private TestResultsEntryNewPage addOdometerReading(int odometerReading) {
        addOdometerReading.click();
        FormDataHelper.enterText(odometerField, String.valueOf(odometerReading));
        setOdometerUnit(OdometerUnit.KILOMETRES);
        odometerSubmit.click();

        return this;
    }

    private void setOdometerUnit(OdometerUnit unit) {
        new Select(odometerUnit).selectByValue(unit.getValue());
    }

    public TestResultsEntryPageInterface completeTestDetailsWithPassValues() {
        addOdometerReading(1001);
        completeBrakeTestWithPassValues();

        return this;
    }
}