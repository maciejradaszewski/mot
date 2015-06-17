package com.dvsa.mot.selenium.priv.testdata.tradeuser.provider;

import com.dvsa.mot.selenium.datasource.Login;
import com.dvsa.mot.selenium.framework.api.AeCreationApi;
import com.dvsa.mot.selenium.framework.api.AedmCreationApi;
import com.dvsa.mot.selenium.framework.api.helper.Emailifier;
import com.dvsa.mot.selenium.priv.testdata.tradeuser.entity.AeSimple;

public class AeProvider {

    private Login managementLogin;
    private AeCreationApi aeCreationApi;
    private AedmCreationApi aedmCreationApi;

    public AeProvider(Login managementLogin) {
        this.managementLogin = managementLogin;
        this.aeCreationApi = new AeCreationApi();
        this.aedmCreationApi = new AedmCreationApi();
    }

    /**
     * @param ae
     * @return id of created AE
     */
    public int createAeWithAedm(AeSimple ae) {
        int aeId = aeCreationApi.createAe(
                new AeCreationApi.AeData().name(ae.getName()).slots(ae.getSlotsCount())
                        .email(Emailifier.fromString(ae.getName()))

                , managementLogin);
        aedmCreationApi.createAedm(aeId, managementLogin, false);

        return aeId;
    }
}
