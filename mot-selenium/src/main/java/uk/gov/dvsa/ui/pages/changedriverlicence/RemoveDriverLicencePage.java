package uk.gov.dvsa.ui.pages.changedriverlicence;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class RemoveDriverLicencePage extends Page {

    private static final String PAGE_TITLE = "Remove driving licence";
    @FindBy(linkText = "Remove driving licence") private WebElement removeDrivingLicenceButton;
    @FindBy(linkText = "Back") private WebElement backLink;
    @FindBy(className = "message--important") private WebElement warningMessage;

    public RemoveDriverLicencePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public void clickBackLink() {
        backLink.click();
    }

    public void clickRemoveDrivingLicenceButton() {
        removeDrivingLicenceButton.click();
    }

    public String getWarningMessage() {
        return warningMessage.getText();
    }
}
