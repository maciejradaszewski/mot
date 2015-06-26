package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class EventsHistoryPage extends Page {
    public static final String path = "/event/list/site/%s";
    private static final String PAGE_TITLE = "Events History";

    @FindBy(id = "search") private WebElement searchInput;

    @FindBy(id = "listLogs_wrapper") private WebElement listOfEventsTable;

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
}
