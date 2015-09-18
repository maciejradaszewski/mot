package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.helpdesk.HelpDeskUserProfilePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class EventHistoryPage extends BasePage {

    public static final String PAGE_TITLE = "EVENTS HISTORY";

    @FindBy(partialLinkText = "Return to the ") private WebElement goBackLink;

    @FindBy(xpath = ".//*[@id='listLogs']/tbody/tr[1]/td[1]/a") private WebElement eventOne;

    @FindBy(xpath = ".//*[@id='listLogs']/thead/tr/th[1]") private WebElement typeColumnHeader;

    @FindBy(xpath = ".//*[@id='listLogs']/thead/tr/th[2]") private WebElement dateColumnHeader;

    @FindBy(xpath = ".//*[@id='listLogs']/thead/tr/th[3]") private WebElement descColumnHeader;

    @FindBy(xpath = ".//*[@id='event-type']") private WebElement eventType;

    @FindBy(xpath = ".//*[@id='event-date']") private WebElement eventDate;

    @FindBy(xpath = ".//*[@id='description']") private WebElement eventDesc;

    @FindBy(xpath = "html/body/div[3]/div[1]/div/h1/small") private WebElement fullDetails;

    @FindBy(id = "listLogs") private WebElement eventHistoryTable;

    @FindBy(id = "listLogs_info") private WebElement eventsTopHeader;

    @FindBy(css = ".odd>td>a") private WebElement eventTypeData;

    @FindBy(css = ".sorting_1") private WebElement eventDateData;

    @FindBy(partialLinkText = "User Claims Account") private WebElement userClaimsAccount;

    @FindBy(xpath = ".//*[@class=' longer-truncate']") private WebElement description;

    public EventHistoryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public EnforcementAeEventDetailsPage viewEventDetails() {
        eventOne.click();
        return new EnforcementAeEventDetailsPage(driver);
    }

    public String getTypeColumnHeader() {
        return typeColumnHeader.getText();
    }

    public String getDateColumnHeader() {
        return dateColumnHeader.getText();
    }

    public String getDescColumnHeader() {
        return descColumnHeader.getText();
    }

    public boolean verifyEventDetailsTableIsDisplayed() {
        WebDriverWait webDriverWait = new WebDriverWait(driver, AJAX_MAXIMUM_TIMEOUT);

        webDriverWait.until(ExpectedConditions.presenceOfElementLocated(By.id("listLogs_next")));
        return eventHistoryTable.isDisplayed();
    }

    public boolean checkTableExists() {
        return isElementDisplayed(eventHistoryTable);
    }

    public String getEventType() {

        return eventTypeData.getText();
    }

    public String getEventDate() {

        return eventDateData.getText();
    }

    public HelpDeskUserProfilePage clickGoBackLink(){
        goBackLink.click();
        return new HelpDeskUserProfilePage(driver);
    }

    public String getEventsTopHeaderText() {
        return eventsTopHeader.getText();
    }

    public EventDetailsPage clickUserClaimsAccount(){
        WebDriverWait webDriverWait = new WebDriverWait(driver, AJAX_MAXIMUM_TIMEOUT);

        webDriverWait.until(ExpectedConditions.presenceOfElementLocated(By.id("listLogs_next")));
        webDriverWait.until(ExpectedConditions
                .presenceOfElementLocated(By.partialLinkText("User Claims Account")));

                userClaimsAccount.click();
        return new EventDetailsPage(driver);
    }

    public String getDescription(){
        return description.getText();
    }
}
