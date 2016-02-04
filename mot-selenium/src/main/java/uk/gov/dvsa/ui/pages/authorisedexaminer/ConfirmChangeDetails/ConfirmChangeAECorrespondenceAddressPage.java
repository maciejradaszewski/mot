package uk.gov.dvsa.ui.pages.authorisedexaminer.ConfirmChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class ConfirmChangeAECorrespondenceAddressPage extends ConfirmAEDetailsPage {
    public static final String path = "/authorised-examiner/%s/correspondence-address/review";
    public static final String PAGE_TITLE = "Review correspondence address";

    @FindBy(id = "address") private WebElement tableAddressElementValue;

    public ConfirmChangeAECorrespondenceAddressPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
        selfVerify();
    }

    public String getAddress() {
        return tableAddressElementValue.getText();
    }
}
