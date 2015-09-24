package uk.gov.dvsa.ui.pages.events;

import org.joda.time.DateTime;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class CreateNewEventPage extends Page {

    private static final String PAGE_TITLE = "Record an event";

    @FindBy(id = "eventType") private WebElement eventType;

    @FindBy(id = "date1-day") private WebElement day;

    @FindBy(id = "date1-month") private WebElement month;

    @FindBy(id = "date1-year") private WebElement year;

    @FindBy(id = "continue") private WebElement continueButton;


    public CreateNewEventPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    private CreateNewEventPage enterDateOfEvent(DateTime dateTime) {
        FormCompletionHelper.enterText(day, dateTime.toString("dd"));
        FormCompletionHelper.enterText(month, dateTime.toString("MM"));
        FormCompletionHelper.enterText(year, dateTime.toString("YYYY"));
        return this;
    }

    public CreateNewEventPageTwo submitNewEvent() {
        FormCompletionHelper.selectFromDropDownByValue(eventType, "VT6");
        enterDateOfEvent(DateTime.now());
        continueButton.click();
        return new CreateNewEventPageTwo(driver);
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }
}
