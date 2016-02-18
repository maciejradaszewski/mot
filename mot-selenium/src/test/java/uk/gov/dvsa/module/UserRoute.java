package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.helper.ConfigHelper;
import uk.gov.dvsa.module.userprofile.ChangeAddress;
import uk.gov.dvsa.module.userprofile.ChangeDOB;
import uk.gov.dvsa.module.userprofile.ChangeEmail;
import uk.gov.dvsa.module.userprofile.ChangeName;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.Page;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.*;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchProfilePage;
import uk.gov.dvsa.ui.pages.exception.PageInstanceNotFoundException;
import uk.gov.dvsa.ui.pages.profile.*;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

public class UserRoute {

    private PageNavigator pageNavigator;
    private ProfilePage profilePage;
    private ChangeName changeName;
    private ChangeEmail changeEmail;
    private ChangeDOB changeDOB;
    private ChangeAddress changeAddress;

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

    public boolean isValidationMessageOnChangeTelephonePageDisplayed(String warningMessage) {
        switch (warningMessage) {
            case "TELEPHONE_TOO_LARGE":
                return getTelephoneChangePage().getValidationMessage().equals(TELEPHONE_TOO_LARGE_MESSAGE);
            default:
                return false;
        }
    }

    public ProfilePage page(){
        if (profilePage == null) {
            throw new PageInstanceNotFoundException("Profile page wasn't instantiated");
        }
        return profilePage;
    }

    public ChangeName changeName() {
        if (changeName == null) {
            return this.changeName = new ChangeName(pageNavigator, page());
        }
        return changeName;
    }

    private ChangeTelephoneDetailsPage getTelephoneChangePage() {
        return new ChangeTelephoneDetailsPage(pageNavigator.getDriver());
    }

    public ChangeEmail changeEmail() {
        if (changeEmail == null) {
            return this.changeEmail = new ChangeEmail(pageNavigator, page());
        }
        return changeEmail;
    }

    public ChangeDOB changeDOB() {
        if (changeDOB == null) {
            return this.changeDOB = new ChangeDOB(page());
        }
        return changeDOB;
    }

    public ChangeAddress changeAddress() {
        if (changeAddress == null) {
            return this.changeAddress = new ChangeAddress(page());
        }
        return changeAddress;
    }
}