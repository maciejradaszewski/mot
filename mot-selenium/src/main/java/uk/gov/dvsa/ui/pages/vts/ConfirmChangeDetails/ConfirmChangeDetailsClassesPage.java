package uk.gov.dvsa.ui.pages.vts.ConfirmChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;

public class ConfirmChangeDetailsClassesPage extends ConfirmDetailsPage {
    public static final String path = "/vehicle-testing-station/%s/classes/review";
    public static final String PAGE_TITLE = "Review classes";

    @FindBy(id = "classes") private WebElement tableElementValue;

    public ConfirmChangeDetailsClassesPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
        selfVerify();
    }

    public String getClasses() {
        return tableElementValue.getText();
    }
}
