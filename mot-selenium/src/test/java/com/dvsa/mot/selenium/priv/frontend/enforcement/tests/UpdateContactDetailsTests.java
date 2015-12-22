package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.*;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.AreaOffice1UserCreationApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.AuthorisedExaminerFullDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.UpdateAeContactDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.UpdateVtsContactDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.equalTo;
import static org.hamcrest.Matchers.is;

public class UpdateContactDetailsTests extends BaseTest {

    BusinessDetails businessDetails = BusinessDetails.BUSINESS_DETAILS_1;
    Business business = Business.BUSINESS_1;
    VTSDetails vtsDetails = VTSDetails.VTSDetails1;

    //UPDATE AE/VTS CONTACT DETAILS- invalid data

    @Test(groups = {"VM-7284", "VM-7285", "Sprint12", "V", "Test 01", "Test 03", "Test 04",
            "Test 05", "Test 06", "current", "Regression", "VM-2394", "Sprint 25",})

    public void updateAeAndVTSContactDetailsAsExternalUserwithInvalidData() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("testUpdateAEContactDetails");

        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, aedmLogin);

        //clicking AE link
        userDashboardPage.clickAeNameLink(aeDetails);

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                new AuthorisedExaminerOverviewPage(driver);

        //checking name of created AE page title
        assertThat(" Verify AE Details ", authorisedExaminerOverviewPage.getAeNameDetails(),
                equalTo("Authorised Examiner\n" + aeDetails.getAeName()));

        //verify AE page elements for VTS roles
        authorisedExaminerOverviewPage.isAePageElementsForVtsUsersDisplayed();

        //click contact details link and enter invalid confirm email address
        authorisedExaminerOverviewPage.clickUpdateContactDetailsLinkForAe()
                .wrongEmailAddressForAe(businessDetails, BusinessDetails.BUSINESS_DETAILS_2)
                .submitChangesForAe();

        UpdateAeContactDetailsPage updateAeDetails = new UpdateAeContactDetailsPage(driver);

        //verify confirm email address validation
        assertThat("Verify Email address is not matching Warning Message",
                updateAeDetails.getValidationMsg(),
                equalTo(Assertion.ASSERTION_VALIDATION_MESSAGE.assertion));

        //checking Change contact details page title of AE
        Assert.assertEquals(updateAeDetails.getPageTitle(),
                "AUTHORISED EXAMINER - " + aeDetails.getAeRef() + "\n" + "CHANGE CONTACT DETAILS",
                " Verify AE Change Contact Details form Title ");

        //verify warning message
        assertThat("Verify Email Warning Message", updateAeDetails.getEmailWarningTextForAe(),
                equalTo(Assertion.ASSERTION_EMAIL_WARNING.assertion));

        //cancel AE update details
        updateAeDetails.changeContactDetailsFormForAe(businessDetails, business)
                .cancelAeUpdatesForAe();

        assertThat(" Verify AE Details ", authorisedExaminerOverviewPage.getAeNameDetails(),
                equalTo("Authorised Examiner\n" + aeDetails.getAeName()));
    }

    //UPDATE AE/VTS CONTACT DETAILS- valid data

    @Test(groups = {"VM-7284", "VM-7285", "Sprint12", "V", "Test 01", "Test 03", "Test 04",
            "Test 05", "Test 06", "current", "Regression", "VM-2394", "Sprint 25",})

    public void updateAeAndVTSContactDetailsAsExternalUserwithValidData() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("testUpdateAEContactDetails");
        String siteName = "NewVTS";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);

        Login aedmLogin = createAEDM(aeDetails.getId(), Login.LOGIN_AREA_OFFICE2, false);

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, aedmLogin);

        //clicking AE link
        userDashboardPage.clickAeNameLink(aeDetails);

        AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage =
                new AuthorisedExaminerOverviewPage(driver);

        //checking name of created AE page title
        assertThat(" Verify AE Details ", authorisedExaminerOverviewPage.getAeNameDetails(),
                equalTo("Authorised Examiner\n" + aeDetails.getAeName()));

        //verify AE page elements for VTS roles
        authorisedExaminerOverviewPage.isAePageElementsForVtsUsersDisplayed();

        //change contact details
        authorisedExaminerOverviewPage.clickUpdateContactDetailsLinkForAe().
                changeContactDetailsFormForAe(businessDetails, business).submitChangesForAe();

        //verify changed correspondence email address
        assertThat(" correct AE correspondence EmailAddress ",
                authorisedExaminerOverviewPage.getEmailAddress(),
                equalTo(businessDetails.emailAdd));

        //verify changed correspondence phone number
        assertThat("Correct contact number", authorisedExaminerOverviewPage.getContactNumber(),
                equalTo(businessDetails.phoneNo));

        //verify changed correspondence address
        String expectedAddress = business.busAddress.getLine1() + ",\n" + business.busAddress.getLine2()
                + ",\n" + business.busAddress.getLine3() + ",\n" + business.busAddress
                .getTown() + ",\n" + business.busAddress.getPostcode();
        assertThat("correct address", authorisedExaminerOverviewPage.getCorrespondenceAddress().
                contains(expectedAddress));

        //return to home page
        authorisedExaminerOverviewPage.returnHomeButton().openVtsDetails();

        //Update VTS contact details
        SiteDetailsPage siteDetailsPage = new SiteDetailsPage(driver);

        //Verify VTS name
        assertThat(" Verify VTS Details ", siteDetailsPage.getVTSName(),
                equalTo("Vehicle Testing Station" + "\n" +
                        site.getName()));

        //update VTS contact details link
        siteDetailsPage.clickUpdateContactDetailsLinkForVts();

        UpdateVtsContactDetailsPage updateVtsDetails = new UpdateVtsContactDetailsPage(driver);

        //verify change contact details page title
        assertThat(" Verify VTS Change Contact Details form Title ",
                updateVtsDetails.getVtsChangeContactDetailsPageTitle(),
                equalTo("Vehicle testing station - " + site.getNumber() + "\n"
                        + "Change contact details"));

        //email warning text
        assertThat("Verify Email Warning Message", updateVtsDetails.getEmailWarningTextForVts(),
                equalTo(Assertion.ASSERTION_EMAIL_WARNING.assertion));

        //vts cancel contact details form
        updateVtsDetails.changeContactDetailsFormForVTS(vtsDetails).cancelUpdatesForVts();

        //vts change contact details form and submit
        siteDetailsPage.clickUpdateContactDetailsLinkForVts()
                .changeContactDetailsFormForVTS(vtsDetails).submitChangesForVts();

        //verify changed email address
        assertThat(" Verify VTS New Email Address ", siteDetailsPage.getEmailAddress(),
                equalTo(VTSDetails.VTSDetails1.emailAdd));

        //verify changed contact number
        assertThat(" Verify VTS New Telephone Number ", siteDetailsPage.getSiteContactNumber(),
                equalTo(VTSDetails.VTSDetails1.phoneNo));
    }

    // Update AE/VTS contact details - DVSA user

    @Test(groups = {"VM-7284", "VM-7285", "Sprint12", "V", "Test 07", "Test 08", "Test 02",
            "current", "Regression", "VM-3734", "VM-2378", "Sprint26", "Enf", "E2E", "VM-7275",
            "VM-2394", "Sprint 25",}) public void updateAeAndVTSContactDetailsAsInternalUser() {

        AeService aeService = new AeService();
        AeDetails aeDetails = aeService.createAe("testUpdateAEContactDetails");
        String siteName = "NewVTS";
        Site site = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        siteName);

        Login areaOfficeUser = new AreaOffice1UserCreationApi().createAreaOffice1User();

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, areaOfficeUser);

        //Search for AE and submit
        userDashboardPage.clickListAllAEs().submitSearchForAe(aeDetails.getAeRef());

        AuthorisedExaminerFullDetailsPage authorisedExaminerFullDetailsPage =
                new AuthorisedExaminerFullDetailsPage(driver);

        //Verify page title for DVSA user AE page
        assertThat(" Verify AE Details ", authorisedExaminerFullDetailsPage.getAeNameDetails(),
                equalTo("Authorised Examiner\n" + aeDetails.getAeName()));

        //verify AE Business details elements
        authorisedExaminerFullDetailsPage.verifyAePageElementsDVSAUsers();

        //Click update contact details for AE
        authorisedExaminerFullDetailsPage.clickUpdateContactDetailsLinkForAe();

        UpdateAeContactDetailsPage updateAeDetails = new UpdateAeContactDetailsPage(driver);

        //checking Change contact details page title of AE
        assertThat(" Verify AE Change Contact Details form Title ", updateAeDetails.getPageTitle(),
                equalTo("AUTHORISED EXAMINER - " + aeDetails.getAeRef() + "\n"
                        + "CHANGE CONTACT DETAILS"));

        //update contact details of AE
        updateAeDetails.changeContactDetailsFormForAe(businessDetails, business).submitAeChanges();

        //verify changed correspondence email address
        assertThat(" incorrect AE correspondence EmailAddress ",
                authorisedExaminerFullDetailsPage.getCorrespondenceEmail(),
                equalTo(businessDetails.emailAdd));

        //verify changed correspondence phone number
        assertThat("Incorrect contact number",
                authorisedExaminerFullDetailsPage.getCorrespondencePhoneNo(),
                equalTo(businessDetails.phoneNo));

        //verify changed correspondence address
        Assert.assertTrue(authorisedExaminerFullDetailsPage.getCorrAddress().
                        contains(business.busAddress.getLine1() + ",\n" + business.busAddress
                                .getLine2() + ",\n" + business.busAddress.getLine3() + ",\n"
                                + business.busAddress.getTown() + ",\n" + business.busAddress
                                .getPostcode()), "Incorrect address");

        //click search again/return home link on AE pages And Search for VTS, update contact details and check for no email address tick box
        authorisedExaminerFullDetailsPage.searchAgainButton().clickReturnHomeLink()
                .clickSiteInformationLink().enterSiteName(site.getNumber()).submitSearchExpectingDetailsPage()
                .clickUpdateContactDetailsLinkForVts().changeContactDetailsFormForVTS(vtsDetails)
                .noEmailAddressForVts().submitChangesForVts();

        SiteDetailsPage siteDetailsPage = new SiteDetailsPage(driver);
        //verify changed email address
        assertThat(" Verify VTS New Email Address ", siteDetailsPage.isEmailAddPresent(), is(true));

        //verify changed contact number
        assertThat(" Verify VTS New Telephone Number ", siteDetailsPage.getSiteContactNumber(),
                equalTo(VTSDetails.VTSDetails1.phoneNo));

    }

}



