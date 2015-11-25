package uk.gov.dvsa.ui.pages.mot;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class RefusedTestPage extends Page {

    @FindBy(id = "reprint-certificate") private WebElement printDocuments;
    @FindBy(id = "refusal") public WebElement refuse;
    @FindBy(id = "confirm_vehicle_confirmation") public WebElement confirmRefusal;
    @FindBy(xpath = "/html/body/div[3]/div/div/div[1]/div/h1") public WebElement refusalMsg;

    private static final String PAGE_TITLE = "MOT test refused";
    public static final String PATH = "/refuse-to-test/%s/summary";

    public RefusedTestPage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }
}