package uk.gov.dvsa.ui.pages.mot.duplicatereplacementcertificates;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class DuplicateReplacementCertificateTestHistoryPage extends Page {

    private static final String PAGE_TITLE = "Duplicate or replacement certificate";

    @FindBy(xpath = "((//*[@class='row'])[contains(.,'Pass')]//button[text()='Edit'])[1]")
    private WebElement editOnPass;
    @FindBy(xpath = "((//*[@class='row'])[contains(.,'Fail')]//button[text()='Edit'])[1]")
    private WebElement editOnFail;
    @FindBy(id = "return_to_replacement_search") private WebElement returnButton;
    @FindBy(className = "validation-message") private WebElement validationMessage;

    public DuplicateReplacementCertificateTestHistoryPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ReplacementCertificateUpdatePage clickFirstEditButton() {
        WebElement editButton =
                driver.findElement(By.xpath("((//*[@class='row'])//button[text()='Edit'])[1]"));
        editButton.click();
        return new ReplacementCertificateUpdatePage(driver);
    }

}
