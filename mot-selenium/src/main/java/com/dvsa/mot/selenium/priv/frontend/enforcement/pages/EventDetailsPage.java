package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class EventDetailsPage extends BasePage {

    public static final String PAGE_TITLE = "EVENTS\nEVENT DETAILS";

    public EventDetailsPage(WebDriver driver){
        super(driver);
        checkTitle(PAGE_TITLE);
    }
}
