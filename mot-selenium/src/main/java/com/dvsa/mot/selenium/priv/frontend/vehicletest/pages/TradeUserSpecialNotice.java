package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.ui.ExpectedConditions;
import org.openqa.selenium.support.ui.WebDriverWait;

public class TradeUserSpecialNotice extends SpecialNoticesPage {

    public TradeUserSpecialNotice(WebDriver driver) {

        super(driver);
    }

    @Override public TradeUserSpecialNotice acknowledgeOverdueSpecialNotice(int specialNotice) {

        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("view-unread-" + specialNotice)))).click();
        new WebDriverWait(driver, 1).until(ExpectedConditions
                .visibilityOf(findWebElement(By.id("special-notice-submit-" + specialNotice))))
                .click();

        return this;
    }

}
