package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class MotTestAbandonedPage extends BasePage {

    private static final String PAGE_TITLE = "TEST ABANDONED";

    @FindBy(id = "cancel_test_result") private WebElement finishButton;

    public MotTestAbandonedPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage clickFinish() {
        finishButton.click();
        return new UserDashboardPage(driver);
    }

}
