package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonForRefusal;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class MotTestRefusedPage extends BasePage {

    private final String PAGE_TITLE = "MOT TEST REFUSED";

    @FindBy(id = "reprint-certificate") private WebElement printDocuments;

    @FindBy(id = "refusal") public WebElement refuse;

    @FindBy(id = "confirm_vehicle_confirmation") public WebElement confirmRefusal;

    @FindBy(xpath = "/html/body/div[3]/div/div/div[1]/div/h1") public WebElement refusalMsg;

    public MotTestRefusedPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public static MotTestRefusedPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle, ReasonForRefusal reasonForRefusal) {
        return RefuseToTestPage.navigateHereFromLoginPage(driver, login, vehicle)
                .refuseMotTest(reasonForRefusal);
    }

    public boolean isPrintDocumentButtonDisplayed() {
        return isElementDisplayed(printDocuments);
    }

}
