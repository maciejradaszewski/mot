package uk.gov.dvsa.ui.feature.journey;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.Test;
import uk.gov.dvsa.domain.model.User;
import uk.gov.dvsa.helper.CompanyDetailsHelper;
import uk.gov.dvsa.helper.ContactDetailsHelper;
import uk.gov.dvsa.ui.BaseTest;
import uk.gov.dvsa.ui.pages.AreaOfficerAuthorisedExaminerViewPage;
import uk.gov.dvsa.ui.pages.ConfirmNewAeDetailsPage;
import uk.gov.dvsa.ui.pages.CreateAePage;

import java.io.IOException;
import java.net.URISyntaxException;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.core.Is.is;

public class AuthorisedExaminerTest extends BaseTest {

    @BeforeMethod(alwaysRun = true) public void setup() {
        CompanyDetailsHelper.setCompanyDetails();
        ContactDetailsHelper.setContactDetails();
    }

    @Test(groups = {"FeatureToggleCreateAe"}) public void createAuthorisedExaminerSuccessfully()
            throws IOException, URISyntaxException {

        User areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");

        //Given I am on the Create AE screen as DVSA Admin user
        CreateAePage createAePage = pageNavigator.gotoCreateAePage(areaOffice1User);

        //When I Create an AE using valid business details as correspondence details
        AreaOfficerAuthorisedExaminerViewPage examinerViewPage =
                createAePage.completeBusinessAndCorrespondenceDetails(false).create();

        //Then the new AE is created with the data provided
        assertThat(examinerViewPage.verifyNewAeCreated(), is(true));
    }

    @Test(groups = {"FeatureToggleCreateAe"}) public void verifyConfirmAeDetailsPage()
            throws IOException, URISyntaxException {
        User areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");

        //Given I am on the Create AE screen as DVSA Admin user
        CreateAePage createAePage = pageNavigator.gotoCreateAePage(areaOffice1User);

        //When the the user has entered valid data and clicks the Continue to Summary button
        ConfirmNewAeDetailsPage confirmNewAeDetailsPage =
                createAePage.completeBusinessAndCorrespondenceDetails(false)
                        .clickContinueToSummary();

        //Then the data entered is displayed on the confirmation screen
        confirmNewAeDetailsPage.verifyNewAeDetailsOnConfirmationPage(false);
    }

    @Test(groups = {"FeatureToggleCreateAe"}) public void verifyRegEmailAddressFields()
            throws IOException, URISyntaxException {
        User areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");

        //Given I am on the Create AE screen as DVSA Admin user
        CreateAePage createAePage = pageNavigator.gotoCreateAePage(areaOffice1User);

        //When the user enters email address and selects 'Email address not provided' option
        createAePage.enterBusinessEmail(ContactDetailsHelper.email)
                .selectBusinessEmailNotProvidedOption();

        //Then the email address fields should be empty
        assertThat(createAePage.isBusinessEmailFieldsEmpty(), is(true));
    }

    @Test(groups = {"FeatureToggleCreateAe"}) public void verifyCorrEmailAddressFieldsEmpty()
            throws IOException, URISyntaxException {
        User areaOffice1User = userData.createAreaOfficeOne("AreaOfficer");

        //Given I am on the Create AE screen as DVSA Admin user
        CreateAePage createAePage = pageNavigator.gotoCreateAePage(areaOffice1User);

        //When the user enters email address and selects 'Email address not provided' option
        createAePage.selectBusinessDetailsSameAsCorrespondenceDetails(false)
                .enterCorrespondenceAddress().selectCorrespondenceEmailNotProvided();

        //Then the email address fields should be empty
        assertThat(createAePage.isCorrespondenceEmailFieldsEmpty(), is(true));
    }
}
