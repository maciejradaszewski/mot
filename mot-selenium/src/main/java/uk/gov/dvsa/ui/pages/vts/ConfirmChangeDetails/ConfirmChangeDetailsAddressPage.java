package uk.gov.dvsa.ui.pages.vts.ConfirmChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class ConfirmChangeDetailsAddressPage extends ConfirmDetailsPage {
    public static final String path = "/vehicle-testing-station/%s/address/review";
    public static final String PAGE_TITLE = "Review address";

    @FindBy(id = "address") private WebElement tableAddressElementValue;

    public ConfirmChangeDetailsAddressPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
        selfVerify();
    }

    public String getAddress() {
        return tableAddressElementValue.getText();
    }
}
