package com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class NotificationsRoleRemovalPage extends BasePage{

    private String PAGE_TITLE = "NOTIFICATIONS\nROLE REMOVAL";

    @FindBy(id = "notification-content") private WebElement roleRemovalNotificationMessage;

    @FindBy(id = "notification-sent") private WebElement roleRemovedDateAndTimeMessage;

    @FindBy(id = "link-back-to-home") private WebElement backToHomeLink;

    public NotificationsRoleRemovalPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getRemovalNotificationMessage() {
        return roleRemovalNotificationMessage.getText();
    }

    public String getRoleRemovedDateAndTimeMessage() {
        return roleRemovedDateAndTimeMessage.getText();
    }

}
