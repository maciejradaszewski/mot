package uk.gov.dvsa.ui.pages.vts;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ConfirmSiteDetailsPage extends Page {
    public static final String path = "/vehicle-testing-station/%s/site-details/confirmation";
    private static final String PAGE_TITLE = "Confirm site details";

    @FindBy(id = "submitSiteDetailsUpdate") private WebElement submitButton;

    public ConfirmSiteDetailsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public  VehicleTestingStationPage clickSubmitButton() {
        submitButton.click();
        return new VehicleTestingStationPage(driver);
    }
}