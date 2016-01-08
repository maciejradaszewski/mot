package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class SummaryAndConfirmationPage extends Page {

    private static final String PAGE_TITLE = "Vehicle Testing Station\n" +
            "Summary and confirmation";

    @FindBy(id = "confirm-role" ) private WebElement confirmButton;

    public SummaryAndConfirmationPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public SummaryAndConfirmationPage clickConfirmButton() {
        confirmButton.click();

        return this;
    }
}
