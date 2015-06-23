package com.dvsa.mot.selenium.priv.frontend.enforcement.tests;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Site;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeDetails;
import com.dvsa.mot.selenium.framework.api.authorisedexaminer.AeService;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.EnforcementHomePage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteDetailsPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteInformationSearchPage;
import com.dvsa.mot.selenium.priv.frontend.enforcement.pages.SiteSearchResultsPage;
import org.apache.commons.lang3.RandomStringUtils;
import org.testng.Assert;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.is;

public class SearchForSiteInformationTests extends BaseTest {

    @Test(groups = {"VM-7269", "Regression"}) public void siteInformationTests() {
        AeService aeService = new AeService();
        String aeName = RandomStringUtils.randomAlphabetic(6);
        String vtsOneName = RandomStringUtils.randomAlphabetic(6);
        AeDetails aeDetails = aeService.createAe(aeName);
        Site siteOne = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        vtsOneName);

        SiteDetailsPage siteDetailsPage = SiteInformationSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1)
                .enterSiteId(siteOne.getNumber()).enterSiteName(siteOne.getName())
                .enterSiteTown(siteOne.getContactDetails().getContactAddress().getTown())
                .enterSitePostCode(siteOne.getContactDetails().getContactAddress().getPostcode())
                .selectSiteClass1().selectSiteClass2().selectSiteClass3().selectSiteClass4()
                .selectSiteClass5().selectSiteClass7().submitSearchExpectingDetailsPage();

        Assert.assertTrue(siteDetailsPage.isVtsContactDetailsDisplayed());

        Site siteTwo = new VtsCreationApi()
                .createVtsSite(aeDetails.getId(), TestGroup.ALL, Login.LOGIN_AREA_OFFICE1,
                        vtsOneName);

        SiteSearchResultsPage siteSearchResultsPage = siteDetailsPage.clickSearchAgain()
                .enterSiteName(siteOne.getName()).
        enterSiteTown(siteTwo.getContactDetails().getContactAddress().getTown())
                .submitSearchExpectingResultsPage();

        Assert.assertTrue(siteSearchResultsPage.verifyFullTitle(siteTwo.getName() + ", " +siteTwo.getContactDetails().getContactAddress().getTown()).isTablePresent());

        siteSearchResultsPage.clickReturnToSiteSearchInformation()
                .submitSearchExpectingResultsPage();

        siteDetailsPage = siteSearchResultsPage.selectSiteLinkFromTable(siteTwo.getNumber());

        Assert.assertTrue(siteDetailsPage.isVtsContactDetailsDisplayed());

    }

    @Test(groups = {"VM-7269", "Regression"}) public void verifyInvalidSiteSearch() {
        SiteInformationSearchPage siteInformationSearchPage = SiteInformationSearchPage
                .navigateHereFromLoginPage(driver, Login.LOGIN_AREA_OFFICE1)
                .submitSearchExpectingSiteSearchPage();

        assertThat("Search criteria message is not displayed",
                siteInformationSearchPage.getValidationMessageFailure(),
                containsString(Assertion.ASSERTION_SITE_SEARCH.assertion));

        siteInformationSearchPage.enterSiteId(RandomStringUtils.randomAlphabetic(6))
                .submitSearchExpectingSiteSearchPage();

        assertThat("Invalid search message is not displayed",
                siteInformationSearchPage.getValidationMessageFailure(),
                containsString(Assertion.ASSERTION_SITE_INVALID_SEARCH.assertion));

    }
}
