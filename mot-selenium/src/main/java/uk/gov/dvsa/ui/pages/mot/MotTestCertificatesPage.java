package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class MotTestCertificatesPage extends Page {

    public static final String path = "/mot-test-certificates";
    private static final String PAGE_TITLE = "MOT testing\n" +
            "MOT test certificates";
    private int waitingPeriodInSeconds = 30;
    private int refreshEveryTimeout = 5;
    private String emailLinkText = "Email";
    private WebElement emailButton;
    @FindBy (className = "key-value-list") private WebElement recentCertificatesTable;
    @FindBy (className = "data-paging__link") private WebElement paginationButton;

    public MotTestCertificatesPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public boolean isRecentCertificatesTableDisplayed() {
        return recentCertificatesTable.isDisplayed();
    }

    public EmailCertificateFormPage gotoMotTestEmailCertificateFormPage() {
        emailButton = PageInteractionHelper.refreshPageUntilElementWontBeVisible(
                By.linkText(emailLinkText), waitingPeriodInSeconds, refreshEveryTimeout);
        emailButton.click();
        return new EmailCertificateFormPage(driver);
    }

    public boolean isPaginationButtonVisible() {
        return PageInteractionHelper.isElementDisplayed(paginationButton);
    }
}