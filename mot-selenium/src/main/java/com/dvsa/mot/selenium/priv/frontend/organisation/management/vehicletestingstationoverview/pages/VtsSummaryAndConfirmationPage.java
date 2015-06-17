package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class VtsSummaryAndConfirmationPage extends BasePage{

    private String PAGE_TITLE = "VEHICLE TESTING STATION\nREMOVE A ROLE";

    @FindBy(id = "notification-sent") private WebElement notificationSentMessage;

    @FindBy(id = "confirm") private WebElement confirmRoleRemoval;

    @FindBy(id = "cancel-and-return-to-garage") private WebElement cancelAndReturnToGarageLink;

    public VtsSummaryAndConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String viewNotificationSentMessage() {
        return notificationSentMessage.getText();
    }

    public SiteDetailsPage clickConfirmRoleRemoval() {
        confirmRoleRemoval.click();
        return new SiteDetailsPage(driver);
    }

    public SiteDetailsPage clickCancelAndReturnToGarageLink() {
        cancelAndReturnToGarageLink.click();
        return new SiteDetailsPage(driver);
    }

}
