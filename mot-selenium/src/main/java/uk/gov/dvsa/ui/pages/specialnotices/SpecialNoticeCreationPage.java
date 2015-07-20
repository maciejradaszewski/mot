package uk.gov.dvsa.ui.pages.specialnotices;

import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import org.openqa.selenium.By;
import org.openqa.selenium.Dimension;
import org.openqa.selenium.Point;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import java.util.List;

public class SpecialNoticeCreationPage extends Page{

    public static final String PATH = "/special-notices/create";
    private static final String PAGE_TITLE = "Create Special Notice";

    @FindBy(id = "notice-title-input") private WebElement subjectTitleInput;

    @FindBy(name = "internalPublishDate[day]") private WebElement internalDateDayInput;
    @FindBy(name = "internalPublishDate[month]") private WebElement internalDateMonthInput;
    @FindBy(name = "internalPublishDate[year]") private WebElement internalDateYearInput;

    @FindBy(name = "externalPublishDate[day]") private WebElement externalDateDayInput;
    @FindBy(name = "externalPublishDate[month]") private WebElement externalDateMonthInput;
    @FindBy(name = "externalPublishDate[year]") private WebElement externalDateYearInput;

    @FindBy(css = "input[value='TESTER-CLASS-1']") private WebElement vehicleClass1CheckBox;
    @FindBy(css = "input[value='TESTER-CLASS-4']") private WebElement vehicleClass4CheckBox;
    @FindBy(css = "input[value='DVSA']") private WebElement dvsaRolesCheckBox;
    @FindBy(css = "input[value='VTS']") private WebElement vtsRolesCheckbox;

    @FindBy(id = "acknowledgement-input") private WebElement acknowledgementPeriodInput;

    @FindBy(id = "notice-text-input") private WebElement noticeTextInput;
    @FindBy(id = "preview-special-notice") private WebElement previewSpecialNoticeButton;

    public SpecialNoticeCreationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    private SpecialNoticeCreationPage enterInternalPublishDate(DateTime dateTime) {
        FormCompletionHelper.enterText(internalDateDayInput, dateTime.toString("dd"));
        FormCompletionHelper.enterText(internalDateMonthInput, dateTime.toString("MM"));
        FormCompletionHelper.enterText(internalDateYearInput, dateTime.toString("YYYY"));
        return this;
    }
    
    private SpecialNoticeCreationPage enterExternalPublishDate(DateTime dateTime) {
        FormCompletionHelper.enterText(externalDateDayInput, dateTime.toString("dd"));
        FormCompletionHelper.enterText(externalDateMonthInput, dateTime.toString("MM"));
        FormCompletionHelper.enterText(externalDateYearInput, dateTime.toString("YYYY"));
        return this;
    }

    public SpecialNoticePreviewPage createSpecialNoticeSuccessfully(String specialNoticeTitle) {
        FormCompletionHelper.enterText(subjectTitleInput, specialNoticeTitle);
        enterExternalPublishDate(DateTime.now());
        enterInternalPublishDate(DateTime.now());
        FormCompletionHelper.enterText(acknowledgementPeriodInput, "12");
        FormCompletionHelper.selectInputBox(vehicleClass1CheckBox);
        FormCompletionHelper.selectInputBox(vehicleClass4CheckBox);
        FormCompletionHelper.selectInputBox(dvsaRolesCheckBox);
        FormCompletionHelper.selectInputBox(vtsRolesCheckbox);
        FormCompletionHelper.enterText(noticeTextInput, "#Testing the Future \n *Mark down*");
        previewSpecialNoticeButton.click();
        return new SpecialNoticePreviewPage(driver);
    }

}
