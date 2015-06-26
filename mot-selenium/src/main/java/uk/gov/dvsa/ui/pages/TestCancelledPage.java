package uk.gov.dvsa.ui.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.PageFactory;
import uk.gov.dvsa.framework.config.webdriver.MotAppDriver;
import uk.gov.dvsa.helper.PageInteractionHelper;

import java.util.List;

public abstract class TestCancelledPage extends Page {
    By vtsMessage = By.cssSelector(".col-lg-6>ul>li");

    private String page_title = "";

    public TestCancelledPage(MotAppDriver driver, String title) {
        super(driver);
        page_title = title;
        selfVerify();
    }

    @Override
    protected boolean selfVerify() {
        return PageInteractionHelper.verifyTitle(this.getTitle(), page_title);
    }


    public boolean isVT30messageDisplayed() {
        List<WebElement> vtsMessageList = driver.findElements(vtsMessage);
        for(WebElement e: vtsMessageList){
            if(e.getText().equals("The VT30 has been generated")){
                return true;
            }
        }
        return false;
    }
}
