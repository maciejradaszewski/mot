package com.dvsa.mot.selenium.priv.frontend.helpdesk;


import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class HelpDeskResetAccountConfirmationPage extends BasePage {

    private final String PAGE_TITLE = "USER PROFILE\nRECLAIM ACCOUNT";

    @FindBy(id = "reclaim-account-submit") private WebElement reclaimAccountButton;

    @FindBy(id = "navigation-link-") private WebElement cancelAndReturnToUserProfileLink;

    @FindBy(xpath = ".//*[@id='reclaim-account-notification']/p") private WebElement
            accountReclaimNotification;

    @FindBy(id = "validation-message--important") private WebElement reclaimAccountNotification;

    public HelpDeskResetAccountConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getAccountReclaimNotificationMessage() {

        return reclaimAccountNotification.getText().trim();
    }

    public HelpDeskUserProfilePage clickReclaimAccountButton() {
        reclaimAccountButton.click();
        return new HelpDeskUserProfilePage(driver);
    }

    public HelpDeskUserProfilePage clickCancelAndReturnToUserProfileLink() {
        cancelAndReturnToUserProfileLink.click();
        return new HelpDeskUserProfilePage(driver);
    }

    public String getAccountReclaimConfirmationMessage() {

        return accountReclaimNotification.getText().trim();
    }


}
