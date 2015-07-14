package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.domain.model.mot.CancelTestReason;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ReasonToCancelTestPage extends Page {
    @FindBy(id = "reasonForCancel13") private WebElement reasonAccidentOrIllness;

    @FindBy(id = "reasonForCancel25") private WebElement reasonAbortedByVE;

    @FindBy(id = "reasonForCancel28") private WebElement reasonVehicleRegisteredError;

    @FindBy(id = "reasonForCancel12") private WebElement reasonTestEquipmentIssue;

    @FindBy(id = "reasonForCancel5") private WebElement reasonVTSincident;

    @FindBy(id = "reasonForCancel6") private WebElement reasonIncorrectLocation;

    @FindBy(id = "reasonForCancel21") private WebElement reasonDangerousOrCauseDamage;

    @FindBy(id = "oneTimePassword") private WebElement enterYourPinField;

    @FindBy(id = "mot_test_cancel_confirm") private WebElement confirmAndCancelTestButton;

    @FindBy(id = "returnToMotTest") private WebElement returnToMotTest;

    @FindBy(id = "cancelComment") private WebElement cancelComment;

    private static final String PAGE_TITLE = "Reasons to cancel test";
    private MotAppDriver driver;

    public ReasonToCancelTestPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
        this.driver = driver;
        PageFactory.initElements(driver, this);
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ReasonToCancelTestPage enterReason(CancelTestReason reason){
        selectReason(reason);
        if (reason.equals(CancelTestReason.DANGEROUS_OR_CAUSE_DAMAGE)) {
            enterCancelComment(reason.getDescription());
            enterUserPin(driver.getCurrentUser());
        }

        return this;
    }

    public void clickConfirmAndCancelTest(){
        confirmAndCancelTestButton.click();
    }

    private void enterUserPin(User currentUser) {
        enterYourPinField.sendKeys(currentUser.getPin());
    }

    public ReasonToCancelTestPage selectReason(CancelTestReason reason) {
        switch (reason) {
            case ACCIDENT_OR_ILLNESS:
                selectReasonAccidentOrIllness();
                break;
            case ABORTED_BY_VE:
                selectReasonAbortedByVE();
                break;
            case VEHICLE_REGISTERED_IN_ERROR:
                selectReasonVehicleRegisteredError();
                break;
            case TEST_EQUIPMENT_ISSUE:
                selectReasonTestEquipmentIssue();
                break;
            case VTS_INCIDENT:
                selectReasonVTSincident();
                break;
            case INCORRECT_LOCATION:
                selectReasonIncorrectLocation();
                break;
            case DANGEROUS_OR_CAUSE_DAMAGE:
                selectReasonDangerousOrCauseDamage();
                break;
            default:
                break;
        }

        return this;
    }

    private ReasonToCancelTestPage selectReasonAccidentOrIllness() {
        reasonAccidentOrIllness.click();
        return this;
    }

    private ReasonToCancelTestPage selectReasonAbortedByVE() {
        reasonAbortedByVE.click();
        return this;
    }

    private ReasonToCancelTestPage selectReasonVehicleRegisteredError() {
        reasonVehicleRegisteredError.click();
        return this;
    }

    private ReasonToCancelTestPage selectReasonTestEquipmentIssue() {
        reasonTestEquipmentIssue.click();
        return this;
    }

    private ReasonToCancelTestPage selectReasonIncorrectLocation() {
        reasonIncorrectLocation.click();
        return this;
    }

    private ReasonToCancelTestPage selectReasonVTSincident() {
        reasonVTSincident.click();
        return this;
    }

    private ReasonToCancelTestPage selectReasonDangerousOrCauseDamage() {
        reasonDangerousOrCauseDamage.click();
        return this;
    }

    private ReasonToCancelTestPage enterCancelComment(String comment) {
        cancelComment.sendKeys(comment);
        return this;
    }
}
