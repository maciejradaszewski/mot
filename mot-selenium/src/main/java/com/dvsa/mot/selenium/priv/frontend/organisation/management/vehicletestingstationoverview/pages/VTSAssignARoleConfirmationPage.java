package com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AssignARoleConfirmationPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;

import java.util.ArrayList;

public class VTSAssignARoleConfirmationPage extends AssignARoleConfirmationPage {

    private static String PAGE_TITLE = "VEHICLE TESTING STATION\n" + "SUMMARY AND CONFIRMATION";
    private static SiteDetailsPage siteDetailsAfterRoleAssignedPage;

    public VTSAssignARoleConfirmationPage(WebDriver driver) {

        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public SiteDetailsPage clickOnConfirmNominationExpectingSiteDetailsPage() {
        confirmButton.click();
        return new SiteDetailsPage(driver);
    }

    public static VTSAssignARoleConfirmationPage assignRoleAtSiteLevel(Login loginAsUser,
            String siteName, Person userBeingAssociated, Role roleBeingAssociated,
            WebDriver driver) {

        UserDashboardPage userDashboardPage = LoginPage.loginAs(driver, loginAsUser);
        SiteDetailsPage siteDetailsPage = userDashboardPage.clickOnSiteLink(siteName);
        VTSFindAUserPage findAUserPage = siteDetailsPage.clickAssignARoleLink();
        VTSSelectARolePage selectARolePage =
                findAUserPage.enterUsername(userBeingAssociated.login.username).search();
        return selectARolePage.selectRoleAndSubmit(roleBeingAssociated);
    }

    public static SiteDetailsPage assignMultipleRoleAtSiteLevel(Login loginAsUser, String siteName,
            Person userBeingAssociated, ArrayList<Role> rolesBeingAssociated, WebDriver driver) {

        UserDashboardPage userDashboardPage = LoginPage.loginAs(driver, loginAsUser);
        SiteDetailsPage siteDetailsPage = userDashboardPage.clickOnSiteLink(siteName);

        for (Role roleBeingAssociated : rolesBeingAssociated) {

            siteDetailsAfterRoleAssignedPage = siteDetailsPage.clickAssignARoleLink()
                    .enterUsername(userBeingAssociated.login.username).search()
                    .selectRole(roleBeingAssociated).clickAssignARoleButton()
                    .clickOnConfirmNominationExpectingSiteDetailsPage();
        }

        return siteDetailsAfterRoleAssignedPage;
    }

}
