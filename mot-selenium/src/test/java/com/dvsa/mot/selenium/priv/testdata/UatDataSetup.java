package com.dvsa.mot.selenium.priv.testdata;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.Configurator;
import com.dvsa.mot.selenium.framework.api.*;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi.TesterStatus;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.DataGeneratorForTradeUser;

import javax.json.JsonObject;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

import static com.dvsa.mot.selenium.framework.api.TestGroup.group1;
import static com.dvsa.mot.selenium.framework.api.TestGroup.group2;
import static com.dvsa.mot.selenium.priv.testdata.UatVehicleDataGenerator.VtsData;
import static com.dvsa.mot.selenium.priv.testdata.UatVehicleDataGenerator.VtsDataCollector;
import static java.util.Arrays.asList;

public class UatDataSetup extends Configurator {

    private static final String MASTER_DIFF = "0-";

    private Login schemeManagementUser;

    private Login areaOffice2User;

    public static void main(String[] args) {
        System.out.println("START");
        new UatDataSetup().createUatData();
        System.out.println("COMPLETED");
    }

    /**
     * Generates data for UAT, can take several minutes. Be patient.
     */
    public void createUatData() {

        new DataGeneratorForTradeUser().generateData();

        schemeManagementUser = new SchemeManagementUserCreationApi()
                .createSchemeManagementUser("RootSCH");

        areaOffice2User = new AreaOffice2UserCreationApi().createAreaOffice2User("aouO2");

        printNewLoginDetails(schemeManagementUser, "Scheme Management");

        createAreaOffice1Users(10);
        createVehicleExaminerUsers(10);
        createAssessorUsers(10);
        createUsers(10);

        VtsDataCollector vtsDataCollector = new VtsDataCollector();

        createOrg1(vtsDataCollector);
        createOrg2(vtsDataCollector);
        createOrg3(vtsDataCollector);
        createOrg4(vtsDataCollector);
        createOrg5(vtsDataCollector);
        createOrg6(vtsDataCollector);
        createOrg7(vtsDataCollector);
        createOrg8(vtsDataCollector);
        createOrg9(vtsDataCollector);
        createOrg10(vtsDataCollector);
        createOrg11(vtsDataCollector);
        createOrg12(vtsDataCollector);
        createOrg14(vtsDataCollector);
        createOrg15(vtsDataCollector);
        createOrg16(vtsDataCollector);
        createOrg21(vtsDataCollector);
        createOrg22(vtsDataCollector);
        createOrg23(vtsDataCollector);
        createOrg24(vtsDataCollector);
        createOrg25(vtsDataCollector);
        createOrg26(vtsDataCollector);
        createOrg27(vtsDataCollector);

        new UatVehicleDataGenerator().genTestHistory(vtsDataCollector);
    }

    private Login createAreaOffice1(String diff) {
        return new AreaOffice1UserCreationApi().createAreaOffice1User(diff);
    }

    private void createAreaOffice1Users(int count) {
        for (int i = 1; i <= count; i++) {
            Login areaOffice1 = createAreaOffice1(MASTER_DIFF + "UAT-area-admin-" + i);
            printNewLoginDetails(areaOffice1, "DVSA Area Office 1");
        }
    }

    private Login createVehicleExaminer(String diff) {
        return new VehicleExaminerUserCreationApi().createVehicleExaminerUser(diff);
    }

    private void createVehicleExaminerUsers(int count) {
        for (int i = 1; i <= count; i++) {
            Login vehicleExaminer = createVehicleExaminer(MASTER_DIFF + "UAT-vehicle-examiner" + i);
            printNewLoginDetails(vehicleExaminer, "Vehicle Examiner");
        }
    }

    private Login createAssessor(String diff) {
        return new AssessorUserCreationApi().createAssessorUser(diff);
    }

    private void createAssessorUsers(int count) {
        for (int i = 1; i <= count; i++) {
            Login assessor = createAssessor(MASTER_DIFF + "UAT-assessor" + i);
            printNewLoginDetails(assessor, "Assessor");
        }
    }

    private Login createUser(String diff) {
        return new UserCreationApi().createUser(diff);
    }

    private void createUsers(int count) {
        for (int i = 1; i <= count; i++) {
            Login user = createUser(MASTER_DIFF + "UAT-user" + i);
            printNewLoginDetails(user, "User");
        }
    }

    private void createOrg1(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org1";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vtsDiff = "vts1";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, group1, areaOffice2User, aeDiff + "-" + vtsDiff);

        List<Login> testers =
                createTesters(6, "12", aeDiff, vtsDiff, asList(vtsId), group1, TesterStatus.QLFD,
                        areaOffice2User);
        vtsDataCollector.addAll(new VtsData(1, vtsId).addTesters(testers, group1));
        createSiteManager(aeDiff, aed, vtsId);
    }

    private void createOrg2(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org2";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vtsDiff = "vts2";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(3, 4, 5, 7), areaOffice2User, aeDiff + "-" + vtsDiff);

        List<Login> testers =
                createTesters(6, "3to7", aeDiff, vtsDiff, asList(vtsId), group2, TesterStatus.QLFD,
                        areaOffice2User);
        vtsDataCollector.addAll(new VtsData(2, vtsId).addTesters(testers, group2));
        createSiteManager(aeDiff, aed, vtsId);
    }


    private void createOrg3(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org3";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vtsDiff = "vts3";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(3, 4, 5, 7), areaOffice2User, aeDiff + "-" + vtsDiff);

        List<Login> testers =
                createTesters(6, "3to7", aeDiff, vtsDiff, asList(vtsId), group2, TesterStatus.QLFD,
                        areaOffice2User);
        vtsDataCollector.addAll(new VtsData(3, vtsId).addTesters(testers, group2));
        createSiteManager(aeDiff, aed, vtsId);
    }

    private void createOrg4(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org4";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vtsDiff = "vts4";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vtsDiff);

        List<Login> g1Testers =
                createTesters(5, "12", aeDiff, vtsDiff, asList(vtsId), group1, TesterStatus.QLFD,
                        areaOffice2User);
        List<Login> g2Testers =
                createTesters(5, "3to7", aeDiff, vtsDiff, asList(vtsId), group2, TesterStatus.QLFD,
                        areaOffice2User);
        vtsDataCollector.addAll(new VtsData(4, vtsId).addTesters(g1Testers, group1)
                        .addTesters(g2Testers, group2));

        createSiteManager(aeDiff, aed, vtsId);
    }

    private void createOrg5(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org5";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vtsDiff = "vts5";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vtsDiff);

        List<Login> g1Testers =
                createTesters(10, "12", aeDiff, vtsDiff, asList(vtsId), group1, TesterStatus.QLFD,
                        areaOffice2User);
        List<Login> g2Testers =
                createTesters(10, "3to7", aeDiff, vtsDiff, asList(vtsId), group2, TesterStatus.QLFD,
                        areaOffice2User);
        vtsDataCollector.addAll(new VtsData(5, vtsId).addTesters(g1Testers, group1)
                .addTesters(g2Testers, group2));
        createSiteManager(aeDiff, aed, vtsId);
    }

    private void createOrg6(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org6";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));

        String vtsDiff = "vts6";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), group1, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));

        vtsDataCollector.addAll(new VtsData(6, vtsId).addTesters(asList(aedm), group1));
    }

    private void createOrg7(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org7";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));
        createAedFromExistingPerson(asList(aeId), areaOffice2User, aedm,
                aedmData.getInt("personId"));

        String vtsDiff = "vts7";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(3, 4, 5, 7), areaOffice2User, aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), group2, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));
        vtsDataCollector.addAll(new VtsData(7, vtsId).addTesters(asList(aedm), group2));
    }

    private void createOrg8(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org8";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));
        createAedFromExistingPerson(asList(aeId), areaOffice2User, aedm,
                aedmData.getInt("personId"));

        String vtsDiff = "vts8";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(3, 4, 5, 7), areaOffice2User, aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), null, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));
        vtsDataCollector.addAll(new VtsData(8, vtsId).addTesters(asList(aedm), group1, group2));
    }

    private void createOrg9(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org9";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts9Diff = "vts9";
        String vts10Diff = "vts10";
        String vts11Diff = "vts11";

        int vts9Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts9Diff);
        int vts10Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts10Diff);
        int vts11Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts11Diff);

        List<Login> vts9Testers =
                createTesters(10, "all", aeDiff, vts9Diff, asList(vts9Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        List<Login> vts10Testers =
                createTesters(10, "all", aeDiff, vts10Diff, asList(vts10Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts11Testers =
                createTesters(10, "all", aeDiff, vts11Diff, asList(vts11Id), null,
                        TesterStatus.QLFD, areaOffice2User);

        createSiteManager(aeDiff, aed, vts9Id);
        createSiteManager(aeDiff, aed, vts10Id);
        createSiteManager(aeDiff, aed, vts11Id);

        vtsDataCollector.addAll(new VtsData(9, vts9Id).addTesters(vts9Testers, group1, group2),
                new VtsData(10, vts10Id).addTesters(vts10Testers, group1, group2),
                new VtsData(11, vts11Id).addTesters(vts11Testers, group1, group2));
    }

    private void createOrg10(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org10";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts12Diff = "vts12";
        String vts13Diff = "vts13";
        String vts1213Diff = "vts1213";
        String vts14Diff = "vts14";

        int vts12Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts12Diff);
        int vts13Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts13Diff);
        int vts14Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts14Diff);

        List<Login> vts12Testers =
                createTesters(5, "all", aeDiff, vts12Diff, asList(vts12Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        List<Login> vts13Testers =
                createTesters(5, "all", aeDiff, vts13Diff, asList(vts13Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts1213Diff, asList(vts12Id, vts13Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts14Testers =
                createTesters(5, "all", aeDiff, vts14Diff, asList(vts14Id), null, TesterStatus.QLFD,
                        areaOffice2User);

        createSiteManager(aeDiff, aed, vts12Id);
        createSiteManager(aeDiff, aed, vts13Id);
        createSiteManager(aeDiff, aed, vts14Id);

        vtsDataCollector.addAll(new VtsData(12, vts12Id).addTesters(vts12Testers, group1, group2),
                new VtsData(13, vts13Id).addTesters(vts13Testers, group1, group2),
                new VtsData(14, vts14Id).addTesters(vts14Testers, group1, group2));
    }

    private void createOrg11(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org11";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts15Diff = "vts15";
        String vts16Diff = "vts16";
        String vts17Diff = "vts17";

        int vts15Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts15Diff);
        int vts16Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts16Diff);
        int vts17Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts17Diff);

        List<Login> vts15G1Testers =
                createTesters(5, "12", aeDiff, vts15Diff, asList(vts15Id), group1,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts15G2Testers =
                createTesters(5, "3to7", aeDiff, vts15Diff, asList(vts15Id), group2,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts16G1Testers =
                createTesters(5, "12", aeDiff, vts16Diff, asList(vts16Id), group1,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts16G2Testers =
                createTesters(5, "3to7", aeDiff, vts16Diff, asList(vts16Id), group2,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts17G1Testers =
                createTesters(5, "12", aeDiff, vts17Diff, asList(vts17Id), group1,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts17G2Testers =
                createTesters(5, "3to7", aeDiff, vts17Diff, asList(vts17Id), group2,
                        TesterStatus.QLFD, areaOffice2User);

        createSiteManager(aeDiff, aed, vts15Id);
        createSiteManager(aeDiff, aed, vts16Id);
        createSiteManager(aeDiff, aed, vts17Id);

        vtsDataCollector.addAll(new VtsData(15, vts15Id).addTesters(vts15G1Testers, group1)
                .addTesters(vts15G2Testers, group2),
                new VtsData(16, vts16Id).addTesters(vts16G1Testers, group1)
                        .addTesters(vts16G2Testers, group2),
                new VtsData(17, vts17Id).addTesters(vts17G1Testers, group1)
                        .addTesters(vts17G2Testers, group2));
    }

    private void createOrg12(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org12";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));

        String vtsDiff = "vts18";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), null, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));

        vtsDataCollector.addAll(new VtsData(18, vtsId).addTesters(asList(aedm), group1, group2));
    }

    private void createOrg14(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org14";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));

        String vtsDiff = "vts20";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(3, 4, 5, 7), areaOffice2User, aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), group2, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));

        vtsDataCollector.addAll(new VtsData(20, vtsId).addTesters(asList(aedm), group1, group2));
    }

    private void createOrg15(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org15";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));

        String vtsDiff = "vts21";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(3, 4, 5, 7), areaOffice2User, aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), group2, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));

        vtsDataCollector.addAll(new VtsData(21, vtsId).addTesters(asList(aedm), group1, group2));
    }

    private void createOrg16(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org16";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        Login aedm = createAedmLoginCredentials(aedmData.getString("username"),
                aedmData.getString("password"));

        String vtsDiff = "vts22";

        int vtsId = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vtsDiff);

        createSiteManagerFromExistingPerson(asList(vtsId), aedm, aedmData.getInt("personId"));

        createTesterFromExistingPerson(asList(vtsId), null, TesterStatus.QLFD, aedm,
                aedmData.getInt("personId"));

        vtsDataCollector.addAll(new VtsData(22, vtsId).addTesters(asList(aedm), group1, group2));
    }

    private void createOrg21(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org21";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts24Diff = "vts24";
        String vts25Diff = "vts25";
        String vts26Diff = "vts26";

        int vts24Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts24Diff);
        int vts25Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts25Diff);
        int vts26Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts26Diff);

        List<Login> vts24Testers =
                createTesters(5, "all", aeDiff, vts24Diff, asList(vts24Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        List<Login> vts25Testers =
                createTesters(5, "all", aeDiff, vts25Diff, asList(vts25Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        List<Login> vts26Testers =
                createTesters(5, "all", aeDiff, vts26Diff, asList(vts26Id), null, TesterStatus.QLFD,
                        schemeManagementUser);

        createSiteManager(aeDiff, aed, vts24Id);
        createSiteManager(aeDiff, aed, vts25Id);
        createSiteManager(aeDiff, aed, vts26Id);

        vtsDataCollector.addAll(new VtsData(24, vts24Id).addTesters(vts24Testers, group1, group2),
                new VtsData(25, vts25Id).addTesters(vts25Testers, group1, group2),
                new VtsData(26, vts26Id).addTesters(vts26Testers, group1, group2));
    }

    private void createOrg22(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org22";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts27Diff = "vts27";
        String vts2728Diff = "vts2728";
        String vts28Diff = "vts28";
        String vts2829Diff = "vts2829";
        String vts29Diff = "vts29";

        int vts27Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts27Diff);
        int vts28Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts28Diff);
        int vts29Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts29Diff);

        List<Login> vts27Testers =
                createTesters(5, "all", aeDiff, vts27Diff, asList(vts27Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts2728Diff, asList(vts27Id, vts28Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts28Testers =
                createTesters(5, "all", aeDiff, vts28Diff, asList(vts28Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts2829Diff, asList(vts28Id, vts29Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts29Testers =
                createTesters(5, "all", aeDiff, vts29Diff, asList(vts29Id), null, TesterStatus.QLFD,
                        areaOffice2User);

        createSiteManager(aeDiff, aed, vts27Id);
        createSiteManager(aeDiff, aed, vts28Id);
        createSiteManager(aeDiff, aed, vts29Id);

        vtsDataCollector.addAll(new VtsData(27, vts27Id).addTesters(vts27Testers, group1, group2),
                new VtsData(28, vts28Id).addTesters(vts28Testers, group1, group2),
                new VtsData(29, vts29Id).addTesters(vts29Testers, group1, group2));
    }

    private void createOrg23(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org23";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts30Diff = "vts30";
        String vts3031Diff = "vts3031";
        String vts31Diff = "vts31";
        String vts3132Diff = "vts3132";
        String vts32Diff = "vts32";

        int vts30Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vts30Diff);
        int vts31Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vts31Diff);
        int vts32Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vts32Diff);

        List<Login> vts30Testers =
                createTesters(5, "all", aeDiff, vts30Diff, asList(vts30Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts3031Diff, asList(vts30Id, vts31Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts31Testers =
                createTesters(5, "all", aeDiff, vts31Diff, asList(vts31Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts3132Diff, asList(vts31Id, vts32Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts32Testers =
                createTesters(5, "all", aeDiff, vts32Diff, asList(vts32Id), null, TesterStatus.QLFD,
                        areaOffice2User);

        createSiteManager(aeDiff, aed, vts30Id);
        createSiteManager(aeDiff, aed, vts31Id);
        createSiteManager(aeDiff, aed, vts32Id);

        vtsDataCollector.addAll(new VtsData(30, vts30Id).addTesters(vts30Testers, group1, group2),
                new VtsData(31, vts31Id).addTesters(vts31Testers, group1, group2),
                new VtsData(32, vts32Id).addTesters(vts32Testers, group1, group2));
    }

    private void createOrg24(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org24";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts33Diff = "vts33";
        String vts3334Diff = "vts3334";
        String vts34Diff = "vts34";
        String vts3435Diff = "vts3435";
        String vts35Diff = "vts35";

        int vts33Id = new VtsCreationApi().createVts(aeId, asList(3, 4, 5, 7), areaOffice2User,
                aeDiff + "-" + vts33Diff);
        int vts34Id = new VtsCreationApi().createVts(aeId, asList(3, 4, 5, 7), areaOffice2User,
                aeDiff + "-" + vts34Diff);
        int vts35Id = new VtsCreationApi().createVts(aeId, asList(3, 4, 5, 7), areaOffice2User,
                aeDiff + "-" + vts35Diff);

        List<Login> vts33Testers =
                createTesters(5, "all", aeDiff, vts33Diff, asList(vts33Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts3334Diff, asList(vts33Id, vts34Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts34Testers =
                createTesters(5, "all", aeDiff, vts34Diff, asList(vts34Id), null, TesterStatus.QLFD,
                        areaOffice2User);
        createTesters(5, "all", aeDiff, vts3435Diff, asList(vts34Id, vts35Id), null,
                TesterStatus.QLFD, areaOffice2User);
        List<Login> vts35Testers =
                createTesters(5, "all", aeDiff, vts35Diff, asList(vts35Id), null, TesterStatus.QLFD,
                        areaOffice2User);

        createSiteManager(aeDiff, aed, vts33Id);
        createSiteManager(aeDiff, aed, vts34Id);
        createSiteManager(aeDiff, aed, vts35Id);

        vtsDataCollector.addAll(new VtsData(33, vts33Id).addTesters(vts33Testers, group1, group2),
                new VtsData(34, vts34Id).addTesters(vts34Testers, group1, group2),
                new VtsData(35, vts35Id).addTesters(vts35Testers, group1, group2));
    }

    private void createOrg25(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org25";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts36Diff = "vts36";
        String vts37Diff = "vts37";
        String vts38Diff = "vts38";

        int vts36Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts36Diff);
        int vts37Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts37Diff);
        int vts38Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2, 3, 4, 5, 7), areaOffice2User,
                        aeDiff + "-" + vts38Diff);

        List<Login> vts36Testers =
                createTesters(10, "all", aeDiff, vts36Diff, asList(vts36Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts37Testers =
                createTesters(10, "all", aeDiff, vts37Diff, asList(vts37Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts38Testers =
                createTesters(10, "all", aeDiff, vts38Diff, asList(vts38Id), null,
                        TesterStatus.QLFD, areaOffice2User);

        createSiteManager(aeDiff, aed, vts36Id);
        createSiteManager(aeDiff, aed, vts37Id);
        createSiteManager(aeDiff, aed, vts38Id);

        vtsDataCollector.addAll(new VtsData(36, vts36Id).addTesters(vts36Testers, group1, group2),
                new VtsData(37, vts37Id).addTesters(vts37Testers, group1, group2),
                new VtsData(38, vts38Id).addTesters(vts38Testers, group1, group2));
    }

    private void createOrg26(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org26";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts39Diff = "vts39";
        String vts40Diff = "vts40";
        String vts41Diff = "vts41";

        int vts39Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vts39Diff);
        int vts40Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vts40Diff);
        int vts41Id = new VtsCreationApi()
                .createVts(aeId, asList(1, 2), areaOffice2User, aeDiff + "-" + vts41Diff);

        List<Login> vts39Testers =
                createTesters(10, "all", aeDiff, vts39Diff, asList(vts39Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts40Testers =
                createTesters(10, "all", aeDiff, vts40Diff, asList(vts40Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts41Testers =
                createTesters(10, "all", aeDiff, vts41Diff, asList(vts41Id), null,
                        TesterStatus.QLFD, areaOffice2User);

        createSiteManager(aeDiff, aed, vts39Id);
        createSiteManager(aeDiff, aed, vts40Id);
        createSiteManager(aeDiff, aed, vts41Id);

        vtsDataCollector.addAll(new VtsData(39, vts39Id).addTesters(vts39Testers, group1, group2),
                new VtsData(40, vts40Id).addTesters(vts40Testers, group1, group2),
                new VtsData(41, vts41Id).addTesters(vts41Testers, group1, group2));
    }

    private void createOrg27(VtsDataCollector vtsDataCollector) {
        String aeDiff = MASTER_DIFF + "Org27";

        int aeId = createAe(1000, aeDiff);
        JsonObject aedmData = createAedm(aeDiff, aeId);
        createAedmLoginCredentials(aedmData.getString("username"), aedmData.getString("password"));
        Login aed = createAed(aeDiff, aeId);

        String vts42Diff = "vts42";
        String vts43Diff = "vts43";
        String vts44Diff = "vts44";

        int vts42Id = new VtsCreationApi().createVts(aeId, asList(3, 4, 5, 7), areaOffice2User,
                aeDiff + "-" + vts42Diff);
        int vts43Id = new VtsCreationApi().createVts(aeId, asList(3, 4, 5, 7), areaOffice2User,
                aeDiff + "-" + vts43Diff);
        int vts44Id = new VtsCreationApi().createVts(aeId, asList(3, 4, 5, 7), areaOffice2User,
                aeDiff + "-" + vts44Diff);

        List<Login> vts42Testers =
                createTesters(10, "all", aeDiff, vts42Diff, asList(vts42Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts43Testers =
                createTesters(10, "all", aeDiff, vts43Diff, asList(vts43Id), null,
                        TesterStatus.QLFD, areaOffice2User);
        List<Login> vts44Testers =
                createTesters(10, "all", aeDiff, vts44Diff, asList(vts44Id), null,
                        TesterStatus.QLFD, areaOffice2User);

        createSiteManager(aeDiff, aed, vts42Id);
        createSiteManager(aeDiff, aed, vts43Id);
        createSiteManager(aeDiff, aed, vts44Id);

        vtsDataCollector.addAll(new VtsData(42, vts42Id).addTesters(vts42Testers, group1, group2),
                new VtsData(43, vts43Id).addTesters(vts43Testers, group1, group2),
                new VtsData(44, vts44Id).addTesters(vts44Testers, group1, group2));
    }

    private int createAe(int slots, String aeDiff) {
        int aeId = new AeCreationApi().createAe(aeDiff, areaOffice2User, slots);
        return aeId;
    }

    private JsonObject createAedm(String aeDiff, int aeId) {
        return new AedmCreationApi()
                .createAedm(asList(aeId), areaOffice2User, aeDiff + "-AEDM", false);
    }

    private Login createAedmLoginCredentials(String username, String password) {
        Login login = new Login(username, password);
        printNewLoginDetails(login, "AEDM");
        return login;
    }

    private Login createAed(String aeDiff, int aeId) {
        Login aedLogin =
                new AedCreationApi().createAed(asList(aeId), areaOffice2User, aeDiff + "-AED");
        printNewLoginDetails(aedLogin, "AED");
        return aedLogin;
    }

    private List<Login> createTesters(int count, String diffStart, String aeDiff, String vtsDiff,
            Collection<Integer> vtsIds, TestGroup testerGroup, TesterStatus status, Login schm) {
        List<Login> createdTesters = new ArrayList<>();

        for (int i = 1; i <= count; i++) {
            Login testerLogin =
                    createTester(i, diffStart, aeDiff, vtsDiff, vtsIds, testerGroup, status, schm,
                            false);
            createdTesters.add(testerLogin);
        }

        //generate extra testers whose need claim account
        Login testerLogin =
                createTester(++count, diffStart, aeDiff, vtsDiff, vtsIds, testerGroup, status, schm,
                        true);
        createdTesters.add(testerLogin);

        return createdTesters;
    }

    private Login createTester(int testerNumber, String diffStart, String aeDiff, String vtsDiff,
            Collection<Integer> vtsIds, TestGroup testerGroup, TesterStatus status, Login schm,
            Boolean accountClaimRequired) {
        String testerDiff = String.valueOf(testerNumber);
        Login testerLogin = new TesterCreationApi().createTester(vtsIds, testerGroup, status, schm,
                diffStart + "-" + aeDiff + "-" + vtsDiff + "-" + testerDiff,
            accountClaimRequired, false);

        printNewLoginDetails(testerLogin,
                "tester class " + (null == testerGroup ? "all" : testerGroup.description));

        return testerLogin;
    }

    private void createTesterFromExistingPerson(Collection<Integer> vtsId, TestGroup testerGroup,
            TesterStatus status, Login aedm, int personId) {

        new TesterCreationApi()
                .createTesterRoleForExistingPerson(vtsId, testerGroup, status, aedm, personId);
        System.out.println("User " + aedm.username + " assigned Tester role.");
    }

    private void createAedFromExistingPerson(Collection<Integer> aeIds, Login schm, Login aedm,
            int personId) {

        new AedCreationApi().createAedRoleForExistingPerson(aeIds, schm, aedm, personId);
        System.out.println("User " + aedm.username + " assigned AED role.");
    }

    private void createSiteManager(String aeDiff, Login person, int vtsA) {
        Login siteManagerLogin = new SiteManagerCreationApi()
                .createSm(asList(vtsA), person, aeDiff + "-sm-vts" + vtsA);

        printNewLoginDetails(siteManagerLogin, "site manager");
    }

    private void createSiteManagerFromExistingPerson(Collection<Integer> aeIds, Login person,
            int personId) {

        new SiteManagerCreationApi().createSmForExistingPerson(aeIds, person, personId);
        System.out.println("User " + person.username + " assigned Site Manager role.");
    }

    private void printNewLoginDetails(Login newLogin, String loginType) {
        System.out.println(
                "new " + loginType + " created: [" + newLogin.username + "]/[" + newLogin.password
                        + "]");
    }

}
