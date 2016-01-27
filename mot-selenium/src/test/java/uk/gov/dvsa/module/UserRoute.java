package uk.gov.dvsa.module;

import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.domain.navigation.PageNavigator;
import uk.gov.dvsa.helper.ConfigHelper;
import uk.gov.dvsa.ui.pages.HomePage;
import uk.gov.dvsa.ui.pages.ProfilePage;
import uk.gov.dvsa.ui.pages.dvsa.UserSearchProfilePage;
import uk.gov.dvsa.ui.pages.profile.NewPersonProfilePage;
import uk.gov.dvsa.ui.pages.profile.NewUserProfilePage;
import uk.gov.dvsa.ui.pages.profile.PersonProfilePage;
import uk.gov.dvsa.ui.pages.profile.ProfileOfUserPage;
import uk.gov.dvsa.ui.pages.vts.VehicleTestingStationPage;

import java.io.IOException;

public class UserRoute {
    PageNavigator pageNavigator;
    ProfilePage profilePage;

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

    public boolean isTesterQualificationStatusDisplayed() {
        return profilePage.isTesterQualificationStatusDisplayed();
    }

    public ProfilePage page(){
        return profilePage;
    }
}
