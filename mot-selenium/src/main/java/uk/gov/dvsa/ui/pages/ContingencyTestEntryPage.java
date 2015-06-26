package uk.gov.dvsa.ui.pages;

import org.joda.time.DateTime;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class ContingencyTestEntryPage extends Page {

    public static final String path = "/contingency";
    private static final String PAGE_TITLE = "Contingency Test Entry";

    @FindBy(id = "ct-code") private WebElement contingencyCodeInput;
    @FindBy(id = "radio-test-type-group-labelnormal-test") private WebElement contingencyTestTypeNormalTest;
    @FindBy(id = "radioOptionRadio-reason-groupCP") private WebElement reasonForContingencyTestCommunicationProblems;
    @FindBy(id = "confirm_ct_button") private WebElement confirmContingencyTestDetailsButton;
    @FindBy(id = "dateTestDay") private WebElement dateTestDay;
    @FindBy(id = "dateTestMonth") private WebElement dateTestMonth;
    @FindBy(id = "dateTestYear") private WebElement dateTestYear;

    public ContingencyTestEntryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    private ContingencyTestEntryPage fillContingencyCode(String code) {
        contingencyCodeInput.sendKeys(code);

        return this;
    }

    private ContingencyTestEntryPage selectContingencyTestTypeNormalTest() {
        contingencyTestTypeNormalTest.click();

        return this;
    }

    private ContingencyTestEntryPage selectReasonForContingencyTestCommunicationProblems() {
        reasonForContingencyTestCommunicationProblems.click();

        return this;
    }

    private ContingencyTestEntryPage clickConfirmContingencyTestDetailsButton() {
        confirmContingencyTestDetailsButton.click();

        return this;
    }

    private ContingencyTestEntryPage fillDateContingencyTestPerformed(DateTime date) {
        dateTestDay.sendKeys(date.dayOfMonth().getAsShortText());
        dateTestMonth.sendKeys(String.valueOf(date.getMonthOfYear()));
        dateTestYear.sendKeys(date.year().getAsShortText());
        return this;
    }

    public VehicleSearchPage fillContingencyTestFormAndConfirm(String code, DateTime date) {
        fillContingencyCode(code)
                .selectContingencyTestTypeNormalTest()
                .selectReasonForContingencyTestCommunicationProblems()
                .fillDateContingencyTestPerformed(date)
                .clickConfirmContingencyTestDetailsButton();

        return new VehicleSearchPage(driver);
    }
}
