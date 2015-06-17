package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.CacheLookup;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class MotManualPage extends BasePage {

    @FindBy(className = "headSingleTitle") @CacheLookup private WebElement rfrTitle;

    public MotManualPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
    }

}
