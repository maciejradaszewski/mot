package uk.gov.dvsa.ui.pages.mot.retest;

import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.mot.OptionsPage;

public class ReTestOptionsPage extends OptionsPage {
    private static final String PAGE_TITLE = "retest started";

    public ReTestOptionsPage(MotAppDriver driver) {
        super(driver, PAGE_TITLE);
    }

    public void clickReturnHome(){
        clickReturnToHome();
    }
}
