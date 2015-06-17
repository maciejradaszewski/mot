package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AdjustmentConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "MANUAL ADJUSTMENT OF SLOTS";

    @FindBy(id = "statusMessage") private WebElement statusMessage;

    @FindBy(id = "successMessage") private WebElement balanceMessage;

    public AdjustmentConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getManualAdjustmentStatusMessage() {
        return statusMessage.getText();
    }

    public String getAdjustedBalanceMessage() {
        return balanceMessage.getText();
    }

}
