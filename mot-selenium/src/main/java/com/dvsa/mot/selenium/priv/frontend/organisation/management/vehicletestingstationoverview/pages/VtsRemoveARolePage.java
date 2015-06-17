package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class VtsRemoveARolePage extends BasePage {

    private static String PAGE_TITLE = "VEHICLE TESTING STATION\n" + "REMOVE A ROLE";

    @FindBy(id = "confirm") private WebElement confirmRoleRemoval;

    public VtsRemoveARolePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public SiteDetailsPage clickConfirmRoleRemoval() {
        confirmRoleRemoval.click();
        return new SiteDetailsPage(driver);
    }

}
