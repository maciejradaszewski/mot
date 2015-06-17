package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.SelectARolePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AssignARoleConfirmationPage extends BasePage {

    @FindBy(id = "confirm-role") protected WebElement confirmButton;

    public AssignARoleConfirmationPage(WebDriver driver) {

        super(driver);
    }

    public static AssignARoleConfirmationPage navigateHereFromLoginPage(WebDriver driver,
            Login login, Site site, String username, Role role) {
        return SelectARolePage.navigateHereFromLoginPage(driver, login, site, username)
                .selectRoleAndSubmit(role);
    }

    public AuthorisedExaminerOverviewPage clickOnConfirmButton() {
        confirmButton.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public SiteDetailsPage clickOnConfirmNominationExpectingSiteDetailsPage() {
        confirmButton.click();
        return new SiteDetailsPage(driver);
    }

}
