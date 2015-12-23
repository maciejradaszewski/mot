package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.events.CreateNewEventPage;

import java.util.List;

public class EventsHistoryPage extends Page {
    public static final String PATH = "/event/list/site/%s";
    public static final String AE_PATH = "/event/list/ae/%s";
    private static final String PAGE_TITLE = "Events history";

    @FindBy(id = "search") private WebElement searchInput;

    @FindBy(id = "listLogs_wrapper") private WebElement listOfEventsTable;

    @FindBy(partialLinkText = "Record an event") private WebElement recordNewEventLink;

    @FindBy(name = "submit") private WebElement applyButton;

    private static final By EVENT_TYPE = By.xpath("//*[@id='listLogs']/tbody/tr/td[1]/a");

    private static final By EVENT_DATE = By.xpath("//*[@id='listLogs']/tbody/tr/td[2]");

    private static final By EVENT_DESCRIPTION = By.xpath("//*[@id='listLogs']/tbody/tr/td[3]");

    private WebElement getEventTypeElement() {
        return driver.findElement(EVENT_TYPE);
    }

    private WebElement getEventDateElement() {
        return driver.findElement(EVENT_DATE);
    }

    private WebElement getEventDescriptionElement() {
        return driver.findElement(EVENT_DESCRIPTION);
    }

    public EventsHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public EventsHistoryPage fillSearchInput(String searchString) {
        searchInput.sendKeys(searchString);
        return this;
    }

    public boolean isListOfEventsDisplayed() {
        return listOfEventsTable.isDisplayed();
    }

    public int getEventHistoryTableSize(){
        return driver.findElements(By.cssSelector("#listLogs tbody>tr")).size();
    }

    public CreateNewEventPage clickRecordNewEvent() {
        recordNewEventLink.click();
        return new CreateNewEventPage(driver);
    }

    public EventsHistoryPage clickApplyButton() {
        applyButton.click();
        return this;
    }

    public String getEventTypeText() {
        return getEventTypeElement().getText();
    }

    public String getEventDateText() {
        return getEventDateElement().getText();
    }

    public String getEventDescriptionText() {
        return getEventDescriptionElement().getText();
    }

}
