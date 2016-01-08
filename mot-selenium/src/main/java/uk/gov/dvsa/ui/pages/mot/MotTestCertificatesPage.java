package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

import java.util.List;

public class MotTestCertificatesPage extends Page {

    public static final String PATH = "/mot-test-certificates";
    public static final String PAGE_TITLE = "MOT testing";
    public static final String PAGE_HEADER = "MOT test certificates";
    private int waitingPeriodInSeconds = 30;
    private int refreshEveryTimeout = 5;
    private String emailLinkText = "Email";
    private WebElement emailButton;
    @FindBy (className = "key-value-list") private WebElement recentCertificatesTable;
    @FindBy (className = "data-paging__prev") private WebElement prevPaginationButton;
    @FindBy (className = "data-paging__next") private WebElement nextPaginationButton;

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

    public boolean isPaginationButtonNextVisible() {
        return PageInteractionHelper.isElementDisplayed(nextPaginationButton);
    }

    public boolean isPaginationButtonPrevVisible() {
        return PageInteractionHelper.isElementDisplayed(prevPaginationButton);
    }

    public boolean isVehicleCorrect(String makeModel, String registration, String testResult) {
        List<WebElement> listOfElementsInTable = recentCertificatesTable.findElements(By.tagName("td"));

        return ((listOfElementsInTable.get(0).getText().equals(makeModel)) &&
            (listOfElementsInTable.get(1).getText().equals(registration)) &&
            (listOfElementsInTable.get(2).getText().equals(testResult)));
    }

    public MotTestCertificatesPage clickOnPrevPaginationButton() {
        prevPaginationButton.findElement(By.className("data-paging__link")).click();
        return new MotTestCertificatesPage(driver);
    }

    public MotTestCertificatesPage clickOnNextPaginationButton() {
        nextPaginationButton.findElement(By.className("data-paging__link")).click();
        return new MotTestCertificatesPage(driver);
    }
}