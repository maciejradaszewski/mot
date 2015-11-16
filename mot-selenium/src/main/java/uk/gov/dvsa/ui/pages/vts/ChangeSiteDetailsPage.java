package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.ui.Select;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ChangeSiteDetailsPage extends Page {
    public static final String path = "/vehicle-testing-station/%s/site-details";
    private static final String PAGE_TITLE = "Vehicle testing station";

    @FindBy(id = "status") private WebElement statusSelect;
    @FindBy(id = "submitSiteDetailsUpdate") private WebElement submitButton;

    public ChangeSiteDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public ChangeSiteDetailsPage changeSiteStatus(String newStatus) {
        FormCompletionHelper.selectFromDropDownByVisibleText(statusSelect, newStatus);
        return this;
    }

    public ConfirmSiteDetailsPage clickSubmitButton() {
        submitButton.click();
        return new ConfirmSiteDetailsPage(driver);
    }
}
