package uk.gov.dvsa.ui.pages.vehicleinformation;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class VehicleInformationResultsPage extends Page {

    private static final String PAGE_TITLE = "Vehicle Search Results";

    @FindBy (linkText = "Details") private WebElement vehicleDetailsLink;

    public VehicleInformationResultsPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    public boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(getTitle(), PAGE_TITLE);
    }

    public VehicleInformationPage clickVehicleDetailsLink() {
        vehicleDetailsLink.click();
        return new VehicleInformationPage(driver);
    }
}
