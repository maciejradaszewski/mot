package com.dvsa.mot.selenium.priv.frontend.payment;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.BaseTest;
import com.dvsa.mot.selenium.framework.api.FinanceUserCreationApi;
import com.dvsa.mot.selenium.priv.frontend.payment.pages.FinancialReportDownloadPage;
import org.testng.annotations.Test;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class CpmsFinancialReportsTests extends BaseTest {
    
    private Login createFinanceUserReturnFinanceUserLogin() {
        FinanceUserCreationApi financeUserCreationApi = new FinanceUserCreationApi();
        Login financeUserLogin = financeUserCreationApi.createFinanceUser().getLogin();
        return financeUserLogin;
    }

    @Test(groups = {"Regression", "SPMS-137"})
    public void generateFinancialReportForAllPaymentsTest() {
        
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        FinancialReportDownloadPage financialReportDownloadPage = FinancialReportDownloadPage
                .navigateHereFromLoginAndGenerateFinancialReports(driver, financeUserLogin, "All Payments", "All payments report");

        assertThat("Verifying Back to generate report link displayed",
                financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-138"})
    public void generateFinancialReportForTransactionBreakdown() {
        
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        FinancialReportDownloadPage financialReportDownloadPage = FinancialReportDownloadPage
                .navigateHereFromLoginAndGenerateFinancialReports(driver, financeUserLogin, "Transaction Breakdown", "Transaction breakdown report");
        
        assertThat("Verifying Back to generate report link displayed",
                financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(), is(true));
    }

    @Test(groups = {"Regression", "SPMS-265"})
    public void generateFinancialReportForGeneralLedger() {
        
        Login financeUserLogin = createFinanceUserReturnFinanceUserLogin();
        FinancialReportDownloadPage financialReportDownloadPage = FinancialReportDownloadPage
                .navigateHereFromLoginAndGenerateFinancialReports(driver, financeUserLogin, "General Ledger", "General Ledger Report");

        assertThat("Verifying Back to generate report link displayed",
                financialReportDownloadPage.isBackToGenerateReportLinkDisplayed(), is(true));
    }

}
