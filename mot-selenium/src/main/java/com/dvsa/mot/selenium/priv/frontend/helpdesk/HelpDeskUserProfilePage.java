package com.dvsa.mot.selenium.priv.frontend.helpdesk;

import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EventHistoryPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class HelpDeskUserProfilePage extends BasePage {
    private String PAGE_TITLE = "USER PROFILE";

    @FindBy(id = "reset-password-by-post") private WebElement resetPassword;

    @FindBy(id = "reset-username-by-post") private WebElement recoverUsername;

    @FindBy(linkText = "Cancel and return to search results") private WebElement
            backToSearchResults;

    @FindBy(linkText = "Return to home") private WebElement returnToHome;

    @FindBy(id = "person-name") private WebElement name;

    @FindBy(id = "person-username") private WebElement username;

    @FindBy(id = "person-dob") private WebElement birth;

    @FindBy(id = "person-address") private WebElement address;

    @FindBy(id = "person-email") private WebElement email;

    @FindBy(id = "person-telephone") private WebElement telephone;

    @FindBy(id = "person-driving-licence") private WebElement licenceNUm;

    @FindBy(css = ".key-value-list__key>a") private WebElement roleAssociation;

    @FindBy(id = "person-event-history-link") private WebElement eventHistoryLink;

    @FindBy(id = "reset-to-unclaim-account-by-post") private WebElement resetAccountByPost;

    @FindBy(id = "validation-message--success") private WebElement validationMessageSuccess;

    @FindBy(id = "validation-message--failure") private WebElement validationMessageFailure;

    @FindBy(id = "group-A-qualification-status") private WebElement qualificationStatusGroupA;

    @FindBy(id = "group-B-qualification-status") private WebElement qualificationStatusGroupB;

    public HelpDeskUserProfilePage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    public HelpdeskResetPasswordSuccessPage clickResetPassword() {
        resetPassword.click();
        return new HelpdeskResetPasswordSuccessPage(driver);
    }

    public HelpdeskRecoverUsernameSuccessPage clickRecoverUsername() {
        recoverUsername.click();
        return new HelpdeskRecoverUsernameSuccessPage(driver);
    }

    public HelpdeskUserResultsPage backToSearchResults() {
        backToSearchResults.click();
        return new HelpdeskUserResultsPage(driver);
    }

    public UserDashboardPage returnToHome() {
        returnToHome.click();
        return new UserDashboardPage(driver);
    }

    public String getName() {
        return name.getText();
    }

    public String getUserName() {
        return username.getText();
    }

    public String getDateOfBirth() {
        return birth.getText();
    }

    public String getAddress() {
        return address.getText();
    }

    public String getEmail() {
        return email.getText();
    }

    public String getQualificationStatusGroupA() {
        return qualificationStatusGroupA.getText();
    }

    public String getQualificationStatusGroupB() {
        return qualificationStatusGroupB.getText();
    }

    public String getTelephoneNumber() {
        return telephone.getText();
    }

    public String getLicenceNumber() {
        return licenceNUm.getText();
    }

    public boolean isPasswordResetDisplayed() {
        return isElementDisplayed(resetPassword);
    }

    public boolean isUsernameResetDisplayed() {
        return isElementDisplayed(recoverUsername);
    }

    public SiteDetailsPage getVehicleTestingStationPage() {
        roleAssociation.click();
        return new SiteDetailsPage(driver);
    }

    public AuthorisedExaminerOverviewPage getAuthorisedExaminerPage() {
        roleAssociation.click();
        return new AuthorisedExaminerOverviewPage(driver);
    }

    public EventHistoryPage clickEventHistoryLink() {
        eventHistoryLink.click();
        return new EventHistoryPage(driver);
    }

    public String getTesterAssociation() {

        return roleAssociation.getText().trim();
    }

    public HelpDeskResetAccountConfirmationPage resetAccountByPost() {

        resetAccountByPost.click();
        return new HelpDeskResetAccountConfirmationPage(driver);
    }

    public boolean isAccountReclaimASuccess() {

        return isElementDisplayed(validationMessageSuccess);
    }

    public String getAccountReclaimByPostSuccessMessage() {

        return validationMessageSuccess.getText().trim();
    }

    public boolean isAccountReclaimAFailure() {

        return isElementDisplayed(validationMessageFailure);
    }

    public String getAccountReclaimByFailureMessage() {

        return validationMessageFailure.getText().trim();
    }

}
