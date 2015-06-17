package com.dvsa.mot.selenium.priv.frontend.organisation.management.common;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AssignARoleConfirmationPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SelectARolePage extends BasePage {

    @FindBy(id = "assign-role-button") protected WebElement assignRoleButton;

    public SelectARolePage(WebDriver driver) {
        super(driver);
    }

    public SelectARolePage(WebDriver driver, String pageTitle) {
        super(driver);
        checkTitle(pageTitle);
    }

    public AssignARoleConfirmationPage clickAssignARoleButton() {
        assignRoleButton.click();
        return new AssignARoleConfirmationPage(driver);
    }

    public static SelectARolePage navigateHereFromLoginPage(WebDriver driver, Login login,
            Site site, String username) {
        return FindAUserPage.navigateHereFromLoginPage(driver, login, site).enterUsername(username)
                .search();
    }

    private SelectARolePage selectRole(Role role) {

        driver.findElement(By.id("site-role-label-" + role.getAssignRoleName())).click();
        return this;
    }
    public AssignARoleConfirmationPage selectRoleAndSubmit(Role role) {

        return selectRole(role).clickAssignARoleButton();
    }

    public SelectARolePage selectAeRole(Role role) {

        driver.findElement(By.id("organisationRoleLabel-" + role.getRoleId())).click();
        return this;
    }
}
