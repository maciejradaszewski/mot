package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.support.PageFactory;

import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Login;

public class ChangeAeDetailsPage extends CreateAuthorisedExaminerPage {

    public ChangeAeDetailsPage(WebDriver driver) {
        super(driver);
    }

    public static ChangeAeDetailsPage navigateHereFromLoginPage(WebDriver driver, Login login, Business organisation) {
        return AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, login, organisation).changeDetails();
    }
}
