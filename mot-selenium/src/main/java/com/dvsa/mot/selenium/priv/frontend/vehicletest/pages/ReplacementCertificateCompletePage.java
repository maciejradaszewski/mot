package com.dvsa.mot.selenium.priv.frontend.vehicletest.pages;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementTestComparisonPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import org.openqa.selenium.support.PageFactory;


public class ReplacementCertificateCompletePage extends BasePage {

    private static String PAGE_TITLE = "TEST RESULTS UPDATED SUCCESSFULLY";

    @FindBy(id = "quit") private WebElement doneButton;

    @FindBy(id = "reprint-certificate") private WebElement reprintReceiptButton;

    @FindBy(id = "compareTestResults") private WebElement compareTestResults;

    @FindBy(id = "pass-certificate-item") private WebElement passCertificateItem;

    @FindBy(id = "refusal-certificate-item") private WebElement refusalCertificateItem;

    @FindBy(id = "print") private WebElement print;

    public ReplacementCertificateCompletePage(WebDriver driver) {
        super(driver);
        PageFactory.initElements(driver, this);
        checkTitle(PAGE_TITLE);
    }

    public UserDashboardPage clickDoneButton() {
        doneButton.click();
        //return LoginPage;
        return new UserDashboardPage(driver);
    }

    public WebElement clickReprintReceiptButton() {
        disablePrintingOnCurrentPage();
        reprintReceiptButton.click();
        return reprintReceiptButton;
    }
    public String getPrintCertificateUrl() {
        return print.getAttribute("href");
    }

    public void clickPrintButton() {
      print.click();
    }

}
