package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;


import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.common.FindAUserPage;
import org.openqa.selenium.WebDriver;

public class AEFindAUserPage extends FindAUserPage {

    private static String PAGE_TITLE = "AUTHORISED EXAMINER\n" + "SEARCH FOR A USER";

    public AEFindAUserPage(WebDriver driver) {

        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public AEFindAUserPage enterUsername(String username) {
        userSearchBox.sendKeys(username);
        return new AEFindAUserPage(driver);
    }

    public AESelectARolePage search() {
        searchButton.click();
        return new AESelectARolePage(driver);
    }
}
