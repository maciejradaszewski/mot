package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class OrderSummaryPage extends BasePage {

    private static final String PAGE_TITLE = "ORDER SUMMARY";

    @FindBy(id = "changeAmount") private WebElement changeAmountLink;

    @FindBy(id = "payByCard") private WebElement payByCardButton;

    public OrderSummaryPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public boolean isChangeAmountLinkVisible() {
        return isElementDisplayed(changeAmountLink);
    }

    public boolean isPayByCardButtonVisible() {
        return isElementDisplayed(payByCardButton);
    }

    public CardDetailsPage clickPayByCardButton() {
        payByCardButton.click();
        return new CardDetailsPage(driver);
    }

}
