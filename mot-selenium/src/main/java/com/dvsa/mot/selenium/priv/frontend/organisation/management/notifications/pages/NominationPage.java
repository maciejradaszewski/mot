package com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class NominationPage extends BasePage{

    private String PAGE_TITLE = "NOTIFICATIONS\nTESTER NOMINATION";

    @FindBy(id = "notification-decision") private WebElement roleAssignedSuccessfullyMessage;

    @FindBy(id = "notification-decision-datetime") private WebElement roleAssignedDateAndTimeMessage;

    @FindBy(id = "action-site-nomination-accepted") private WebElement acceptNomination;

    @FindBy(id = "action-site-nomination-rejected") private WebElement rejectNomination;

    @FindBy(id = "link-back-to-home") private WebElement clickBackToHomeLink;

    public NominationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public NominationPage clickAcceptNomination() {
        acceptNomination.click();
        return this;
    }

    public UserDashboardPage clickBackToHomeLink() {
        clickBackToHomeLink.click();
        return new UserDashboardPage(driver);
    }


}
