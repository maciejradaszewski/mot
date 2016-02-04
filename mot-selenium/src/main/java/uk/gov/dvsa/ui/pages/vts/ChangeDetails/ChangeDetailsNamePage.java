package uk.gov.dvsa.ui.pages.vts.ChangeDetails;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.FormCompletionHelper;

public class ChangeDetailsNamePage extends ChangeDetailsPage {
    public static final String PATH = "/vehicle-testing-station/%s/name/change";
    public static final String PAGE_TITLE = "Change site name";

    @FindBy(id = "vtsNameTextBox") private WebElement siteName;

    public ChangeDetailsNamePage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

    public ChangeDetailsNamePage inputSiteDetailsName(String name) {
        FormCompletionHelper.enterText(siteName, name);
        return this;
    }
}
