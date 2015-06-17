package com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class NotificationAcceptedPage extends BasePage {

    private String PAGE_TITLE = "NOTIFICATIONS\nNOMINATION ACCEPTED";

    @FindBy(id = "notification-content") private WebElement nominationAcceptedMessage;

    @FindBy(className = "text") private WebElement notificationSentDetailsMessage;

    @FindBy(id = "link-back-to-home") private WebElement backToHomeLink;

    public NotificationAcceptedPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage clickBackToHomeLink() {
        backToHomeLink.click();
        return new UserDashboardPage(driver);
    }

}
