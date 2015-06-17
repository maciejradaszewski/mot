package com.dvsa.mot.selenium.priv.frontend.vehicletest;

import com.dvsa.mot.selenium.datasource.Assertion;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.SpecialNotice;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.MarkDown;
import com.dvsa.mot.selenium.framework.api.SchemeUserCreationApi;
import com.dvsa.mot.selenium.framework.util.validation.ValidationSummary;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.CreateSpecialNoticePage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.SpecialNoticePreviewPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.SpecialNoticesPage;
import com.dvsa.mot.selenium.priv.frontend.vehicletest.pages.TradeUserSpecialNotice;
import org.joda.time.DateTime;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;
import static org.testng.Assert.assertTrue;

public class SpecialNoticesTest extends BaseTest {

    @Test(groups = {"slice_A", "VM-1965", "VM-1966", "VM-1967"})
    public void testUserHasNoUnreadSpecialNotices() {

        SpecialNoticesPage specialNoticesPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login).viewNotices();


        assertThat("Check unread special notice messages",
                specialNoticesPage.getUnreadSpecialNoticesMessage(),
                is(Assertion.ASSERTION_NO_UNREAD_SPECIAL_NOTICE_MESSAGE.assertion));

        UserDashboardPage dashboardPage = specialNoticesPage.returnToDashboard();

        assertThat("Start MOT test link present", dashboardPage.existStartMotTestButton(),
                is(true));
    }

    @Test(groups = {"slice_A", "VM-2309"}, enabled = true)
    public void testSNMandateFieldsForCreationAndUser2AccessSNCreatedByUser1() {

        final String TEXT_LINE = "Mark down test line";
        String TITLE = "Verify Special Notice Created";
        int specialNotice = createSpecialNotice(DateTime.now(), true, TITLE);
        SchemeUserCreationApi schemeUserCreationApi = new SchemeUserCreationApi();
        Login schemeUser2 = schemeUserCreationApi.createSchemeUser();

        UserDashboardPage userDashboardPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_USER);
        userDashboardPage.viewNotices().createSpecialNotice().submitExpectingError();
        CreateSpecialNoticePage createSpecialNoticePage = new CreateSpecialNoticePage(driver);

        createSpecialNoticePage.enterTitle(SpecialNotice.SPECIAL_NOTICE_VALID.title)
                .submitExpectingError();

        assertThat("Assert that acknowledge period invalid",
                createSpecialNoticePage.isAcknowledgePeriodInvalid(), is(true));

        createSpecialNoticePage.enterAcknowledgePeriod(
                SpecialNotice.SPECIAL_NOTICE_VALID.acknowledgementPeriod.getId())
                .submitExpectingError().enterBody(TEXT_LINE)
                .enterRecipients(SpecialNotice.SPECIAL_NOTICE_VALID.recipients).submit();

        assertThat("Check error message", createSpecialNoticePage.getErrorMessage(),
                is(Assertion.ASSERTION_DATE_INPUT_NEEDED.assertion));

        createSpecialNoticePage.clickHome().clickLogout().loginAsUser(schemeUser2).viewNotices();

        SpecialNoticesPage specialNoticesPage = new SpecialNoticesPage(driver);

        assertThat("Wrong title", specialNoticesPage.getTitle(specialNotice), is(TITLE));
    }


    @Test(groups = {"slice_A", "VM-1965", "VM-1966", "VM-1967", "VM-1991", "VM-1992", "VM-2312"})
    public void testTesterHasOverdueSNAcknowledgeItPrintAndStartMotTestLinkPresent() {
        Login testerWithOverdueSpecialNotices = createTester();

        int specialNotice =
                createSpecialNotice(DateTime.now(), true, "Overdue special notice to Acknowledge");
        broadcastSpecialNotice(testerWithOverdueSpecialNotices.username, specialNotice, false);

        UserDashboardPage dashboardPage = UserDashboardPage
                .navigateHereFromLoginPage(driver, testerWithOverdueSpecialNotices);
        //Demo Test link present before acknowledge Special Notice
        assertTrue(dashboardPage.isStartMotTrainingModeLinkClickable(),
                "Start Demo Test link present and clickable");

        TradeUserSpecialNotice specialNoticesPage =
                dashboardPage.viewOverdueTradeUserUnreadSpecialNotices()
                        .acknowledgeOverdueSpecialNotice(specialNotice);

        assertThat("Print button should be present!",
                specialNoticesPage.isPrintButtonPresentInCurrentNotices(specialNotice), is(true));
        assertThat("Remove button shouldn't be present!",
                specialNoticesPage.removeButtonNotPresent(specialNotice), is(true));
        assertThat("Acknowledge button shouldn't be present!",
                specialNoticesPage.acknowledgeButtonNotPresent(specialNotice), is(true));
        assertThat(specialNoticesPage.verifySpecialNoticeInfoMessage(),
                is(Assertion.ASSERTION_SPECIAL_NOTICE_ACKNOWLEDGED.assertion));

        specialNoticesPage.printCurrentSpecialNotice(specialNotice).clickHome();

        assertThat("Start MOT test button not displayed", dashboardPage.isStartMotTestDisplayed(),
                is(true));
    }

    @Test(groups = {"VM-2311", "VM-2315"},
            description = "DVSA Scheme User can search for and is presented with a list of current special notices, can also remove notices")
    public void testDVSASchemeUserCanRemoveNotices() {

        int specialNoticeRemoved =
                createSpecialNotice(DateTime.now(), true, "Verify Special Notice Removed");

        SpecialNoticesPage specialNoticesPage = SpecialNoticesPage
                .navigateHereFromLoginPageAsDVSAUser(driver, Login.LOGIN_SCHEME_USER)
                .removeSpecialNotice(specialNoticeRemoved);

        //message displayed after removing special notice
        assertThat("Verify special notice information message",
                specialNoticesPage.verifySpecialNoticeInfoMessage(),
                is(Assertion.ASSERTION_SPECIAL_NOTICE_REMOVED.assertion));
        //check remove button not displayed
        assertThat("Remove button shouldn't be present",
                specialNoticesPage.removeButtonNotPresent(specialNoticeRemoved), is(true));
    }

    @Test(groups = {"slice_A", "VM-2309", "VM-2997"})
    public void testVerifyDraftCreateNewSpecialNotice() {

        int specialNoticeDraft =
                createSpecialNotice(DateTime.now(), false, "Verify Special Notice draft");

        SpecialNoticesPage specialNoticesPage = SpecialNoticesPage
                .navigateHereFromLoginPageAsDVSAUser(driver, Login.LOGIN_SCHEME_USER);

        assertThat("Verify special notice created as draft",
                specialNoticesPage.getStatus(specialNoticeDraft),
                is(Assertion.ASSERTION_NOTICE_STATUS.assertion));
    }

    @Test(groups = {"slice_A", "VM-2313", "Sprint 21", "MOT Testing"},
            description = "Test the different 'mark downs' used when we edit a new special notice are formatted correctly.")
    public void testSpecialNoticesMarkDown() {

        final String TEXT_LINE = "Mark down test line";

        SpecialNoticePreviewPage specialNoticePreviewPage =
                CreateSpecialNoticePage.navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_USER)
                        .enterSpecialNotice(SpecialNotice.SPECIAL_NOTICE_VALID).clearBody()
                        .enterBody(MarkDown.getTextAsItalic(TEXT_LINE))
                        .enterBody(MarkDown.getNewLine())
                        .enterBody(MarkDown.getTextAsBold(TEXT_LINE))
                        .enterBody(MarkDown.getNewLine())
                        .enterBody(MarkDown.getTextAsLargeHeader(TEXT_LINE))
                        .enterBody(MarkDown.getNewLine())
                        .enterBody(MarkDown.getTextAsMediumHeader(TEXT_LINE))
                        .enterBody(MarkDown.getNewLine())
                        .enterBody(MarkDown.getTextAsSmallHeader(TEXT_LINE)).submit();

        final String PREVIEW_PAGE_SOURCE = specialNoticePreviewPage.getPageSource();

        assertThat("Assert italic style",
                MarkDown.existTextAsItalic(PREVIEW_PAGE_SOURCE, TEXT_LINE), is(true));
        assertThat("Assert bold style", MarkDown.existTextAsBold(PREVIEW_PAGE_SOURCE, TEXT_LINE),
                is(true));
        assertThat("Assert Large header style",
                MarkDown.existTextAsLargeHeader(PREVIEW_PAGE_SOURCE, TEXT_LINE), is(true));
        assertThat("Assert Medium header style",
                MarkDown.existTextAsMediumHeader(PREVIEW_PAGE_SOURCE, TEXT_LINE), is(true));
        assertThat("Assert Small header style",
                MarkDown.existTextAsSmallHeader(PREVIEW_PAGE_SOURCE, TEXT_LINE), is(true));
    }

    @Test(groups = {"slice_A", "VM-7844"})
    public void testSpecialNoticesMarkdownCannotContainJavascript() {

        CreateSpecialNoticePage.navigateHereFromLoginPage(driver, Login.LOGIN_SCHEME_USER)
                .enterSpecialNotice(SpecialNotice.SPECIAL_NOTICE_CONTAINING_JAVASCRIPT).submit();

        assertThat("Check mark down error message",
                ValidationSummary.isValidationSummaryDisplayed(driver), is(true));
    }

    @Test(groups = {"VM-3547", "Sprint 24B", "MOT Testing", "slice_A"},
            description = "DVSA Area Admin User can search for and is presented with a list of current special notices, can also edit the notices")
    public void testDVSASchemeUserCanEditNotices() {

        final String TEXT_LINE = "Mark down test line";
        String TITLE = "TEST TITLE";
        int draftSpecialNotice = createSpecialNotice(DateTime.now(), false, "Draft special notice");

        SpecialNoticesPage specialNoticesPage = SpecialNoticesPage
                .navigateHereFromLoginPageAsDVSAUser(driver, Login.LOGIN_SCHEME_USER);
        SpecialNoticePreviewPage specialNoticePreviewPage =
                specialNoticesPage.editSpecialNotice(draftSpecialNotice)
                        .enterSpecialNotice(SpecialNotice.SPECIAL_NOTICE_VALID).clearBody()
                        .enterBody(MarkDown.getTextAsItalic(TEXT_LINE)).clearTitle()
                        .enterTitle(TITLE).submit();

        assertThat("Wrong subject message", specialNoticePreviewPage.getSubjectMessage(),
                is(TEXT_LINE));
        assertThat("Wrong Title", specialNoticePreviewPage.getTitle(), is(TITLE));
        assertThat("Check if published notice is displayed",
                specialNoticePreviewPage.isPublishSpecialNoticeDisplayed(), is(true));

    }

    @Test(groups = {"slice_A", "VM-4517"}, description = "user who cannot create special notices")
    public void testUserWhoCannotCreateSpecialNotices() {

        SpecialNoticesPage specialNoticesPage =
                SpecialNoticesPage.navigateHereFromLoginPageAsTesterUser(driver, Login.LOGIN_AEDM);

        assertThat("Create Special notice link present",
                specialNoticesPage.isCreateSpecialNoticeLinkNotPresent(), is(false));
        assertThat("Remove special notices link present",
                specialNoticesPage.isRemoveLinkNotPresent(), is(false));
    }
}
