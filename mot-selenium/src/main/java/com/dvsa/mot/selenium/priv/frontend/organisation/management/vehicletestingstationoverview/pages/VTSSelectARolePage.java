package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;

import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.SelectARolePage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;


public class VTSSelectARolePage extends SelectARolePage {

    private static String PAGE_TITLE = "VEHICLE TESTING STATION\n" + "CHOOSE A ROLE";

    public VTSSelectARolePage(WebDriver driver) {

        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public VTSSelectARolePage selectRole(Role role) {

        driver.findElement(By.id("site-role-label-" + role.getAssignRoleName())).click();
        return this;
    }

    public VTSAssignARoleConfirmationPage selectRoleAndSubmit(Role role) {

        return selectRole(role).clickAssignARoleButton();
    }

    public VTSAssignARoleConfirmationPage clickAssignARoleButton() {

        assignRoleButton.click();
        return new VTSAssignARoleConfirmationPage(driver);
    }
}
