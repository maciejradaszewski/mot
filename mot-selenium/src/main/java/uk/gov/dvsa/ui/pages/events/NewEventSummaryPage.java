package uk.gov.dvsa.ui.pages.events;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class NewEventSummaryPage extends Page {

    private static final String PAGE_TITLE = "Event summary";

    @FindBy(id = "continue") private WebElement recordNewEventLink;
    @FindBy(id = "validation-message--success") private WebElement validationMessage;

    public NewEventSummaryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    public EventsHistoryPage clickRecordNewEvent() {
        recordNewEventLink.click();
        return new EventsHistoryPage(driver);
    }

    public boolean isEventRecordedSuccessfully() {
        return validationMessage.isDisplayed();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }
}
