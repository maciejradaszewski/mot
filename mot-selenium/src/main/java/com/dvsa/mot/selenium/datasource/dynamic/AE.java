package com.dvsa.mot.selenium.datasource.dynamic;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.RandomDataGenerator;
import com.dvsa.mot.selenium.framework.api.*;

import javax.json.JsonObject;
import java.util.ArrayList;
import java.util.Collections;
import java.util.UUID;

/**
 * Created by Mark Hitchins on 09/09/2014.
 */
public class AE {

    public String name;
    final public int aeId;
    final public int slots;
    public Login aedm;
    public Login aed;
    public Login schemeManager;
    public Login areaOfficer;

    public ArrayList<VTS> vtses;

    //Create AE with random VTS name, 1000 slots and 1 associated VTS
    public AE() {
        this(RandomDataGenerator.generateRandomString(10, System.nanoTime()), 1000, 1);
    }

    public AE(int numberOfVTS) {
        this(RandomDataGenerator.generateRandomString(10, System.nanoTime()), 1000, numberOfVTS);
    }

    public AE(String requiredName, int numberOfVTS) {
        this(requiredName, 1000, numberOfVTS);
    }

    public AE(String requiredName, int requiredSlots, int numberOfVTS) {
        name = requiredName;
        slots = requiredSlots;

        aeId = new AeCreationApi()
                .createAe(RandomDataGenerator.generateRandomString(5, UUID.randomUUID().hashCode()),
                        Login.LOGIN_AREA_OFFICE1, slots);

        schemeManager =
                new SchemeManagementUserCreationApi().createSchemeManagementUser("schm-" + name);
        areaOfficer = new AreaOffice1UserCreationApi().createAreaOffice1User("ao-" + name);
        JsonObject aedmData = new AedmCreationApi()
                .createAedm(Collections.singletonList(aeId), areaOfficer, "aedm-" + name, false);
        aedm = new Login(aedmData.getString("username"), aedmData.getString("password"));
        aed = new AedCreationApi()
                .createAed(Collections.singletonList(aeId), areaOfficer, "aed-" + name);

        if (numberOfVTS > 0) {
            addVtsToAE(numberOfVTS);
        }
    }

    public void addVtsToAE(int numberOfVTS) {
        addVtsToAE(numberOfVTS, 1, true);
    }

    public void addVtsToAE(int numberOfVTS, int numberOfTesters, boolean createSiteManager) {
        vtses = new ArrayList<VTS>();

        for (int i = 1; i <= numberOfVTS; i++) {
            String vtsName = name + "-vts" + i;
            vtses.add(new VTS(vtsName, areaOfficer, aeId, numberOfTesters, aed, createSiteManager));
        }
    }
}
