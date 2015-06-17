package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class RegisterVehicleForMotPage extends BasePage {
    public static final String PAGE_TITLE = "REGISTER A VEHICLE FOR MOT";

    @FindBy(id = "start_certificate_reissue") private WebElement duplicateReplacementCertificateBtn;

    @FindBy(id = "logout") private WebElement logout;

    public RegisterVehicleForMotPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public LoginPage logout() {
        logout.click();
        return new LoginPage(driver);
    }
}
