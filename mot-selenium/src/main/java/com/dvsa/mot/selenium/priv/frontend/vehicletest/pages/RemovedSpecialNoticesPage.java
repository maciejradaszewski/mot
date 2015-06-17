package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class RemovedSpecialNoticesPage extends BasePage {
    public static final String PAGE_TITLE = "REMOVED SPECIAL NOTICES";

    @FindBy(id = "current_special_notice") private WebElement currentSpecialNoticesLink;

    @FindBy(id = "special-notice") private WebElement removedSpecialNotice;

    public RemovedSpecialNoticesPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

}
