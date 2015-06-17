package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class ChoosePaymentTypePage extends BasePage {

    private static final String PAGE_TITLE = "BUY TEST SLOTS";

    @FindBy(id = "inputPaymentType") private WebElement chequePaymentMethod;

    @FindBy(id = "startOrder") private WebElement startOrder;

    public ChoosePaymentTypePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public ChoosePaymentTypePage selectChequePaymentType() {
        chequePaymentMethod.click();
        return new ChoosePaymentTypePage(driver);
    }

    public EnterChequeDetailsPage clickStartOrder() {
        startOrder.click();
        return new EnterChequeDetailsPage(driver);
    }

}
