package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AEAssignARoleConfirmationPage extends AssignARoleConfirmationPage {

    private static String PAGE_TITLE = "AUTHORISED EXAMINER\n" + "SUMMARY AND CONFIRMATION";

    @FindBy(id = "submit") private WebElement confirmRoleRemoval;

    public AEAssignARoleConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public AuthorisedExaminerOverviewPage clickOnConfirmButton() {
        confirmButton.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public static AssignARoleConfirmationPage assignRoleAtOrganisationLevel(Login loginAs,
            Login userBeingAssociated, Site site, String OrganisationName, WebDriver driver,Role role) {

        return SiteDetailsPage.navigateHereFromLoginPage(driver, loginAs, site)
                .clickOnAeLink(OrganisationName).clickAssignRole()
                .enterUsername(userBeingAssociated.username).search().selectAeRole(role)
                .clickAssignARoleButton();
    }
}
