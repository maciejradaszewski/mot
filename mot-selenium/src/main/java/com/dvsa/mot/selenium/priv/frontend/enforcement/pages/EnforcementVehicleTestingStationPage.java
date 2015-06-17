package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class EnforcementVehicleTestingStationPage extends BasePage {

    public static final String PAGE_TITLE = "VEHICLE TESTING STATION";

    @FindBy(id = "return-to-home") private WebElement returnToHomepage;

    @FindBy(id = "search-again") private WebElement searchAgain;

    public EnforcementVehicleTestingStationPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public EnforcementHomePage returnToHomepage() {
        returnToHomepage.click();
        return new EnforcementHomePage(driver);
    }

}
