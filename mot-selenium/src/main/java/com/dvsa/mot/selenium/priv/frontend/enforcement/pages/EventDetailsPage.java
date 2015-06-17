package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class EventDetailsPage extends BasePage {

    public static final String PAGE_TITLE = "FULL DETAILS OF PERSON EVENT SELECTED FOR";

    @FindBy(id = "event-type") private WebElement eventType;

    @FindBy(id = "description") private WebElement description;

    public EventDetailsPage(WebDriver driver, String userName, String namesAndSurname){
        super(driver);
        checkTitle(PAGE_TITLE + "\n" + userName.toUpperCase() + " - " + namesAndSurname.toUpperCase());
    }

    public String getEventType(){ return eventType.getText();}

    public String getDescription(){return description.getText();}
}
