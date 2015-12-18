package com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages;

import com.dvsa.mot.selenium.datasource.Address;
import com.dvsa.mot.selenium.datasource.Business;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.ElementDisplayUtils;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.UpdateAeContactDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.AedmTestLogs;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.BuySlotsPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.ManageDirectDebitPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.OrganisationSlotsUsagePage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.SetUpDirectDebitPage;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.TransactionHistoryPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

import java.util.List;

/**
 * Authorised examiner details page - trade version.
 *
 * For enforcement version
 * see {@link com.dvsa.mot.selenium.priv.frontend.enforcement.pages.AuthorisedExaminerFullDetailsPage}.
 */
public class AuthorisedExaminerOverviewPage extends BasePage {

    @Override public String getPageSource() {
        return super.getPageSource();
    }

    private static final String PAGE_TITLE = "AUTHORISED EXAMINER";

    @FindBy(xpath = ".//h1") private WebElement aeDetails;

    @FindBy(id = "change-site-details") private WebElement changeSiteDetailsLink;

    @FindBy(id = "slot-usage") private WebElement slotUsage;

    @FindBy(id = "add-slots") private WebElement buySlotsLink;
    
    @FindBy(id = "setupDirectDebit") private WebElement setupDirectDebitLink;
    
    @FindBy(id = "manageDirectDebit") private WebElement manageDirectDebitLink;
    
    @FindBy(id = "slots-adjustment") private WebElement slotsAdjustmentLink;
    
    @FindBy(id = "transaction-history") private WebElement transactionHistoryLink;

    @FindBy(id = "reg_AE_address") private WebElement authorisedExaminerAddress;

    @FindBy(id = "authorised-examiner-contact-details") private WebElement
            authorisedExaminerContactDetails;

    @FindBy(id = "authorised-examiner-designated-manager-0") private WebElement
            authorisedExaminerDesignatedManager;

    @FindBy(id = "authorised-examiner-delegate") private WebElement authorisedExaminerDelegate;

    @FindBy(id = "vehicle-testing-station-1") private WebElement vehicleTestingStation1;

    @FindBy(id = "vehicle-testing-station-2") private WebElement vehicleTestingStation2;

    @FindBy(id = "vehicle-testing-station-3") private WebElement vehicleTestingStation3;

    @FindBy(id = "validation-message--success") private WebElement infoMessage;

    @FindBy(id = "assign-a-role") private WebElement assignRoleLink;

    @FindBy(id = "remove-role") private WebElement removeRoleLink;

    @FindBy(css = "#slot-count span") private WebElement numberOfSlots;

    @FindBy(id = "authorisedExaminerHeader") private WebElement headerOfAE;

   @FindBy(id = "value_AE_address") private WebElement addressOfAE;

    @FindBy(id = "change-contact-details") private WebElement changeAEDetails;

    @FindBy(id = "value_AE_email") private WebElement emailOfAE;

    @FindBy(id = "remove-aep") private WebElement removeAep;

    @FindBy(id = "add-aep") private WebElement addAep;

    @FindBy(id = "test-log") private WebElement viewTestLogs;

    @FindBy(id = "transactions") private WebElement transactions;

    @FindBy(id = "event-history") private WebElement eventHistoryLink;

    @FindBy(css = ".key-value-list__key>a") private WebElement roleAssociation;

    @FindBy(id = "remove-role") private WebElement removeAedRole;

    @FindBy(id = "validation-message--success") private WebElement roleRemovalNotification;

    @FindBy(id = "cor_email") private WebElement correspondenceEmail;

    @FindBy(id = "cor_address") private  WebElement correspondenceAddress;

    @FindBy(id = "cor_phone") private WebElement correspondenceContactNumber;

    @FindBy (id = "return-home") private WebElement returnHomeButton;

    @FindBy(id = "ae-name") private WebElement aeName;

    @FindBy(id = "ae-number") private WebElement aeNumber;

    @FindBy(id = "ae-company-number") private WebElement aeCompanyNumber;

    @FindBy(id = "ae-tradename") private WebElement aeTradeName;

    @FindBy(id = "ae-type") private WebElement aeType;




    private final String AEDM_ROLES_LIST_XPATH =
            "//*[contains(@id,'authorised-examiner-designated-manager-')]";

    public AuthorisedExaminerOverviewPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public AuthorisedExaminerOverviewPage(WebDriver driver, String organisation) {

        super(driver);
        checkTitle(PAGE_TITLE + "\n" + organisation);
    }

    public String getAeNameDetails() {
        return aeDetails.getText();
    }

    public static AuthorisedExaminerOverviewPage navigateHereFromLoginPage(WebDriver driver,
            Login login, Business organisation) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login)
                .manageAuthorisedExaminer(organisation);
    }

    public ChangeAeDetailsPage changeDetails() {
        changeSiteDetailsLink.click();
        return new ChangeAeDetailsPage(driver);
    }

    public static AuthorisedExaminerOverviewPage navigateHereFromLoginPage(WebDriver driver,
            Login login) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickFirstAeLink();
    }

    public boolean isAuthorisedExaminerDetailsPresent() {
            return !authorisedExaminerAddress.getText().isEmpty();
    }

    public String getAuthorisedExaminerAddress() {
        return authorisedExaminerAddress.getText();
    }


    public boolean isAuthorisedExaminerVehicleTestingStationDetailsPresent() {
        return !vehicleTestingStation1.getText().isEmpty();
    }

    public boolean verifyAeManagementRolesExists() {
        return ((!authorisedExaminerDesignatedManager.getText().isEmpty())
                || (!authorisedExaminerDelegate.getText().isEmpty()));

    }

    public AEFindAUserPage clickAssignRole() {
        assignRoleLink.click();
        return new AEFindAUserPage(driver);
    }

    public boolean isSlotsZeroOrMoreAvailable() {
        return (Integer.parseInt(numberOfSlots.getText()) >= 0);
    }

    public boolean removeAnAep(String firstName, String lastName) {

        String fullName;
        String[] firstAndLastNames;
        List<WebElement> listOfNames = driver.findElements(
                By.xpath("id('authorised-examiner-principals')/div/div/div[1]"));
        for (WebElement name : listOfNames) {
            fullName = name.getText();
            firstAndLastNames = fullName.split(" ");
            if ((firstAndLastNames[0].equalsIgnoreCase(firstName)) && (firstAndLastNames[2]
                    .equalsIgnoreCase(lastName))) {
                removeAep.click();
                return true;
            }
        }
        return false;
    }

    private String getXPathForAEDMUserRoleDetails(Person person) {
        String personName = "[contains(.,'" + person.getFullNameWithOutTitle() + "')]";
        return AEDM_ROLES_LIST_XPATH + personName;
    }

    public boolean existAEDMRoleForUser(Role role, Person person) {
        WebElement webElement = driver.findElement(By.id("role-assignment-" + person.getId() + "-" + role.getFullName()));
        return webElement != null;
    }
    
    public BuySlotsPage clickBuySlotsLink() {
    	buySlotsLink.click();
        return new BuySlotsPage(driver);
    }
    
    public SetUpDirectDebitPage clickSetupDirectDebitLink(){
        setupDirectDebitLink.click();
        return new SetUpDirectDebitPage(driver);
    }
    
    public ManageDirectDebitPage clickManageDirectDebitLink(){
        manageDirectDebitLink.click();
        return new ManageDirectDebitPage(driver);
    }
    
    public TransactionHistoryPage clickTransactionHistoryLink() {
    	transactionHistoryLink.click();
        return new TransactionHistoryPage(driver);
    }
    
    public OrganisationSlotsUsagePage clickSlotUsageLink() {
        slotUsage.click();
        return new OrganisationSlotsUsagePage(driver);
    }

    public boolean verifyUserAddedToAEDList(String firstName, String lastName) {

        String fullName;
        String[] firstAndLastNames;
        List<WebElement> listOfNames =
                driver.findElements(By.xpath("id('positions-list')/div/div/div[1]"));
        for (WebElement name : listOfNames) {
            fullName = name.getText();
            firstAndLastNames = fullName.split(" ");
            if ((firstAndLastNames[0].equalsIgnoreCase(firstName)) && (firstAndLastNames[1]
                    .equalsIgnoreCase(lastName))) {

                return true;
            }
        }
        return false;
    }

    public String getInfoMessage() {
        return infoMessage.getText();
    }

    public boolean isSlotsUsageLinkVisible() {
        return isElementDisplayed(slotUsage);
    }
    
    public boolean isBuySlotsLinkVisible() {
        return isElementDisplayed(buySlotsLink);
    }
    
    public boolean isTransactionHistoryLinkVisible() {
        return isElementDisplayed(transactionHistoryLink);
    }
    
    public boolean isSetupDirectDebitLinkVisible() {
        return isElementDisplayed(setupDirectDebitLink);
    }
    
    public boolean isManageDirectDebitLinkVisible() {
        return isElementDisplayed(manageDirectDebitLink);
    }
    
    public boolean isSlotsAdjustmentLinkVisible() {
        return isElementDisplayed(slotsAdjustmentLink);
    }

    public AedmTestLogs viewTestLogs(String title) {
        viewTestLogs.click();
        return new AedmTestLogs(driver, title.toUpperCase());
    }

    private RemoveRolePage clickRemoveAEDMRoleButtonForUser(Role role, Person person) {
        if (existAEDMRoleForUser(role, person)) {
            WebElement removeRole = driver.findElement(By.xpath(
                    getXPathForAEDMUserRoleDetails(person) + "//a[text()='Remove']"));
            removeRole.click();
        }
        return new RemoveRolePage(driver);
    }

    public AuthorisedExaminerOverviewPage removeAEDMRoleFromUser(Role role, Person person) {
        return clickRemoveAEDMRoleButtonForUser(role, person).confirmRemoveRole();
    }

    public AuthorisedExaminerOverviewPage assignNewRoleToUser(Role role, Person person) {
        return clickAssignRole().enterUsername(person.login.username).search()
                .selectAeRole(role).clickAssignARoleButton().clickOnConfirmButton();
    }

    public SiteDetailsPage clickVTSLinkExpectingSiteDetailsPage(String vtsName) {
        driver.findElement(By.partialLinkText(vtsName)).click();
        return new SiteDetailsPage(driver);
    }

    public boolean checkAddress(Address address) {
        return getAuthorisedExaminerAddress().contains(address.line1)
                && getAuthorisedExaminerAddress().contains(address.line2)
                && getAuthorisedExaminerAddress().contains(address.line3)
                && getAuthorisedExaminerAddress().contains(address.town)
                && getAuthorisedExaminerAddress().contains(address.postcode);
    }

    private String getNominationMessageXpath(Person person) {

        String message = "A role notification has been sent to " + person.getNamesAndSurname();
        return "//p[contains(.," + "'" + message + "'" + ")]";
    }

    public boolean verifyNominationMessage(Person person) {

        String xpath = getNominationMessageXpath(person);
        WebElement nominationMessage = driver.findElement(By.xpath(xpath));
        return isElementDisplayed(nominationMessage);
    }

    public UpdateAeContactDetailsPage clickUpdateContactDetailsLinkForAe() {
        changeAEDetails.click();
        return new UpdateAeContactDetailsPage(driver);
    }

    public AeRemoveARolePage clickRemoveAedRole() {
        removeAedRole.click();
        return new AeRemoveARolePage(driver);
    }

    public String getRoleRemovalNotification() {
        return roleRemovalNotification.getText();
    }

    public String getEmailAddress() {return correspondenceEmail.getText();}

    public String getContactNumber() {return correspondenceContactNumber.getText();}

    public String getCorrespondenceAddress() {return correspondenceAddress.getText();}

    public UserDashboardPage returnHomeButton() {
        returnHomeButton.click();
        return new UserDashboardPage(driver);
    }


    public boolean isAePageElementsForVtsUsersDisplayed() {
        WebElement[] elements =
                {aeName, aeNumber, aeTradeName, aeType, aeCompanyNumber};
        return ElementDisplayUtils.elementsDisplayed(elements);
    }
}
