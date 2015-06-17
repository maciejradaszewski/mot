package com.dvsa.mot.selenium.priv.testdata.tradeuser;

import com.beust.jcommander.internal.Lists;
import com.beust.jcommander.internal.Maps;
import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.datasource.Vehicle;
import com.dvsa.mot.selenium.framework.Utilities;
import com.dvsa.mot.selenium.framework.api.AreaOffice2UserCreationApi;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.AeSimple;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.TesterSimple;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.VtsSimple;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.provider.AeProvider;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.provider.TesterProvider;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.provider.VehicleProvider;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.provider.VtsProvider;

import java.util.Collection;
import java.util.List;
import java.util.Map;
import java.util.Set;

import static com.dvsa.mot.selenium.priv.testdata.tradeuser.DataSet.*;

/**
 * See VM-4462
 * Always creates the same sets of 'realistic' data.
 */
public class DataGeneratorForTradeUser {

    private VehicleProvider vehicleProvider;
    private AeProvider aeProvider;
    private VtsProvider vtsProvider;
    private TesterProvider testerProvider;

    public static void main(String[] args) {
        new DataGeneratorForTradeUser().generateData();
    }

    public void generateData() {
        initProviders();
        createVehicles(CARS);
        createVehicles(BIKES);
        createAesAndVtssAndTesters();

        Utilities.Logger.LogInfo("Done.");
    }

    private void initProviders() {
        Utilities.Logger.LogInfo("Creating Management Login to rule them all...");
        Login managementLogin = new AreaOffice2UserCreationApi().createAreaOffice2User("RootAO2-1");

        vehicleProvider = new VehicleProvider();
        aeProvider = new AeProvider(managementLogin);
        vtsProvider = new VtsProvider(managementLogin);
        testerProvider = new TesterProvider(managementLogin);
    }

    private void createVehicles(List<Vehicle> vehicles) {
        for (Vehicle vehicle : vehicles) {
            Utilities.Logger.LogInfo(
                    "Creating a Vehicle [" + vehicle.carReg + " / " + vehicle.fullVIN + "]");
            vehicleProvider.createVehicle(vehicle);
        }
    }

    private void createAesAndVtssAndTesters() {
        Map<VtsSimple, Integer> createdVtses = Maps.newHashMap();

        for (AeSimple ae : AUTHORISED_EXAMINERS) {
            Utilities.Logger.LogInfo("Creating an AE [" + ae.getName() + "]");
            int aeId = aeProvider.createAeWithAedm(ae);
            createdVtses.putAll(createVtss(aeId, ae.getVtses()));
        }

        for (TesterSimple tester : TESTERS) {
            Utilities.Logger.LogInfo("Creating a Tester [" + tester.getUsername() + "]");
            List<Integer> idsOfTestersVtses = getIdsOfVtss(tester.getVtses(), createdVtses);
            testerProvider.createTester(tester, idsOfTestersVtses);
        }
    }

    private Map<VtsSimple, Integer> createVtss(int aeId, Collection<VtsSimple> vtses) {

        Map<VtsSimple, Integer> createdVtss = Maps.newHashMap();
        for (VtsSimple vts : vtses) {
            Utilities.Logger.LogInfo("Creating a VTS [" + vts.getName() + "]");
            createdVtss.put(vts, vtsProvider.createVts(aeId, vts));
        }

        return createdVtss;
    }

    private List<Integer> getIdsOfVtss(Set<VtsSimple> interestedIn,
            Map<VtsSimple, Integer> existing) {
        List<Integer> ids = Lists.newArrayList();

        for (VtsSimple vts : interestedIn) {
            ids.add(existing.get(vts));
        }

        return ids;
    }
}
