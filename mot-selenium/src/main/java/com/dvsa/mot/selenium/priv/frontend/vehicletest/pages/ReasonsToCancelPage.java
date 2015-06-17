package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.Reason;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class ReasonsToCancelPage extends BasePage {

    private static final String PAGE_TITLE = "REASONS TO CANCEL TEST";
    @FindBy(id = "reasonForCancel13") private WebElement reasonAccidentOrIllness;

    @FindBy(id = "reasonForCancel25") private WebElement reasonAbortedByVE;

    @FindBy(id = "reasonForCancel28") private WebElement reasonVehicleRegisteredError;

    @FindBy(id = "reasonForCancel12") private WebElement reasonTestEquipmentIssue;

    @FindBy(id = "reasonForCancel5") private WebElement reasonVTSincident;

    @FindBy(id = "reasonForCancel6") private WebElement reasonIncorrectLocation;

    @FindBy(id = "reasonForCancel21") private WebElement reasonDangerousOrCauseDamage;

    @FindBy(id = "oneTimePassword") private WebElement oneTimePasscode;

    @FindBy(id = "mot_test_cancel_confirm") private WebElement confirm;

    @FindBy(id = "returnToMotTest") private WebElement returnToMotTest;

    @FindBy(id = "cancelComment") private WebElement cancelComment;

    public ReasonsToCancelPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    private ReasonsToCancelPage selectReasonAccidentOrIllness() {
        reasonAccidentOrIllness.click();
        return this;
    }

    private ReasonsToCancelPage selectReasonAbortedByVE() {
        reasonAbortedByVE.click();
        return this;
    }

    private ReasonsToCancelPage selectReasonVehicleRegisteredError() {
        reasonVehicleRegisteredError.click();
        return this;
    }

    private ReasonsToCancelPage selectReasonTestEquipmentIssue() {
        reasonTestEquipmentIssue.click();
        return this;
    }

    private ReasonsToCancelPage selectReasonIncorrectLocation() {
        reasonIncorrectLocation.click();
        return this;
    }

    private ReasonsToCancelPage selectReasonVTSincident() {
        reasonVTSincident.click();
        return this;
    }

    private ReasonsToCancelPage selectReasonDangerousOrCauseDamage() {
        reasonDangerousOrCauseDamage.click();
        return this;
    }

    public ReasonsToCancelPage enterOneTimePasscode(String passcode) {
        oneTimePasscode.sendKeys(passcode);
        return this;
    }

    public ReasonsToCancelPage enterCancelComment(String comment) {
        cancelComment.sendKeys(comment);
        return this;
    }

    public ReasonsToCancelPage submitReasonsToCancelPageExpectingError() {
        confirm.click();
        return new ReasonsToCancelPage(driver);
    }

    public MotTestAbortedPage submitReasonsToCancelPageExpectingAbortedPage() {
        confirm.click();
        return new MotTestAbortedPage(driver);
    }

    public MotTestAbandonedPage submitReasonsToCancelPageExpectingAbandonedPage() {
        confirm.click();
        return new MotTestAbandonedPage(driver);
    }

    public MotTestPage returnToMotTestPage() {
        returnToMotTest.click();
        return new MotTestPage(driver);
    }

    public ReasonsToCancelPage selectReason(Reason reason) {
        switch (reason) {
            case AccidentOrIllness:
                selectReasonAccidentOrIllness();
                break;
            case AbortedByVE:
                selectReasonAbortedByVE();
                break;
            case VehicleRegisteredInError:
                selectReasonVehicleRegisteredError();
                break;
            case testEquipmentIssue:
                selectReasonTestEquipmentIssue();
                break;
            case VTSincident:
                selectReasonVTSincident();
                break;
            case incorrectLocation:
                selectReasonIncorrectLocation();
                break;
            case dangerousOrCauseDamage:
                selectReasonDangerousOrCauseDamage();
                break;
            default:
                break;
        }

        return this;
    }

    public ReasonsToCancelPage enterReasonsToCancelPage(ReasonToCancel reason) {
        selectReason(reason.reasonToCancel);
        if (reason.reasonToCancel == Reason.dangerousOrCauseDamage)
            enterCancelComment(reason.cancelComment);
        return this;
    }


    public MotTestAbortedPage enterAndSubmitReasonsToCancelPageExpectingAbortedPage(
            ReasonToCancel reason) {
        enterReasonsToCancelPage(reason);
        return submitReasonsToCancelPageExpectingAbortedPage();
    }

    public MotTestAbandonedPage enterAndSubmitReasonsToCancelPageExpectingAbandonedPage(
            ReasonToCancel reason, String passcode) {
        enterReasonsToCancelPage(reason);
        enterOneTimePasscode(passcode);
        return submitReasonsToCancelPageExpectingAbandonedPage();
    }

    public static ReasonsToCancelPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle) {
        return MotTestPage.navigateHereFromLoginPage(driver, login, vehicle).clickCancelMotTest();
    }

    public boolean isReasonDangerousOrCauseDamageDisplayed() {
        return isElementDisplayed(reasonDangerousOrCauseDamage);
    }

    public boolean isErrorMessageDisplayed() {
        return ValidationSummary.isValidationSummaryDisplayed(driver);
    }
}
