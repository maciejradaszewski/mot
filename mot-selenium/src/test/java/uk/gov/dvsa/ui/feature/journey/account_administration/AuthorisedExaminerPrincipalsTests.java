package uk.gov.dvsa.ui.feature.journey.account_administration;

import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.AeDetails;
import uk.gov.dvsa.domain.model.PersonDetails;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.AreaOfficerAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.Aep.CreateAepPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.Aep.ReviewCreateAepPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.Aep.RemoveAepPage;
import uk.gov.dvsa.ui.pages.authorisedexaminer.AuthorisedExaminerViewPage;
import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.IsEqual.equalTo;


import java.io.IOException;
import java.net.URISyntaxException;

public class AuthorisedExaminerPrincipalsTests extends BaseTest {

    private AeDetails ae = null;
    private PersonDetails aep = null;
    private User areaOffice1User = null;

    @Test(groups = {"Regression", "BL-21"})
    public void createAepSuccessfully() throws IOException, URISyntaxException {
        //GIVEN I am logged in as AreaOffice1User
        ae = aeData.createAeWithDefaultValues();
        areaOffice1User = userData.createAreaOfficeOne("AreaOfficer12");

        AreaOfficerAuthorisedExaminerViewPage aePage = pageNavigator.goToPageAsAuthorisedExaminer(areaOffice1User,
                AreaOfficerAuthorisedExaminerViewPage.class,
                AreaOfficerAuthorisedExaminerViewPage.PATH,
                ae.getId()
        );

        //WHEN I create new Aep
        CreateAepPage createAepPage = aePage.clickCreateAepLink(ae.getIdAsString());
        aep = new PersonDetails();

        //AND I fill in AEP data
        createAepPage.fillInForm(aep);

        //THEN AEP is succesfully created
        //todo probably need to validate fields displayed on AEP list
        ReviewCreateAepPage reviewCreateAepPage = createAepPage.submitForm();
        AuthorisedExaminerViewPage AEViewPage = reviewCreateAepPage.submitForm();
        assertThat(AEViewPage.getValidationMessage(), equalTo(aep.getFirstName() + " " + aep.getLastName() +
                " has been added as a Authorised Examiner Principal."));
    }

    @Test(groups = {"Regression", "BL-21"}, dependsOnMethods = {"createAepSuccessfully"})
    public void removeAepSuccessfully() throws IOException, URISyntaxException {
        //GIVEN I am logged in as AreaOffice1User
        AreaOfficerAuthorisedExaminerViewPage aePage = pageNavigator.goToPageAsAuthorisedExaminer(areaOffice1User,
                AreaOfficerAuthorisedExaminerViewPage.class,
                AreaOfficerAuthorisedExaminerViewPage.PATH,
                ae.getId()
        );

        //WHEN I remove AEP from AE
        RemoveAepPage removeAepPage = aePage.clickRemoveAepLink();
        AuthorisedExaminerViewPage aeViewPage = removeAepPage.submitForm();

        //THEN AEP is successfully removed
        assertThat(aeViewPage.getValidationMessage(), equalTo(aep.getFirstName() + " " + aep.getLastName() +
                " has been removed successfully."));
    }
}
