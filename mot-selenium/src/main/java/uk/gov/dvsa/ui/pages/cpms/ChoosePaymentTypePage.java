package uk.gov.dvsa.ui.pages.cpms;

import java.util.List;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;
import uk.gov.dvsa.ui.pages.Page;

public class ChoosePaymentTypePage extends Page {
    private static final String PAGE_TITLE = "Buy test slots";
    
    @FindBy(id = "startOrder") private WebElement startOrderButton;

    public ChoosePaymentTypePage(MotAppDriver driver) {
        super(driver);
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), PAGE_TITLE);
    }
    
    public BuyTestSlotsPage selectCardPaymentTypeAndSubmit() {
        List<WebElement> radios = driver.findElements(By.name("paymentType"));
        for (WebElement radio : radios) {
            if (radio.getAttribute("value").equals("card"))
                (radio).click();
        }
        startOrderButton.click();
        return new BuyTestSlotsPage(driver);
    }
}