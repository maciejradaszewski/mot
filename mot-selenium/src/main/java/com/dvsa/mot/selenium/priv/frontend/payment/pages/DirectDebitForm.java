package com.dvsa.mot.selenium.priv.frontend.payment.pages;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.framework.BasePage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class DirectDebitForm extends BasePage {

    @FindBy(id = "customer_given_name") private WebElement givenName;

    @FindBy(id = "customer_family_name") private WebElement familyName;

    @FindBy(id = "customer_email") private WebElement customerEmail;

    @FindBy(id = "customer_bank_accounts_account_number") private WebElement accountNo;

    @FindBy(id = "customer_bank_accounts_branch_code") private WebElement branchCode;

    @FindBy(id = "customer_address_line1") private WebElement customerAddressLine1;

    @FindBy(id = "customer_address_line2") private WebElement customerAddressLine2;

    @FindBy(id = "customer_city") private WebElement customerCity;

    @FindBy(id = "customer_postal_code") private WebElement customerPostCode;

    @FindBy(xpath = "//div[2]/button[text()='enter your address manually']") private WebElement enterAddressManuallyButton;

    @FindBy(xpath = "//button[contains(.,'Set up Direct Debit')]") private WebElement continueButton;

    @FindBy(id = "confirm-limit") private WebElement confirmButton;

    public DirectDebitForm(WebDriver driver) {
        super(driver);
    }

    public DirectDebitForm enterCustomerDetails(Person person) {
        givenName.sendKeys(person.forename);
        familyName.sendKeys(person.surname);
        customerEmail.sendKeys(person.email);
        return new DirectDebitForm(driver);
    }

    public DirectDebitForm enterAccountDetails() {
        accountNo.sendKeys(Integer.toString(55779911));
        branchCode.sendKeys(Integer.toString(200000));
        return new DirectDebitForm(driver);
    }

    public DirectDebitForm enterCustomerAddress(Address address) {
        enterAddressManuallyButton.click();
        customerAddressLine1.sendKeys(address.line1);
        customerAddressLine2.sendKeys(address.line2);
        customerCity.sendKeys(address.town);
        customerPostCode.sendKeys(address.postcode);
        return new DirectDebitForm(driver);
    }

    public DirectDebitForm fillDirectDebitForm(Person person, Address address) {
        enterCustomerDetails(person);
        enterAccountDetails();
        enterCustomerAddress(address);
        return new DirectDebitForm(driver);
    }

    public DirectDebitForm clickContinueButton() {
        continueButton.click();
        return new DirectDebitForm(driver);
    }

    public DirectDebitConfirmationPage clickConfirmButton() {
        confirmButton.click();
        return new DirectDebitConfirmationPage(driver);
    }

}
