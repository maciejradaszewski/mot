package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;

/**
 * Created by davidd on 17/09/2014.
 */
public class DuplicateReplacementCertificatePrintPage extends BasePage {

    private static final String PRINT_REPLACEMENT_CERT_PAGE_TITLE = "DUPLICATE DOCUMENT AVAILABLE";

    @FindBy(id = "reprint-certificate") private WebElement reprintCertificateButton;

    @FindBy(id = "quit") private WebElement backToUserHome;

    public DuplicateReplacementCertificatePrintPage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PRINT_REPLACEMENT_CERT_PAGE_TITLE);
    }

    public DuplicateReplacementCertificatePrintPage(WebDriver driver, String title) {
        super(driver);
        checkTitle(title);
    }

    public UserDashboardPage clickBackToUserHome() {
        backToUserHome.click();
        return new UserDashboardPage(driver);
    }

    public DuplicateReplacementCertificatePrintPage printCertificate() {

        reprintCertificateButton.click();
        return this;
    }

    public boolean isPrintDocumentDisplayed() {

        if (reprintCertificateButton.isDisplayed()) {
            return true;
        } else {
            return false;
        }
    }

    public boolean isPrintDocumentButtonDisplayed() {
        return isElementDisplayed(reprintCertificateButton);
    }
}
