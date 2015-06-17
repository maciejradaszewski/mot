package com.dvsa.mot.selenium.datasource.dynamic;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.SiteManagerCreationApi;
import com.dvsa.mot.selenium.framework.api.TestGroup;
import com.dvsa.mot.selenium.framework.api.TesterCreationApi;
import com.dvsa.mot.selenium.framework.api.VtsCreationApi;

import java.util.ArrayList;
import java.util.Collections;
import java.util.UUID;

/**
 * Created by Mark Hitchins on 09/09/2014.
 */
public class VTS {

    public String name;
    public int id;
    //public String aeName;

    public Login siteManager;
    public ArrayList<Login> testers;

    public TestGroup testGroup;

    //public VTSDetails vtsDetails;

    public VTS(String requiredVtsName, Login areaOfficer, int aeId, Login aed) {
        this(requiredVtsName, areaOfficer, aeId, 1, aed);
    }

    public VTS(String requiredVtsName, Login areaOfficer, int aeId, int numberOfTesters,
            Login aed) {
        this(requiredVtsName, areaOfficer, aeId, 1, aed, true);
    }

    public VTS(String requiredVtsName, Login areaOfficer, int aeId, int numberOfTesters, Login aed,
            boolean createSiteManager) {

        name = requiredVtsName;
        testGroup = TestGroup.group1;
        id = new VtsCreationApi().createVts(aeId, testGroup, areaOfficer, name);

        testers = new ArrayList<Login>();

        TesterCreationApi tca = new TesterCreationApi();
        for (int i = 1; i <= numberOfTesters; i++)
            testers.add(tca.createTester(Collections.singletonList(id), testGroup,
                    TesterCreationApi.TesterStatus.QLFD, areaOfficer,
                    name + "-" + RandomDataGenerator
                            .generateRandomAlphaNumeric(5, UUID.randomUUID().hashCode()),
                false, false));

        if (createSiteManager) {
            siteManager = new SiteManagerCreationApi()
                    .createSm(Collections.singletonList(id), areaOfficer, name + "-sm");
        }
    }
}
