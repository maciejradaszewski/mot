package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.events.CreateNewEventPage;

public class EventsHistoryPage extends Page {
    public static final String PATH = "/event/list/site/%s";
    public static final String AE_PATH = "/event/list/ae/%s";
    private static final String PAGE_TITLE = "Events history";

    @FindBy(id = "search") private WebElement searchInput;

    @FindBy(id = "listLogs_wrapper") private WebElement listOfEventsTable;

    @FindBy(partialLinkText = "Record an event") private WebElement recordNewEventLink;

    public EventsHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public EventsHistoryPage fillSearchInput(String searchString) {
        searchInput.sendKeys(searchString);
        return this;
    }

    public boolean isListOfEventsDisplayed() {
        return listOfEventsTable.isDisplayed();
    }

    public CreateNewEventPage clickRecordNewEvent() {
        recordNewEventLink.click();
        return new CreateNewEventPage(driver);
    }
}
