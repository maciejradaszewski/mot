package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;


import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.SelectARolePage;
import org.openqa.selenium.WebDriver;

public class AESelectARolePage extends SelectARolePage {

    private static String PAGE_TITLE = "AUTHORISED EXAMINER\n" + "CHOOSE A ROLE";

    public AESelectARolePage(WebDriver driver) {

        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public AEAssignARoleConfirmationPage clickAssignARoleButton() {

        assignRoleButton.click();
        return new AEAssignARoleConfirmationPage(driver);
    }
}
