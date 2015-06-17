package com.dvsa.mot.selenium.priv.frontend.organisation.management.notifications.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AedNominationPage extends BasePage {

    private String PAGE_TITLE = "NOTIFICATIONS\nAUTHORISED EXAMINER DELEGATE NOMINATION";

    @FindBy(id = "notification-decision")
    private WebElement roleAssignedSuccessfullyMessage;

    @FindBy(id = "notification-decision-datetime")
    private WebElement roleAssignedDateAndTimeMessage;

    @FindBy(id = "action-organisation-nomination-accepted")
    private WebElement acceptNomination;

    @FindBy(id = "action-organisation-nomination-rejected")
    private WebElement rejectNomination;

    @FindBy(id = "link-back-to-home")
    private WebElement clickBackToHomeLink;

    public AedNominationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public AedNominationPage clickAcceptNomination() {
        acceptNomination.click();
        return this;
    }

    public UserDashboardPage clickBackToHomeLink() {
        clickBackToHomeLink.click();
        return new UserDashboardPage(driver);
    }

}

