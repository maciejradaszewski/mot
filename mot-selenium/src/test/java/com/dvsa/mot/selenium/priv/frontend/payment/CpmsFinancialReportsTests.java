package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.FinancialReportDownloadPage;
import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsFinancialReportsTests extends BaseTest {

    private FinancialReportDownloadPage generateFinancialReports(Login login, String reportType,
            String reportTitle) {
        FinancialReportDownloadPage financialReportDownloadPage =
                UserDashboardPage.navigateHereFromLoginPage(driver, login)
                        .clickGeneralFinancialReportsLink().selectReportType(reportType)
                        .clickGenerateReportButton(reportTitle);
        return financialReportDownloadPage;
    }

    @Test(groups = {"Regression", "SPMS-137"})
    public void generateFinancialReportForAllPaymentsTest() {

        FinancialReportDownloadPage financialReportDownloadPage =
                generateFinancialReports(Login.LOGIN_FINANCE_USER, "All Payments",
                        "All payments report");

        assertThat("Verifying Back to generate report link displayed",
                financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-138"})
    public void generateFinancialReportForTransactionBreakdown() {

        FinancialReportDownloadPage financialReportDownloadPage =
                generateFinancialReports(Login.LOGIN_FINANCE_USER, "Transaction Breakdown",
                        "Transaction breakdown report");

        assertThat("Verifying Back to generate report link displayed",
                financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(), is(true));
    }

    @Test(groups = {"slice_A", "SPMS-265"})
    public void generateFinancialReportForGeneralLedger() {

        FinancialReportDownloadPage financialReportDownloadPage =
                generateFinancialReports(Login.LOGIN_FINANCE_USER, "General Ledger",
                        "General Ledger Report");

        assertThat("Verifying Back to generate report link displayed",
                financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(), is(true));
    }

}
