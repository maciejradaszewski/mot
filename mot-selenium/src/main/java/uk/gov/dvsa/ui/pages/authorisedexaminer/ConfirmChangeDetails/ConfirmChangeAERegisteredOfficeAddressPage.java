package uk.gov.dvsa.ui.pages.authorisedexaminer.ConfirmChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class ConfirmChangeAERegisteredOfficeAddressPage extends ConfirmAEDetailsPage {
    public static final String path = "/authorised-examiner/%s/registered-address/review";
    public static final String PAGE_TITLE = "Review registered address";

    @FindBy(id = "address") private WebElement tableAddressElementValue;

    public ConfirmChangeAERegisteredOfficeAddressPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
        selfVerify();
    }

    public String getAddress() {
        return tableAddressElementValue.getText();
    }
}
