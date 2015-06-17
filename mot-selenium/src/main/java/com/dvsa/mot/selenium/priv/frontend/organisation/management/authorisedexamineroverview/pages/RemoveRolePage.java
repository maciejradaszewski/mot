package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class RemoveRolePage extends BasePage {

    @FindBy(id = "confirm") private WebElement removeARoleConfirmation;

    public RemoveRolePage(WebDriver driver) {
        super(driver);
    }

    public AuthorisedExaminerOverviewPage confirmRemoveRole() {
        removeARoleConfirmation.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }
}
