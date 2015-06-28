package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import com.dvsa.mot.selenium.framework.BasePage;

public class AdjustmentSummaryPage extends BasePage {
    
    private static final String PAGE_TITLE = "ADJUSTMENT SUMMARY";
    
    @FindBy(id = "confirmOrder") private WebElement confirmAdjustment;

    public AdjustmentSummaryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }
    
    public ManualAdjustmentSuccessPage clickConfirmAdjustment() {
        confirmAdjustment.click();
        return new ManualAdjustmentSuccessPage(driver);
    }

}
