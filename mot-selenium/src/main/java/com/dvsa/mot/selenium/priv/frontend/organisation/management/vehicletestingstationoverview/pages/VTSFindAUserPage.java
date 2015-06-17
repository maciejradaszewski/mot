package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;


import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.FindAUserPage;
import org.openqa.selenium.WebDriver;

public class VTSFindAUserPage extends FindAUserPage {

    private static String PAGE_TITLE = "VEHICLE TESTING STATION\n" + "SEARCH FOR A USER";

    public VTSFindAUserPage(WebDriver driver) {

        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public VTSSelectARolePage search() {
        searchButton.click();
        return new VTSSelectARolePage(driver);
    }

    public VTSFindAUserPage enterUsername(String username) {
        userSearchBox.sendKeys(username);
        return new VTSFindAUserPage(driver);
    }

}
