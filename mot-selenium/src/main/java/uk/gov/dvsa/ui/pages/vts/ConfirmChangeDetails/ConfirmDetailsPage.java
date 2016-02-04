package uk.gov.dvsa.ui.pages.vts.ConfirmChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

public class ConfirmDetailsPage extends Page {
    private String pageTitle = "";

    @FindBy(id = "submitUpdate") private WebElement submitButton;

    public ConfirmDetailsPage(MotAppDriver driver, String pageTitle) {
        super(driver);
        this.pageTitle = pageTitle;
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), pageTitle);
    }

    public VehicleTestingStationPage clickSubmitButton() {
        submitButton.click();
        return new VehicleTestingStationPage(driver);
    }
}
