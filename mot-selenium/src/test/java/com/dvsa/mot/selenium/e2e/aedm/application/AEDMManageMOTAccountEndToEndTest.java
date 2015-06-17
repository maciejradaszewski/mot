//package com.dvsa.mot.selenium.e2e.aedm.application;
//
//import com.dvsa.mot.selenium.datasource.Business;
//import com.dvsa.mot.selenium.datasource.Login;
//import com.dvsa.mot.selenium.framework.BasePublicFrontendTest;
//import com.dvsa.mot.selenium.framework.RandomDataGenerator;
//import com.dvsa.mot.selenium.framework.Utilities;
//import com.dvsa.mot.selenium.framework.api.*;
//import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.AuthorisedExaminerOverviewPage;
//import com.dvsa.mot.selenium.priv.frontend.organisation.management.authorisedexamineroverview.pages.ChangeAeDetailsPage;
//import com.dvsa.mot.selenium.priv.frontend.user.UserDashboardPage;
//import org.testng.annotations.Test;
//
//import java.inline.Arrays;
//import java.inline.Collection;
//import java.inline.Collections;
//
///**
// * Created by MH on 26/08/2014.
// */
//
//public class AEDMManageMOTAccountEndToEndTest extends BasePublicFrontendTest {
//
//    private int aeId;
//    private Login aedmLogin;
//
//    @Test
//    public void AEDMSelfServiceDetailChangeForAE() {
//        // User Journey 2
//        // Log in as AEDM
//        // Select AE to manage
//        //int authorisedExaminer = new AeCreationApi().createAe();
//
//        createAe1();
//
//        //Login login = Login.LOGIN_AEDM;
//        Business business = Business.ISIS_INC;
//
//        UserDashboardPage.navigateHereFromLoginPage(driver, aedmLogin);
//        //AuthorisedExaminerOverviewPage authorisedExaminerOverviewPage = AuthorisedExaminerOverviewPage.navigateHereFromLoginPage(driver, aedmLogin, business);
//
//        // View AE detail
//
//        System.out.println("");
//        // Make variation to VTS (1)
//
//        // Make variation to AE detail (2)
//
//        // Request changes to MOT Authorisation (VT01)
//
//        // Lead Office: Authorise AE Authorisation Change
//
//        // Lead Office: Update AE details
//
//        // Lead Office: Confirm AE Authorisation changes
//
//        // Authorisation changed.
//    }
//
//    private void createAe1() {
//        String aeName = RandomDataGenerator.generateRandomString(5, System.nanoTime());
//
//        aeId = createAE(aeName);
//        Login aedmLogin = new Login(aeName + "-aedm", "Password1");
//
//        createAEDM(aeId, aeName);
//        printNewLoginDetails(aedmLogin, "AEDM");
//
//        String name = "vtsA";
//        int vtsA = createVTS(aeId, null, aeName + "-" + name);
//
//        Collection<Integer> id = Collections.singletonList(vtsA);
//        createTesters(15, "12", aeName, name, id, TestGroup.group1, TesterCreationApi.TesterStatus.QLFD);
////        createTesters(15, "3", aeDiff, vtsADiff, id, TestGroup.group2, TesterCreationApi.TesterStatus.QLFD);
////        createTesters(15, "all", aeDiff, vtsADiff, id, null, TesterCreationApi.TesterStatus.QLFD);
////
////        int vtsB12 = new VtsCreationApi().createVts(aeId, TestGroup.group1, aeDiff + "vtsB12");
////        int vtsC = new VtsCreationApi().createVts(aeId, null, aeDiff + "vtsC");
////
////        createTesters(15, "all", aeDiff, "vtsBC", Arrays.asList(vtsB12, vtsC), null, TesterCreationApi.TesterStatus.QLFD);
////
////        int vtsD = new VtsCreationApi().createVts(aeId, null, aeDiff + "-" + "vtsD");
////
////        createTesters(25, "all", aeDiff, "vtsABCD", Arrays.asList(vtsA, vtsB12, vtsC, vtsD), null, TesterCreationApi.TesterStatus.QLFD);
////
////        createTesters(10, "inact", aeDiff, vtsADiff, id, null, TesterCreationApi.TesterStatus.ITRN);
////
////        createTesters(10, "susp", aeDiff, vtsADiff, id, null, TesterCreationApi.TesterStatus.SPND);
//    }
//
//    private void createTesters(int count, String diffStart, String aeDiff,
//                               String vtsDiff, Collection<Integer> vtsIds, TestGroup testerGroup, TesterCreationApi.TesterStatus status) {
//        for (int i = 1; i <= count; i++) {
//            String testerDiff = String.valueOf(i);
//            Login tester12Login = new TesterCreationApi().createTester(vtsIds,
//                    testerGroup, status, diffStart + "-" + aeDiff + "-" + vtsDiff + "-"
//                            + testerDiff);
//            printNewLoginDetails(tester12Login, "tester class "
//                    + (null == testerGroup ? "all" : testerGroup.description));
//        }
//    }
//
//    private void printNewLoginDetails(Login newLogin, String loginType) {
//        Utilities.Logger.LogInfo("new " + loginType + " created: ["
//                + newLogin.username + "]/[" + newLogin.password + "]");
//    }
//}
