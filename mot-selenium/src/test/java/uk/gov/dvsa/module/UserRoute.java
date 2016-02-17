package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.helper.ConfigHelper;
import uk.gov.dvsa.helper.FormCompletionHelper;
import uk.gov.dvsa.ui.pages.*;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchProfilePage;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;
import uk.gov.dvsa.ui.pages.profile.*;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

public class UserRoute {
    PageNavigator pageNavigator;
    ProfilePage profilePage;

    private static final String FIRST_NAME_ERROR_MESSAGE = "First name - you must enter a first name";
    private static final String LAST_NAME_ERROR_MESSAGE = "Last name - you must enter a last name";
    private static final String DOB_ERROR_MESSAGE = "must be a valid date of birth";

    private static final String EMAIL_MUST_BE_VALID_MESSAGE = "must be a valid email address";
    private static final String EMAIL_MUST_MATCH_MESSAGE = "the email addresses you have entered don't match";

    private static final String TELEPHONE_TOO_LARGE_MESSAGE = "Phone number - must be 24 characters or less";

    public UserRoute(PageNavigator pageNavigator) {
        this.pageNavigator = pageNavigator;
    }

    public void viewYourProfile(final User user) throws IOException {
        String queryPath = String.format(NewPersonProfilePage.PATH, user.getId());

        if(ConfigHelper.isNewPersonProfileEnabled()){
           profilePage = pageNavigator.navigateToPage(user, queryPath, NewPersonProfilePage.class);
        } else {
           profilePage = pageNavigator.navigateToPage(user, PersonProfilePage.PATH, PersonProfilePage.class);
        }
    }

    public void dvsaViewUserProfile(final User userViewingProfile, final User userProfileToView) throws IOException {
        String newQueryPath = String.format(NewUserProfilePage.PATH, userProfileToView.getId());
        String oldQueryPath = String.format(UserSearchProfilePage.PATH, userProfileToView.getId());

        if(ConfigHelper.isNewPersonProfileEnabled()){
            profilePage = pageNavigator.navigateToPage(userViewingProfile, newQueryPath, NewUserProfilePage.class);
        } else {
            profilePage = pageNavigator.navigateToPage(userViewingProfile, oldQueryPath, UserSearchProfilePage.class);
        }
    }

    public void tradeViewUserProfile(final User userViewingProfile, final User userProfileToView) throws IOException {
        profilePage = null;
        VehicleTestingStationPage vtsPage =
                pageNavigator.navigateToPage(userViewingProfile, HomePage.PATH, HomePage.class).selectRandomVts();

        profilePage = vtsPage.chooseAssignedToVtsUser(userProfileToView.getId());
    }

    public <T extends Page> T changeName(String firstName, String lastName, boolean isInputValid) {
        profilePage.clickChangeNameLink().fillFirstName(firstName).fillLastName(lastName);
        if (!isInputValid) {
            return (T)getChangeNamePage().clickSubmitButton(ChangeNamePage.class);
        }
        return (T)getChangeNamePage().clickSubmitButton(NewUserProfilePage.class);
    }

    public <T extends Page> T changeDateOfBirth(String day, String month, String year, boolean isValidValues) {
        ChangeDateOfBirthPage page = profilePage.clickChangeDOBLink();

        page.fillDay(day).fillMonth(month).fillYear(year);
        if (!isValidValues) {
            return (T)page.clickSubmitButton(ChangeDateOfBirthPage.class);
        }
        return (T)page.clickSubmitButton(NewUserProfilePage.class);
    }

    public <T extends Page> T changeEmail(String email, String emailConfirmation, String value) {
        profilePage.clickChangeEmailLink().fillEmail(email).fillEmailConfirmation(emailConfirmation);
        switch (value) {
            case "INVALID_INPUT":
                return (T) getEmailChangePage().clickSubmitButton(ChangeEmailDetailsPage.class);
            case "YOUR_PROFILE":
                return (T) getEmailChangePage().clickSubmitButton(NewPersonProfilePage.class);
            case "PERSON_PROFILE":
                return (T) getEmailChangePage().clickSubmitButton(NewUserProfilePage.class);
            default:
                throw new PageInstanceNotFoundException("Page instantiation exception");
        }
    }

    public <T extends Page> T changeTelephone(String telephone, String value) {
        profilePage.clickChangeTelephoneLink().fillTel(telephone);
        switch (value) {
            case "INVALID_INPUT":
                return (T) getTelephoneChangePage().clickSubmitButton(ChangeTelephoneDetailsPage.class);
            case "YOUR_PROFILE":
                return (T) getTelephoneChangePage().clickSubmitButton(NewPersonProfilePage.class);
            case "PERSON_PROFILE":
                return (T) getTelephoneChangePage().clickSubmitButton(NewUserProfilePage.class);
            default:
                throw new PageInstanceNotFoundException("Page instantiation exception");
        }
    }

    public boolean isTesterQualificationStatusDisplayed() {
        return profilePage.isTesterQualificationStatusDisplayed();
    }

    public boolean isValidationMessageOnChangeNamePageDisplayed(String warningMessage) {
        switch (warningMessage) {
            case "FIRST_NAME":
                return getChangeNamePage().getValidationMessage().equals(FIRST_NAME_ERROR_MESSAGE);
            case "LAST_NAME":
                return getChangeNamePage().getValidationMessage().equals(LAST_NAME_ERROR_MESSAGE);
            default:
                return false;
        }
    }

    public boolean isValidationMessageOnDOBPageDisplayed() {
        return getChangeDOBPage().getValidationMessage().equals(DOB_ERROR_MESSAGE);
    }

    private ChangeDateOfBirthPage getChangeDOBPage() {
        return new ChangeDateOfBirthPage(pageNavigator.getDriver());
    }

    public boolean isValidationMessageOnChangeEmailPageDisplayed(String warningMessage) {
        switch (warningMessage) {
            case "EMAIL_VALID":
                return getEmailChangePage().getValidationMessage().equals(EMAIL_MUST_BE_VALID_MESSAGE);
            case "EMAIL_MATCH":
                return getEmailChangePage().getValidationMessage().equals(EMAIL_MUST_MATCH_MESSAGE);
            case "EMAIL_MATCH_AND_VALID":
                return getEmailChangePage().getValidationMessage().contains(EMAIL_MUST_BE_VALID_MESSAGE) && getEmailChangePage().getValidationMessage().contains(EMAIL_MUST_MATCH_MESSAGE);
            default:
                return false;
        }
    }

    public boolean isValidationMessageOnChangeTelephonePageDisplayed(String warningMessage) {
        switch (warningMessage) {
            case "TELEPHONE_TOO_LARGE":
                return getTelephoneChangePage().getValidationMessage().equals(TELEPHONE_TOO_LARGE_MESSAGE);
            default:
                return false;
        }
    }

    public ProfilePage page(){
        return profilePage;
    }

    private ChangeNamePage getChangeNamePage() {
        return new ChangeNamePage(pageNavigator.getDriver());
    }

    private ChangeEmailDetailsPage getEmailChangePage() {
        return new ChangeEmailDetailsPage(pageNavigator.getDriver());
    }

    private ChangeTelephoneDetailsPage getTelephoneChangePage() {
        return new ChangeTelephoneDetailsPage(pageNavigator.getDriver());
    }
}
