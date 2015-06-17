package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class AeRemoveARolePage extends BasePage {

    private static String PAGE_TITLE = "AUTHORISED EXAMINER\n" + "REMOVE A ROLE";

    @FindBy(id = "confirm") private WebElement confirmRoleRemoval;

    public AeRemoveARolePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public AuthorisedExaminerOverviewPage clickConfirmRoleRemoval() {
        confirmRoleRemoval.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }







}
