package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Payments;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;

import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class DirectDebitConfirmationPage extends BasePage {

    private static final String PAGE_TITLE = "DIRECT DEBIT CONFIRMATION";

    @FindBy(id = "successMessage") private WebElement statusMessage;

    @FindBy(id = "cancelAndReturn") private WebElement returnToAuthorisedExaminerLink;

    public DirectDebitConfirmationPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public String getStatusMessage() {
        return statusMessage.getText();
    }

    public AuthorisedExaminerOverviewPage clickReturnToAeLink() {
        returnToAuthorisedExaminerLink.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }
    
    public static DirectDebitConfirmationPage setupDirectDebitSuccessfully(WebDriver driver, Login login, Payments payments, Person person, Address address) {
        return AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, login)
                .clickSetupDirectDebitLink()
                .enterSlotsRequired(payments.slots).selectCollectionDate("5")
                .clickContinueButton().clickContinueToGoCardlessButton()
                .fillDirectDebitForm(person, address)
                .clickContinueButton().clickConfirmButton();
    }

}
