package com.dvsa.mot.selenium.e2e.support;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.*;

import javax.json.JsonObject;
import java.util.Collection;
import java.util.Collections;
import java.util.UUID;

/**
 * Created by bjss on 08/09/2014.
 */
public class EntityManager {


    //Helper methods (TODO: need to be moved to BaseApi class in future)

    public static String randomName() {
        return RandomDataGenerator.generateRandomString(10, System.nanoTime());
    }

    public static Login createOrg1(String newVtsName) {
        String aeName = randomName();

        int aeId = createAe(1000);
        Login schemeManagementUser =
                new SchemeManagementUserCreationApi().createSchemeManagementUser(aeName);
        JsonObject aedmData = new AedmCreationApi()
                .createAedm(Collections.singletonList(aeId), schemeManagementUser, aeName, false);
        Login aedmLogin = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));
        Login aed = createAed(aeName, aeId, schemeManagementUser);

        String vtsDiff = newVtsName;

        int vtsId = new VtsCreationApi()
                .createVts(aeId, TestGroup.group1, schemeManagementUser, aeName + "-" + vtsDiff);

        privateCreateTester("12", aeName, vtsDiff, Collections.singletonList(vtsId),
                TestGroup.group1, TesterCreationApi.TesterStatus.QLFD);

        createSiteManager(aeName, aed, vtsId);

        return aedmLogin;
    }

    public static Login createTesterAndAssociateToAE(String vtsDiff, int vtsId) {
        return privateCreateTester("12",
                RandomDataGenerator.generateRandomString(5, UUID.randomUUID().hashCode()), vtsDiff,
                Collections.singletonList(vtsId), TestGroup.group1,
                TesterCreationApi.TesterStatus.QLFD);
    }

    public static Login createAedmLoginCredentials(String username, String password) {
        Login login = new Login(username, password);
        printNewLoginDetails(login, "AEDM");
        return login;
    }

    public static Login createAed(String aeDiff, int aeId, Login schemeManagementUser) {
        Login aedLogin = new AedCreationApi()
                .createAed(Collections.singletonList(aeId), schemeManagementUser, aeDiff);
        printNewLoginDetails(aedLogin, "AED");
        return aedLogin;
    }

    public static void createSiteManager(String aeDiff, Login person, int vtsA) {
        Login siteManagerLogin = new SiteManagerCreationApi()
                .createSm(Collections.singletonList(vtsA), person, aeDiff + "-sm-vts" + vtsA);

        printNewLoginDetails(siteManagerLogin, "site manager");
    }

    public static int createAe(int slots) {
        return new AeCreationApi()
                .createAe(RandomDataGenerator.generateRandomString(5, UUID.randomUUID().hashCode()),
                        Login.LOGIN_AREA_OFFICE1, slots);
    }

    public static Login privateCreateTester(String diffStart, String aeDiff, String vtsDiff,
            Collection<Integer> vtsIds, TestGroup testerGroup,
            TesterCreationApi.TesterStatus status) {
        Login newTesterLogin;
        newTesterLogin = new TesterCreationApi()
                .createTester(vtsIds, testerGroup, status, Login.LOGIN_SCHEME_MANAGEMENT,
                        diffStart + "-" + aeDiff + "-" + vtsDiff + "-" + RandomDataGenerator
                                .generateRandomAlphaNumeric(5, UUID.randomUUID().hashCode()),
                        false, false);
        printNewLoginDetails(newTesterLogin,
                "tester class " + (null == testerGroup ? "all" : testerGroup.description));
        return newTesterLogin;
    }

    public static void printNewLoginDetails(Login newLogin, String loginType) {
        Utilities.Logger.LogInfo(
                "new " + loginType + " created: [" + newLogin.username + "]/[" + newLogin.password
                        + "]");
    }

}
