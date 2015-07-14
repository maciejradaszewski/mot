package uk.gov.dvsa.ui.pages.mot;

import com.dvsa.mot.selenium.datasource.DateRange;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public abstract class TestLogPage extends Page {
    private String page_title = "";

    @FindBy(id = "todayCount") private WebElement todayCount;
    @FindBy(id = "last-week-count") private WebElement lastWeekCount;
    @FindBy(id = "last-month-count") private WebElement lastMonthCount;
    @FindBy(id = "last-year-count") private WebElement lastYearCount;
    @FindBy(id = "validation-message--failure") private WebElement validationMessage;
    @FindBy(id="dateFrom-validation-message") private WebElement validationMessageForDate;
    @FindBy(id="dateFrom-Day") private WebElement day;
    @FindBy(id="dateFrom-Month")private WebElement month;
    @FindBy(id="dateFrom-Year") private WebElement year;
    @FindBy(id="btn_search")private WebElement updateResultBtn;

    public TestLogPage(MotAppDriver driver, String title) {
        super(driver);
        page_title = title;
        selfVerify();
    }

    @Override protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), page_title);
    }

    public String getTodayCount() {
        return todayCount.getText();
    }

    public String getLastWeekCount() {
        return lastWeekCount.getText();
    }

    public String getLastMonthCount() {
        return lastMonthCount.getText();
    }

    public String getLastYearCount() {
        return lastYearCount.getText();
    }

    public boolean getNoRecordsFoundValidaitonMessage() {
        return (validationMessage.isDisplayed());
    }

    public boolean getInvalidDateMessage() {
        submitInvalidDate();
        return (validationMessageForDate.isDisplayed());
    }

    public void submitInvalidDate(){
        day.sendKeys(DateRange.INVALID_DATE.startDay);
        month.sendKeys(DateRange.INVALID_DATE.startMonth);
        year.sendKeys(DateRange.INVALID_DATE.startYear);
        updateResultBtn.click();
    }
}
