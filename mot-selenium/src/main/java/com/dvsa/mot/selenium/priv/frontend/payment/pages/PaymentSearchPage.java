package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

public class PaymentSearchPage extends BasePage {

    private static final String PAGE_TITLE = "REFERENCE SEARCH";

    @FindBy(id = "inputReference") private WebElement inputReferenceField;

    @FindBy(id = "submitAeSearch") private WebElement paymentSearchButton;

    public PaymentSearchPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public static PaymentSearchPage navigateHereFromLoginPage(WebDriver driver, Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickPaymentsLink();
    }

    public PaymentSearchPage selectPaymentReference() {
        List<WebElement> radios = driver.findElements(By.name("type"));
        for (WebElement radio : radios) {
            if (radio.getAttribute("value").equals("1"))
                (radio).click();
        }
        return new PaymentSearchPage(driver);
    }

    public PaymentSearchPage selectInvoiceReference() {
        List<WebElement> radios = driver.findElements(By.name("type"));
        for (WebElement radio : radios) {
            if (radio.getAttribute("value").equals("2"))
                (radio).click();
        }
        return new PaymentSearchPage(driver);
    }

    public PaymentSearchPage enterReferenceAndSubmitSearch(String reference) {
        inputReferenceField.sendKeys(reference);
        paymentSearchButton.click();
        return new PaymentSearchPage(driver);
    }

    public PaymentDetailsPage clickReferenceLink(String reference) {
        driver.findElement(By.linkText(reference)).click();
        return new PaymentDetailsPage(driver);
    }

}
