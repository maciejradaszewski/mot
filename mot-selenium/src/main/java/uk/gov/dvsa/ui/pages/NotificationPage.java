package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

public class NotificationPage extends Page {

    public static final String path = "/notification/%s";
    private static final String PAGE_TITLE = "Notifications";
    private static final String NOTIFICATION_STATUS_ACCEPTED = "Nomination accepted";

    @FindBy(id = "action-site-nomination-accepted") private WebElement acceptButton;

    @FindBy(id = "action-site-nomination-rejected") private WebElement rejectButton;

    private static final By NOMINATION_STATUS = By.xpath(".//h1/span[2]");

    private WebElement getNotificationStatusElement() {
        return driver.findElement(NOMINATION_STATUS);
    }

    private MotAppDriver driver;

    public NotificationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
        this.driver = driver;
        PageFactory.initElements(driver, this);
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public NotificationPage clickAcceptButton() {
        acceptButton.click();

        return this;
    }

    public NotificationPage clickRejectButton() {
        rejectButton.click();

        return this;
    }

    private String getNotificationStatusText() {
        return getNotificationStatusElement().getText();
    }

    public boolean isNotificationStatusAccepted() {
        return getNotificationStatusText().contains(NOTIFICATION_STATUS_ACCEPTED);
    }
}
