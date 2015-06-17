package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.SpecialNotice;
import com.dvsa.mot.selenium.datasource.enums.VehicleClasses;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class CreateSpecialNoticePage extends BasePage {
    public static final String PAGE_TITLE = "CREATE SPECIAL NOTICE";

    @FindBy(id = "returnDashboard") private WebElement returnButton;

    @FindBy(id = "notice-title-input") private WebElement noticeTitle;

    @FindBy(name = "internalPublishDate[day]") private WebElement internalPublishDay;

    @FindBy(name = "internalPublishDate[month]") private WebElement internalPublishMonth;

    @FindBy(name = "internalPublishDate[year]") private WebElement internalPublishYear;

    @FindBy(name = "externalPublishDate[day]") private WebElement externalPublishDay;

    @FindBy(name = "externalPublishDate[month]") private WebElement externalPublishMonth;

    @FindBy(name = "externalPublishDate[year]") private WebElement externalPublishYear;

    @FindBy(id = "acknowledgement-input") private WebElement acknowledgement;

    @FindBy(id = "test-classes-chk") private WebElement testClassesCheckbox;

    @FindBy(id = "all-test-classes-chk") private WebElement allTestClassesCheckbox;

    @FindBy(id = "target-roles-chk") private WebElement testClass1Checkbox;

    @FindBy(id = "test-class-2-chk") private WebElement testClass2Checkbox;

    @FindBy(id = "test-class-3-chk") private WebElement testClass3Checkbox;

    @FindBy(id = "test-class-4-chk") private WebElement testClass4Checkbox;

    @FindBy(id = "test-class-5-chk") private WebElement testClass5Checkbox;

    @FindBy(id = "test-class-7-chk") private WebElement testClass7Checkbox;

    @FindBy(id = "dvsa-roles-chk") private WebElement dvsaRolesCheckbox;

    @FindBy(id = "vts-roles-chk") private WebElement vtsRolesCheckbox;

    @FindBy(id = "notice-text-input") private WebElement noticeBody;

    @FindBy(id = "preview-special-notice") private WebElement previewButton;

    @FindBy(id = "validation-summary-id") private WebElement errorMessage;

    public CreateSpecialNoticePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static CreateSpecialNoticePage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return SpecialNoticesPage.navigateHereFromLoginPageAsDVSAUser(driver, login)
                .createSpecialNotice();
    }

    private boolean isElementMarkedInvalid(WebElement element) {
        try {
            return findElementMarkedInvalid().equals(element);
        } catch (Exception e) {
            return false;
        }
    }

    public CreateSpecialNoticePage enterTitle(String title) {
        noticeTitle.sendKeys(title);
        return this;
    }
    public CreateSpecialNoticePage title(int specialNotice) {
        String title1 = Integer.toString(specialNotice);
        noticeTitle.sendKeys(title1);
        return this;
    }

    public CreateSpecialNoticePage clearTitle() {
        noticeTitle.clear();
        return this;
    }

    public boolean isTitleMarkedInvalid() {
        return isElementMarkedInvalid(noticeTitle);
    }


    public CreateSpecialNoticePage enterInternalPublishDay(int day) {
        internalPublishDay.sendKeys(Integer.toString(day));
        return this;
    }

    public CreateSpecialNoticePage clearInternalPublishDay() {
        internalPublishDay.clear();
        return this;
    }

    public boolean isInternalPublishDayMarkedInvalid() {
        return isElementMarkedInvalid(internalPublishDay);
    }

    public CreateSpecialNoticePage enterInternalPublishMonth(int month) {
        internalPublishMonth.sendKeys(Integer.toString(month));
        return this;
    }

    public CreateSpecialNoticePage clearInternalPublishMonth() {
        internalPublishMonth.clear();
        return this;
    }

    public boolean isInternalPublishMonthMarkedInvalid() {
        return isElementMarkedInvalid(internalPublishMonth);
    }

    public CreateSpecialNoticePage enterInternalPublishYear(int year) {
        internalPublishYear.sendKeys(Integer.toString(year));
        return this;
    }

    public CreateSpecialNoticePage clearInternalPublishYear() {
        internalPublishYear.clear();
        return this;
    }

    public boolean isInternalPublishYearMarkedInvalid() {
        return isElementMarkedInvalid(internalPublishYear);
    }

    public CreateSpecialNoticePage enterExternalPublishDay(int day) {
        externalPublishDay.sendKeys(Integer.toString(day));
        return this;
    }

    public CreateSpecialNoticePage clearExternalPublishDay() {
        externalPublishDay.clear();
        return this;
    }

    public boolean isExternalPublishDayMarkedInvalid() {
        return isElementMarkedInvalid(externalPublishDay);
    }

    public CreateSpecialNoticePage enterExternalPublishMonth(int month) {
        externalPublishMonth.sendKeys(Integer.toString(month));
        return this;
    }

    public CreateSpecialNoticePage clearExternalPublishMonth() {
        externalPublishMonth.clear();
        return this;
    }

    public boolean isExternalPublishMonthMarkedInvalid() {
        return isElementMarkedInvalid(externalPublishMonth);
    }

    public CreateSpecialNoticePage enterExternalPublishYear(int year) {
        externalPublishYear.sendKeys(Integer.toString(year));
        return this;
    }

    public CreateSpecialNoticePage clearExternalPublishYear() {
        externalPublishYear.clear();
        return this;
    }

    public boolean isExternalPublishYearMarkedInvalid() {
        return isElementMarkedInvalid(externalPublishYear);
    }

    private CreateSpecialNoticePage check(WebElement checkbox) {
        if (!checkbox.isSelected())
            checkbox.click();
        return this;
    }

    private CreateSpecialNoticePage uncheck(WebElement checkbox) {
        if (checkbox.isSelected())
            checkbox.click();
        return this;
    }
    public boolean isAcknowledgePeriodInvalid() {
        return isElementMarkedInvalid(acknowledgement);
    }

    private CreateSpecialNoticePage selectAcknowledgementPeriod(String period) {
        acknowledgement.clear();
        acknowledgement.sendKeys(period);
        return this;
    }
    public CreateSpecialNoticePage enterAcknowledgePeriod(String period) {
        acknowledgement.sendKeys(period);
        return this;
    }

    private CreateSpecialNoticePage enterClass1Checkbox(boolean checked) {
        if (checked)
            check(testClass1Checkbox);
        else
            uncheck(testClass1Checkbox);
        return this;
    }

    private CreateSpecialNoticePage enterClass2Checkbox(boolean checked) {
        if (checked)
            check(testClass2Checkbox);
        else
            uncheck(testClass2Checkbox);
        return this;
    }

    private CreateSpecialNoticePage enterClass3Checkbox(boolean checked) {
        if (checked)
            check(testClass3Checkbox);
        else
            uncheck(testClass3Checkbox);
        return this;
    }

    private CreateSpecialNoticePage enterClass4Checkbox(boolean checked) {
        if (checked)
            check(testClass4Checkbox);
        else
            uncheck(testClass4Checkbox);
        return this;
    }

    private CreateSpecialNoticePage enterClass5Checkbox(boolean checked) {
        if (checked)
            check(testClass5Checkbox);
        else
            uncheck(testClass5Checkbox);
        return this;
    }

    private CreateSpecialNoticePage enterClass7Checkbox(boolean checked) {
        if (checked)
            check(testClass7Checkbox);
        else
            uncheck(testClass7Checkbox);
        return this;
    }

    public CreateSpecialNoticePage enterBody(String body) {
        noticeBody.sendKeys(body);
        return this;
    }

    public CreateSpecialNoticePage clearBody() {
        noticeBody.clear();
        return this;
    }

    public CreateSpecialNoticePage enterRecipients(VehicleClasses[] recipients) {
        for (VehicleClasses vehicleClass : recipients) {
            switch (vehicleClass) {
                case one:
                    enterClass1Checkbox(true);
                    break;
                case two:
                    enterClass2Checkbox(true);
                    break;
                case three:
                    enterClass3Checkbox(true);
                    break;
                case four:
                    enterClass4Checkbox(true);
                    break;
                case five:
                    enterClass5Checkbox(true);
                    break;
                case seven:
                    enterClass7Checkbox(true);
                    break;
                default:
                    break;
            }
        }
        return this;
    }

    public CreateSpecialNoticePage enterSpecialNotice(SpecialNotice specialNotice) {
        clearTitle();
        enterTitle(specialNotice.title);
        clearInternalPublishDay();
        enterInternalPublishDay(specialNotice.internalPublishDate.getDayOfMonth());
        clearInternalPublishMonth();
        enterInternalPublishMonth(specialNotice.internalPublishDate.getMonthOfYear());
        clearInternalPublishYear();
        enterInternalPublishYear(specialNotice.internalPublishDate.getYear());
        clearExternalPublishDay();
        enterExternalPublishDay(specialNotice.externalPublishDate.getDayOfMonth());
        clearExternalPublishMonth();
        enterExternalPublishMonth(specialNotice.externalPublishDate.getMonthOfYear());
        clearExternalPublishYear();
        enterExternalPublishYear(specialNotice.externalPublishDate.getYear());
        selectAcknowledgementPeriod(specialNotice.acknowledgementPeriod.getId());
        enterRecipients(specialNotice.recipients);
        enterBody(specialNotice.body);
        return this;
    }

    public SpecialNoticePreviewPage submit() {
        previewButton.click();
        return new SpecialNoticePreviewPage(driver);
    }

    public CreateSpecialNoticePage submitExpectingError() {
        previewButton.click();
        return new CreateSpecialNoticePage(driver);
    }

    public String getErrorMessage() {
        return errorMessage.getText();
    }

    public SpecialNoticesPage cancelButton() {
        returnButton.click();
        return new SpecialNoticesPage(driver);
    }

}
