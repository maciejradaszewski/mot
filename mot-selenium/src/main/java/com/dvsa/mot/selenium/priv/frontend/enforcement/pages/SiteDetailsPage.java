package com.dvsa.mot.selenium.priv.frontend.enforcement.pages;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.OpeningHours;
import com.dvsa.mot.selenium.datasource.Person;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.datasource.enums.Days;
import com.dvsa.mot.selenium.datasource.enums.Role;
import com.dvsa.mot.selenium.framework.BasePage;
import com.dvsa.mot.selenium.framework.util.ElementDisplayUtils;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.ConfigureBrakeTestDefaultsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VTSFindAUserPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VtsInProgressMotTestPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.vehicletestingstationoverview.pages.VtsRemoveARolePage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;

public class SiteDetailsPage extends BasePage {

    private String PAGE_TITLE = "VEHICLE TESTING STATION";

    @FindBy(id = "site-number") private WebElement siteNumber;

    @FindBy(id = "site-name") private WebElement siteName;

    @FindBy(id = "site-address") private WebElement siteAddress;

    @FindBy(id = "email") private WebElement siteEmail;

    @FindBy(id = "phone-number") private WebElement phoneNumber;

    @FindBy(id = "fax") private WebElement fax;

    @FindBy(id = "assign-a-role") private WebElement assignARole;

    @FindBy(id = "site-classes") private WebElement testClasses;

    @FindBy(id = "site-role-1-roles") private WebElement siteRole;

    @FindBy(id = "change-contact-details") private WebElement changeContactDetailsLink;

    @FindBy(id = "equipmentDetails") private WebElement equipmentDetails;

    @FindBy(id = "opening-hours") private WebElement openingHours;

    @FindBy(xpath=".//h1") private WebElement title;

    @FindBy(id = "configure-brake-test-defaults-link") private WebElement
            configureBrakeTestDefaultsLink;

    @FindBy(id = "default-brake-test-class-1-and-2") private WebElement defaultBakeTestClass1And2;

    @FindBy(id = "default-parking-brake-test-class-3-and-above") private WebElement
            defaultParkingBrakeTestClass3AndAbove;

    @FindBy(id = "default-service-brake-test-class-3-and-above") private WebElement
            defaultServiceBrakeTestClass3AndAbove;

    @FindBy(id = "remove-role") private WebElement removeRole;

    @FindBy(id = "change-testing-hours") private WebElement changeOpeningHours;

    @FindBy(id = "returnDashboard") private WebElement backToHomeLink;

    @FindBy(id = "authorised-examiner-link") private WebElement authorisedExaminerLink;

    @FindBy(id = "feedback-link") private WebElement feedbackLink;

    @FindBy(id = "validation-message--success") private WebElement roleRemovalSuccessNotification;

    @FindBy(id = "search-again") private WebElement searchAgain;

    public SiteDetailsPage(WebDriver driver) {
        super(driver);
        checkTitle(PAGE_TITLE);
    }

    //TODO refactor for VTS Search
    public static SiteDetailsPage navigateHereFromLoginPage(WebDriver driver, Login login,
            Site site) {
        return UserDashboardPage.navigateHereFromLoginPage(driver, login).clickOnSiteLink(site);
    }

    public SiteInformationSearchPage clickSearchAgain(){
        searchAgain.click();
        return new SiteInformationSearchPage(driver);
    }

    public boolean checkChangeOpeningHoursLinkExists() {
        return isElementDisplayed(changeOpeningHours);
    }

    public boolean isVtsContactDetailsDisplayed() {
        WebElement[] elements =
                {testClasses, siteNumber, siteName, siteAddress, siteEmail, phoneNumber};
        return ElementDisplayUtils.elementsDisplayed(elements);
    }

    public VTSFindAUserPage clickAssignARoleLink() {
        assignARole.click();
        return new VTSFindAUserPage(driver);
    }

    public boolean isNominationResponseDateReflected(DateTime date) {

        String acceptedDate = actualDateDisplayed(date);
        DateTime currentDate = new DateTime();
        String verifyDate = actualDateDisplayed(currentDate);
        if (acceptedDate.equalsIgnoreCase(verifyDate)) {
            return true;
        }

        return false;
    }

    private String actualDateDisplayed(DateTime date) {

        DateTimeFormatter dateFormatter = DateTimeFormat.forPattern("d MMM YYYY");
        return dateFormatter.print(date);
    }

    public boolean existVTSRoleForUser(Role role, Person person) {
        WebElement webElement = driver.findElement(By.id("role-assignment-" + person.getId() + "-" + role.getAssignRoleName()));
        return webElement != null;
    }

    public VtsRemoveARolePage clickRemoveRoleLink() {
        removeRole.click();
        return new VtsRemoveARolePage(driver);

    }

    public boolean isDayPresentInOpeningHours(Days day) {
        if (openingHours.getText().contains(day.getDayName())) {
            return true;
        } else {
            return false;
        }
    }

    public ConfigureBrakeTestDefaultsPage clickOnChangeDefaults() {
        configureBrakeTestDefaultsLink.click();
        return new ConfigureBrakeTestDefaultsPage(driver);
    }

    public boolean isBrakeTestDefaultsDisplayedCorrectly(String SelectedBrakeTestType) {
        if (SelectedBrakeTestType.equals(defaultBakeTestClass1And2.getText())) {
            return true;
        }
        return false;
    }

    public String getSiteClasses() {
        return testClasses.getText();
    }

    public boolean isBrakeTestDefaultsForClassBDisplayedCorrectly(
            String SelectedServiceBrakeTestType, String SelectedParkingBrakeTestType) {
        if (SelectedServiceBrakeTestType.equals(defaultServiceBrakeTestClass3AndAbove.getText())
                && SelectedParkingBrakeTestType
                .equals(defaultParkingBrakeTestClass3AndAbove.getText())) {
            return true;
        }
        return false;
    }

    public VtsInProgressMotTestPage clickOnActiveMotTestLink(String motId) {
        WebElement siteActiveMotTest =
                driver.findElement(By.id("site-active-mot-test-link-" + motId)).findElement(By.tagName("a"));
                siteActiveMotTest.click();
        return new VtsInProgressMotTestPage(driver);
    }
    
    public ManageOpeningHoursPage clickChangeOpeningHours() {
        changeOpeningHours.click();
        return new ManageOpeningHoursPage(driver);
    }

    public boolean isHoursCorrectForDay(Days day, OpeningHours expectedTime) {
        WebElement openingHours =
                driver.findElement(By.id(day.getDayName().toLowerCase() + "-hours"));
        if (openingHours.getText().equals(OpeningHours.checkOpeningHoursString(expectedTime))) {
            return true;
        } else {
            return false;
        }
    }

    public AuthorisedExaminerOverviewPage clickOnAeLink(String org) {

        authorisedExaminerLink.click();
        return new AuthorisedExaminerOverviewPage(driver, org);
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
    public String getVTSName() {
        return title.getText();
    }

    public UpdateVtsContactDetailsPage clickUpdateContactDetailsLinkForVts() {
        changeContactDetailsLink.click();
        return new UpdateVtsContactDetailsPage(driver);
    }

    public String getFeedbackLink () {
        return feedbackLink.getAttribute("href");
    }

    public String getRoleRemovalSuccessNotification() {
        return roleRemovalSuccessNotification.getText();
    }
    public String getEmailAddress() {return siteEmail.getText();}

    public String getSiteContactNumber() {return phoneNumber.getText();}

    public boolean isEmailAddPresent() {
    return !siteEmail.getText().isEmpty();}
}
