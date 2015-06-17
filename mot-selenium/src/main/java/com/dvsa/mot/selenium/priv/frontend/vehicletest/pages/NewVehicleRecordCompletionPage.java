package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Text;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.datasource.enums.PageTitles;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class NewVehicleRecordCompletionPage extends BasePage {

    public static final String PAGE_TITLE =
            PageTitles.NEW_VEHICLE_RECORD_COMPLETE_PAGE.getPageTitle();

    @FindBy(linkText = "MOT Test") private WebElement motTestLink;

    public NewVehicleRecordCompletionPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }
    
    public static NewVehicleRecordCompletionPage navigateHereFromLoginPage(WebDriver driver, Login login, Vehicle vehicle) {
        return NewVehicleRecordSummaryPage.navigateHereFromLoginPage(driver, login, vehicle)
                .confirmAndSave(Text.TEXT_PASSCODE);
    }

    public VehicleSearchPage goToVehicleSearch() {
        motTestLink.click();
        return new VehicleSearchPage(driver);
    }
}
