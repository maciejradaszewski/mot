package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.ReasonToCancel;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

public class MotTestAbortedPage extends BasePage {

    private static final String PAGE_TITLE = "TEST ABORTED";

    @FindBy(id = "cancel_test_result") private WebElement finishButton;


    public MotTestAbortedPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage clickFinish() {
        finishButton.click();
        return new UserDashboardPage(driver);
    }

    public static MotTestAbortedPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Vehicle vehicle, ReasonToCancel reasonToCancel) {
        return ReasonsToCancelPage.navigateHereFromLoginPage(driver, login, vehicle)
                .enterAndSubmitReasonsToCancelPageExpectingAbortedPage(reasonToCancel);
    }

}
