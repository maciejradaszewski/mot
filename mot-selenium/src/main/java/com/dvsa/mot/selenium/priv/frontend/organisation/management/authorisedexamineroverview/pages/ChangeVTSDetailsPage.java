package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.CreateSitePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.PageFactory;

public class ChangeVTSDetailsPage extends CreateSitePage {

    public ChangeVTSDetailsPage(WebDriver driver) {
        super(driver, "EDIT VEHICLE TESTING STATION");
    }

    //public static ChangeVTSDetailsPage navigateHereFromLoginPage(WebDriver driver, Login login,
      //      Site site) {
        //return SiteDetailsPage.navigateHereFromLoginPage(driver, login, site).clickChangeDetails();
    //}


}
