package com.dvsa.mot.selenium.framework;

import com.dvsa.mot.selenium.framework.errors.UnauthorisedError;
import com.dvsa.mot.selenium.framework.errors.inline.InlineErrorVerifier;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementHomePage;
import com.dvsa.mot.selenium.priv.frontend.login.pages.LoginPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserPersonalProfilePage;
import com.dvsa.mot.selenium.pub.frontend.application.tester.pages.ManualsPage;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;
import org.testng.Assert;

import java.util.List;

public abstract class BasePage extends PageInteractionHelper {
    // Enable/Disable checking page titles
    public final static boolean checkPageTitles = true;

    public static String ERROR_STRING = "an error occurred";

    private static String HIDDEN_ERROR_STRING = "something went wrong!";

    @FindBy(tagName = "h1") protected WebElement title;

    @FindBy(id = "logout") private WebElement logout;

    @FindBy(id = "homepage") private WebElement homePage;

    @FindBy(id = "user-profile") private WebElement userProfile;

    @FindBy(id = "global-header") private WebElement globalHeader;

    @FindBy(id = "user-info") private WebElement userInfo;

    @FindBy(id = "current-site") private WebElement currentSite;

    @FindBy(id = "change-site") private WebElement changeSite;

    @FindBy(linkText = "Cancel") private WebElement cancel;

    @FindBy(id = "footerManuals") private WebElement footerManuals;

    public BasePage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);

        //Take screenshot
        if (isUXScreenshotEnabled()) {
            takePageScreenshot(getUXScreenshotPath());
        }

        if (hasErrorOccurred()) {
            if (isUnauthorisedErrorDisplayed()) {
                throw new UnauthorisedError();
            }
            Assert.fail("MOT Application is displaying an error page");
        }

        InlineErrorVerifier.verifyInlineErrorAtPage(this);
    }

    private boolean hasErrorOccurred() {
        List<WebElement> h1 = findElementWithoutImplicitWaits(By.tagName("h1"));
        if (h1.size() > 0) {
            String errorString = h1.get(0).getText().toLowerCase();
            if (errorString.contains(ERROR_STRING) || errorString.contains(HIDDEN_ERROR_STRING)) {
                return true;
            }
        }
        return false;
    }

    protected final void checkTitle(String expectedTitle) {
        String pageTitle = getPageTitle();
        if (checkPageTitles && !pageTitle.toUpperCase().contains(expectedTitle)) {
            throw new IllegalStateException(
                    "This is not the " + expectedTitle + " page; actual title [" + pageTitle + "]");
        }
    }

    public String getPageSource() {
        return driver.getPageSource();
    }

    public String getPageTitle() {
        return title.getText().toUpperCase();
    }

    public UserDashboardPage clickHome() {
        homePage.click();
        return new UserDashboardPage(driver);
    }

    public EnforcementHomePage clickHomeExpectingEnforcementHomePage() {
        homePage.click();
        return new EnforcementHomePage(driver);
    }

    public LoginPage clickLogout() {
        logout.click();
        LoginPage loginPage = new LoginPage(driver);
        return loginPage;
    }

    public UserPersonalProfilePage clickMyProfile() {
        userProfile.click();
        return new UserPersonalProfilePage(driver);
    }

    public String getUserInfo() {
        return userInfo.getText();
    }

    public String getGlobalHeaderInfo() {
        return globalHeader.getText();
    }

    public boolean isChangeSiteLinkDisplayed() {
        return isElementDisplayed(changeSite);
    }

    public boolean exist2FAFieldInCurrentPage() {
        turnOffImplicitWaits();
        List<WebElement> otpElements = driver.findElements(By.id("oneTimePassword"));
        turnOnImplicitWaits();
        return otpElements.size() > 0;
    }

    public boolean isCurrentSiteDisplayedInHeader() {
        return null != currentSite && !currentSite.getText().trim().isEmpty();
    }

    public boolean isUnauthorisedErrorDisplayed() {
        // this will be refactored after a proper error page is introduced
        for (WebElement e : driver.findElements(By.tagName("h3"))) {
            if (e.getText().contains("UnauthorisedException"))
                return true;
        }

        // on testing environment there's no information about the reason of error
        // so until there is a screen with proper message (according to Tom Carrington it would be),
        // we are also considering something went wrong as an possible authorisation error
        if (isSomethingWentWrongErrorDisplayed()) {
            return true;
        }

        return false;
    }

    public boolean isSomethingWentWrongErrorDisplayed() {
        // this will be refactored after a proper error page is introduced
        for (WebElement e : driver.findElements(By.tagName("h1"))) {
            if (e.getText().contains("Something went wrong!"))
                return true;
        }
        return false;
    }

    protected WebElement findWebElement(By selector) {
        List<WebElement> elements = driver.findElements(selector);
        if (elements.size() > 0) {
            WebElement element = elements.get(0);
            return element;
        }
        return null;
    }

    public ManualsPage clickFooterManualAndGuidesLink() {
        footerManuals.click();
        return new ManualsPage(driver);
    }

    public String getMotTestId() {
        String url = driver.getCurrentUrl();
        String id = url.substring(url.lastIndexOf("/") + 1);
        return id;
    }
}
