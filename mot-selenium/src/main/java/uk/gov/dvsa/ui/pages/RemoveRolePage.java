package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

public class RemoveRolePage extends Page {
    private static final String PAGE_TITLE = "Remove a role";
    @FindBy(id = "confirm") private WebElement removeARoleConfirmation;

    public RemoveRolePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }

    public VehicleTestingStationPage confirmRemoveRole() {
        removeARoleConfirmation.click();
        return new VehicleTestingStationPage(driver);
    }
}
